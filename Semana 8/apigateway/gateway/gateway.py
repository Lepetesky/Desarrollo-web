import os
import secrets

import httpx
from fastapi import Depends, FastAPI, HTTPException, Request, Response, status
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer


app = FastAPI(
    title="Secure Local API Gateway",
    description="API Gateway con HashiCorp Vault y autenticacion Bearer",
)

security = HTTPBearer(auto_error=False)

VAULT_ADDR = os.getenv("VAULT_ADDR", "http://127.0.0.1:8200").rstrip("/")
BACKEND_URL = os.getenv("BACKEND_URL", "http://127.0.0.1:9000").rstrip("/")
BACKEND_TIMEOUT = float(os.getenv("BACKEND_TIMEOUT", "10.0"))


# El token de acceso a Vault se configura solo en el entorno del HOST A.
# Los secretos del cliente y del backend nunca se escriben en este archivo.
def get_vault_token() -> str:
    vault_token = os.getenv("VAULT_TOKEN")
    if not vault_token:
        raise RuntimeError("VAULT_TOKEN no configurado")
    return vault_token


# Vault KV v2 responde con data.data. Consultarlo en cada autenticacion permite
# que una rotacion de token se aplique sin reiniciar ni recompilar el Gateway.
async def get_gateway_secrets() -> dict[str, str]:
    try:
        async with httpx.AsyncClient(timeout=5.0, trust_env=False) as client:
            response = await client.get(
                f"{VAULT_ADDR}/v1/secret/data/gateway",
                headers={"X-Vault-Token": get_vault_token()},
            )
    except httpx.RequestError as exc:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="No fue posible acceder a Vault",
        ) from exc

    if response.status_code != status.HTTP_200_OK:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="No fue posible acceder a Vault",
        )

    try:
        vault_data = response.json()["data"]["data"]
        client_token = str(vault_data["client_token"])
        backend_secret = str(vault_data["backend_shared_secret"])
    except (KeyError, TypeError, ValueError) as exc:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="Vault no contiene los secretos requeridos",
        ) from exc

    return {
        "client_token": client_token,
        "backend_shared_secret": backend_secret,
    }


# HTTPBearer extrae Authorization: Bearer <token>. El error 401 corresponde a
# un cliente no autenticado o a una credencial incorrecta.
async def authenticate_client(
    credentials: HTTPAuthorizationCredentials | None = Depends(security),
) -> dict[str, str]:
    if credentials is None:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Bearer token requerido",
            headers={"WWW-Authenticate": "Bearer"},
        )

    vault_secrets = await get_gateway_secrets()
    valid = secrets.compare_digest(
        credentials.credentials,
        vault_secrets["client_token"],
    )

    if not valid:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Token invalido",
            headers={"WWW-Authenticate": "Bearer"},
        )

    return {
        "client_id": "student-client",
        "backend_secret": vault_secrets["backend_shared_secret"],
    }


@app.get("/health")
def health():
    return {"status": "OK", "service": "API Gateway"}


# Esta ruta funciona como proxy para los endpoints actuales y para futuros
# recursos. Conserva metodo, query string, cuerpo, tipo de contenido y estado.
@app.api_route(
    "/api/{path:path}",
    methods=["GET", "POST", "PUT", "PATCH", "DELETE"],
)
async def proxy(
    path: str,
    request: Request,
    auth: dict[str, str] = Depends(authenticate_client),
):
    target_url = f"{BACKEND_URL}/{path}"
    body = await request.body()
    gateway_headers = {
        "X-Gateway-Secret": auth["backend_secret"],
        "X-Authenticated-Client": auth["client_id"],
    }

    content_type = request.headers.get("content-type")
    if content_type:
        gateway_headers["content-type"] = content_type

    try:
        async with httpx.AsyncClient(
            timeout=BACKEND_TIMEOUT,
            trust_env=False,
        ) as client:
            upstream = await client.request(
                method=request.method,
                url=target_url,
                params=request.query_params,
                content=body,
                headers=gateway_headers,
            )
    except httpx.RequestError as exc:
        raise HTTPException(
            status_code=status.HTTP_502_BAD_GATEWAY,
            detail="Backend no disponible",
        ) from exc

    response_headers = {}
    if "content-type" in upstream.headers:
        response_headers["content-type"] = upstream.headers["content-type"]

    return Response(
        content=upstream.content,
        status_code=upstream.status_code,
        headers=response_headers,
    )

