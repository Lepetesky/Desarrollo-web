import re
from contextlib import contextmanager
from types import SimpleNamespace

from bson import ObjectId
from fastapi.testclient import TestClient

from main import app


class FakeCursor:
    def __init__(self, documents):
        self.documents = documents
        self.offset = 0
        self.maximum = len(documents)

    def skip(self, amount):
        self.offset = amount
        return self

    def limit(self, amount):
        self.maximum = amount
        return self

    def __aiter__(self):
        selected = self.documents[self.offset : self.offset + self.maximum]
        self.iterator = iter(selected)
        return self

    async def __anext__(self):
        try:
            return next(self.iterator)
        except StopIteration as error:
            raise StopAsyncIteration from error


class FakeCollection:
    def __init__(self):
        self.documents = {}

    async def insert_one(self, document):
        object_id = ObjectId()
        self.documents[object_id] = {"_id": object_id, **document}
        return SimpleNamespace(inserted_id=object_id)

    async def find_one(self, query):
        document = self.documents.get(query["_id"])
        return document.copy() if document else None

    def find(self, query):
        documents = list(self.documents.values())
        if "nombre" in query:
            pattern = query["nombre"]["$regex"]
            documents = [
                document
                for document in documents
                if re.search(pattern, document["nombre"], re.IGNORECASE)
            ]
        return FakeCursor([document.copy() for document in documents])

    async def update_one(self, query, update):
        document = self.documents.get(query["_id"])
        if document is None:
            return SimpleNamespace(matched_count=0)
        document.update(update["$set"])
        return SimpleNamespace(matched_count=1)

    async def delete_one(self, query):
        document = self.documents.pop(query["_id"], None)
        return SimpleNamespace(deleted_count=int(document is not None))


@contextmanager
def client_for_tests():
    with TestClient(app) as client:
        app.state.collection = FakeCollection()
        yield client


def test_health():
    with client_for_tests() as client:
        response = client.get("/health")
        assert response.status_code == 200
        assert response.json() == {"status": "ok"}


def test_complete_item_crud():
    with client_for_tests() as client:
        payload = {
            "nombre": "Ramen Tonkotsu",
            "precio": 10990,
            "tags": ["ramen", "cerdo"],
        }
        created = client.post("/items", json=payload)
        assert created.status_code == 201
        item_id = created.json()["id"]
        assert created.json()["activo"] is True

        fetched = client.get(f"/items/{item_id}")
        assert fetched.status_code == 200
        assert fetched.json()["nombre"] == "Ramen Tonkotsu"

        updated_payload = {
            "nombre": "Ramen Miso",
            "precio": 9990,
            "tags": ["ramen", "miso"],
            "activo": False,
        }
        updated = client.put(f"/items/{item_id}", json=updated_payload)
        assert updated.status_code == 200
        assert updated.json()["nombre"] == "Ramen Miso"
        assert updated.json()["activo"] is False

        deleted = client.delete(f"/items/{item_id}")
        assert deleted.status_code == 204
        assert deleted.content == b""
        assert client.get(f"/items/{item_id}").status_code == 404


def test_search_and_pagination():
    with client_for_tests() as client:
        for nombre in ["Ramen Shoyu", "Gyozas", "Ramen Miso"]:
            client.post("/items", json={"nombre": nombre, "precio": 5990})

        searched = client.get("/items", params={"q": "RAMEN"})
        assert searched.status_code == 200
        assert [item["nombre"] for item in searched.json()] == [
            "Ramen Shoyu",
            "Ramen Miso",
        ]

        paginated = client.get("/items", params={"skip": 1, "limit": 1})
        assert paginated.status_code == 200
        assert [item["nombre"] for item in paginated.json()] == ["Gyozas"]


def test_validation_and_error_responses():
    with client_for_tests() as client:
        invalid_item = client.post("/items", json={"nombre": "", "precio": 0})
        assert invalid_item.status_code == 422
        assert client.get("/items?skip=-1").status_code == 422
        assert client.get("/items?limit=201").status_code == 422

        assert client.get("/items/id-invalido").status_code == 400
        assert client.put(
            "/items/id-invalido",
            json={"nombre": "Ramen", "precio": 1000},
        ).status_code == 400
        assert client.delete("/items/id-invalido").status_code == 400

        missing_id = str(ObjectId())
        assert client.get(f"/items/{missing_id}").status_code == 404
        assert client.delete(f"/items/{missing_id}").status_code == 404
