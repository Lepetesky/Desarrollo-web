import os
import secrets
from functools import lru_cache
from typing import Annotated

import hvac
from fastapi import Depends, FastAPI, Header, HTTPException, status


app = FastAPI(
    title="Tokyo Noodles - Backend API",
    description="API interna protegida para productos y pedidos de Tokyo Noodles",
)


# Vault guarda el secreto compartido. En el codigo solo dejamos la ruta y los
# nombres de configuracion, nunca el valor real del secreto.
@lru_cache
def get_gateway_secret() -> str:
    vault_addr = os.getenv("VAULT_ADDR", "http://127.0.0.1:8200")
    vault_token = os.getenv("VAULT_TOKEN")
    mount_point = os.getenv("VAULT_MOUNT_POINT", "secret")
    secret_path = os.getenv("VAULT_SECRET_PATH", "tokyo-noodles/security")

    if not vault_token:
        raise RuntimeError("Falta configurar VAULT_TOKEN en el entorno local")

    client = hvac.Client(url=vault_addr, token=vault_token)
    if not client.is_authenticated():
        raise RuntimeError("Vault rechazo el token configurado")

    response = client.secrets.kv.v2.read_secret_version(
        path=secret_path,
        mount_point=mount_point,
        raise_on_deleted_version=True,
    )
    gateway_secret = response["data"]["data"].get("gateway_secret")

    if not gateway_secret:
        raise RuntimeError("Vault no contiene la clave gateway_secret")

    return str(gateway_secret)


# Todos los endpoints internos pasan por esta dependencia. compare_digest evita
# comparar el secreto con una operacion comun que pueda filtrar informacion.
def require_gateway_secret(
    x_gateway_secret: Annotated[str | None, Header()] = None,
) -> None:
    expected_secret = get_gateway_secret()

    if not x_gateway_secret or not secrets.compare_digest(
        x_gateway_secret,
        expected_secret,
    ):
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Acceso directo al backend no autorizado",
        )


backend_protection = [Depends(require_gateway_secret)]


@app.get("/health", dependencies=backend_protection)
def health():
    return {
        "status": "OK",
        "service": "Tokyo Noodles Backend API",
    }


@app.get("/products", dependencies=backend_protection)
def products():
    return {
        "products": [
            {"id": 1, "name": "Ramen Tokyo", "price": 8900},
            {"id": 2, "name": "Gyozas de cerdo", "price": 4500},
            {"id": 3, "name": "Te verde helado", "price": 2000},
        ]
    }


@app.get("/orders", dependencies=backend_protection)
def orders():
    return {
        "orders": [
            {"id": 1001, "status": "paid"},
            {"id": 1002, "status": "pending"},
        ]
    }
