import os
import secrets
from typing import Annotated

from fastapi import Depends, FastAPI, Header, HTTPException, status


app = FastAPI(
    title="Protected Backend API",
    description="API remota que solo acepta solicitudes del API Gateway",
)


# El backend recibe su credencial mediante el entorno del HOST B. No necesita
# conocer el token del cliente ni conectarse directamente a Vault.
def get_internal_gateway_secret() -> str:
    configured_secret = os.getenv("INTERNAL_GATEWAY_SECRET")
    if not configured_secret:
        raise RuntimeError("INTERNAL_GATEWAY_SECRET no esta configurado")
    return configured_secret


# Esta dependencia protege los recursos de negocio. compare_digest permite
# comparar credenciales sin usar una comparacion comun de texto.
def verify_gateway(
    x_gateway_secret: Annotated[str, Header()] = "",
) -> None:
    valid = secrets.compare_digest(
        x_gateway_secret,
        get_internal_gateway_secret(),
    )

    if not valid:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Solicitud no autorizada desde Gateway",
        )


protected_by_gateway = [Depends(verify_gateway)]


# Health queda libre para comprobar si el servicio esta levantado.
@app.get("/health")
def health():
    return {"status": "OK", "service": "Backend API"}


@app.get("/products", dependencies=protected_by_gateway)
def products(
    x_authenticated_client: Annotated[str | None, Header()] = None,
):
    return {
        "authenticated_client": x_authenticated_client,
        "products": [
            {"id": 1, "name": "Notebook", "price": 900000},
            {"id": 2, "name": "Monitor", "price": 250000},
        ],
    }


@app.get("/orders", dependencies=protected_by_gateway)
def orders(
    x_authenticated_client: Annotated[str | None, Header()] = None,
):
    return {
        "authenticated_client": x_authenticated_client,
        "orders": [
            {"id": 1001, "status": "paid"},
            {"id": 1002, "status": "pending"},
        ],
    }
