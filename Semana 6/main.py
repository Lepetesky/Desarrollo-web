import os
import re
from contextlib import asynccontextmanager
from typing import Any

from bson import ObjectId
from dotenv import load_dotenv
from fastapi import Depends, FastAPI, HTTPException, Query, Request, Response, status
from motor.motor_asyncio import AsyncIOMotorClient
from pydantic import BaseModel, Field


load_dotenv()

MONGODB_URI = os.getenv("MONGODB_URI", "mongodb://localhost:27017")
DB_NAME = os.getenv("DB_NAME", "tokyo_noodles")
COLL_NAME = os.getenv("COLL_NAME", "items")


class Item(BaseModel):
    nombre: str = Field(min_length=1)
    precio: float = Field(gt=0)
    tags: list[str] = Field(default_factory=list)
    activo: bool = True


class ItemIn(Item):
    pass


class ItemOut(Item):
    id: str


def doc_to_itemout(doc: dict[str, Any]) -> ItemOut:
    return ItemOut(
        id=str(doc["_id"]),
        nombre=doc["nombre"],
        precio=doc["precio"],
        tags=doc.get("tags", []),
        activo=doc.get("activo", True),
    )


@asynccontextmanager
async def lifespan(app: FastAPI):
    client = AsyncIOMotorClient(MONGODB_URI)
    app.state.collection = client[DB_NAME][COLL_NAME]
    yield
    client.close()


app = FastAPI(
    title="Tokyo Noodles API",
    description="API REST de items creada con FastAPI y MongoDB.",
    version="1.0.0",
    lifespan=lifespan,
)


def get_collection(request: Request):
    return request.app.state.collection


def parse_object_id(item_id: str) -> ObjectId:
    if not ObjectId.is_valid(item_id):
        raise HTTPException(status_code=400, detail="ID invalido")
    return ObjectId(item_id)


@app.get("/health")
async def health() -> dict[str, str]:
    return {"status": "ok"}


@app.get("/items", response_model=list[ItemOut])
async def list_items(
    q: str | None = None,
    skip: int = Query(default=0, ge=0),
    limit: int = Query(default=50, ge=1, le=200),
    collection=Depends(get_collection),
) -> list[ItemOut]:
    query: dict[str, Any] = {}
    if q:
        query["nombre"] = {"$regex": re.escape(q), "$options": "i"}

    items = []
    cursor = collection.find(query).skip(skip).limit(limit)
    async for doc in cursor:
        items.append(doc_to_itemout(doc))
    return items


@app.post("/items", response_model=ItemOut, status_code=status.HTTP_201_CREATED)
async def create_item(item: ItemIn, collection=Depends(get_collection)) -> ItemOut:
    result = await collection.insert_one(item.model_dump())
    doc = await collection.find_one({"_id": result.inserted_id})
    if doc is None:
        raise HTTPException(status_code=500, detail="No se pudo recuperar el item creado")
    return doc_to_itemout(doc)


@app.get("/items/{item_id}", response_model=ItemOut)
async def get_item(item_id: str, collection=Depends(get_collection)) -> ItemOut:
    doc = await collection.find_one({"_id": parse_object_id(item_id)})
    if doc is None:
        raise HTTPException(status_code=404, detail="Item no encontrado")
    return doc_to_itemout(doc)


@app.put("/items/{item_id}", response_model=ItemOut)
async def update_item(
    item_id: str,
    item: ItemIn,
    collection=Depends(get_collection),
) -> ItemOut:
    object_id = parse_object_id(item_id)
    result = await collection.update_one(
        {"_id": object_id},
        {"$set": item.model_dump()},
    )
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Item no encontrado")

    doc = await collection.find_one({"_id": object_id})
    if doc is None:
        raise HTTPException(status_code=404, detail="Item no encontrado")
    return doc_to_itemout(doc)


@app.delete("/items/{item_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_item(item_id: str, collection=Depends(get_collection)) -> Response:
    result = await collection.delete_one({"_id": parse_object_id(item_id)})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="Item no encontrado")
    return Response(status_code=status.HTTP_204_NO_CONTENT)
