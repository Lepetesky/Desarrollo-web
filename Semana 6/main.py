import os
import re
from contextlib import asynccontextmanager
from typing import Any

from bson import ObjectId
from dotenv import load_dotenv
from fastapi import Depends, FastAPI, HTTPException, Query, Request
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
    description="Primera parte de la API REST con FastAPI y MongoDB.",
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


@app.get("/items/{item_id}", response_model=ItemOut)
async def get_item(item_id: str, collection=Depends(get_collection)) -> ItemOut:
    doc = await collection.find_one({"_id": parse_object_id(item_id)})
    if doc is None:
        raise HTTPException(status_code=404, detail="Item no encontrado")
    return doc_to_itemout(doc)
