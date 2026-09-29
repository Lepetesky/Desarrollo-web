import importlib.util
from pathlib import Path

import httpx
import pytest
import respx
from fastapi.testclient import TestClient


PROJECT_ROOT = Path(__file__).resolve().parents[1]
CLIENT_TOKEN = "token-de-prueba"
BACKEND_SECRET = "secreto-interno-de-prueba"


def load_module(name: str, path: Path):
    spec = importlib.util.spec_from_file_location(name, path)
    module = importlib.util.module_from_spec(spec)
    assert spec.loader is not None
    spec.loader.exec_module(module)
    return module


@pytest.fixture
def backend_module(monkeypatch):
    monkeypatch.setenv("INTERNAL_GATEWAY_SECRET", BACKEND_SECRET)
    return load_module(
        "backend_api_test",
        PROJECT_ROOT / "fastapi" / "backend_api.py",
    )


@pytest.fixture
def gateway_module(monkeypatch):
    monkeypatch.setenv("VAULT_TOKEN", "token-vault-solo-prueba")
    module = load_module(
        "gateway_test",
        PROJECT_ROOT / "gateway" / "gateway.py",
    )

    async def fake_vault_secrets():
        return {
            "client_token": CLIENT_TOKEN,
            "backend_shared_secret": BACKEND_SECRET,
        }

    monkeypatch.setattr(module, "get_gateway_secrets", fake_vault_secrets)
    return module


def test_backend_health_is_available(backend_module):
    response = TestClient(backend_module.app).get("/health")
    assert response.status_code == 200


def test_backend_rejects_direct_access(backend_module):
    response = TestClient(backend_module.app).get("/products")
    assert response.status_code == 403


def test_backend_rejects_wrong_gateway_secret(backend_module):
    response = TestClient(backend_module.app).get(
        "/products",
        headers={"X-Gateway-Secret": "incorrecto"},
    )
    assert response.status_code == 403


def test_backend_accepts_internal_gateway_secret(backend_module):
    response = TestClient(backend_module.app).get(
        "/products",
        headers={
            "X-Gateway-Secret": BACKEND_SECRET,
            "X-Authenticated-Client": "student-client",
        },
    )
    assert response.status_code == 200
    assert response.json()["authenticated_client"] == "student-client"


def test_gateway_rejects_request_without_bearer(gateway_module):
    response = TestClient(gateway_module.app).get("/api/products")
    assert response.status_code == 401
    assert response.json()["detail"] == "Bearer token requerido"


def test_gateway_rejects_wrong_bearer(gateway_module):
    response = TestClient(gateway_module.app).get(
        "/api/products",
        headers={"Authorization": "Bearer incorrecto"},
    )
    assert response.status_code == 401
    assert response.json()["detail"] == "Token invalido"


@respx.mock
def test_gateway_returns_controlled_500_when_vault_is_down(monkeypatch):
    monkeypatch.setenv("VAULT_TOKEN", "token-vault-solo-prueba")
    module = load_module(
        "gateway_vault_down_test",
        PROJECT_ROOT / "gateway" / "gateway.py",
    )
    respx.get(f"{module.VAULT_ADDR}/v1/secret/data/gateway").mock(
        side_effect=httpx.ConnectError("vault sin conexion")
    )

    response = TestClient(module.app).get(
        "/api/products",
        headers={"Authorization": f"Bearer {CLIENT_TOKEN}"},
    )

    assert response.status_code == 500
    assert response.json()["detail"] == "No fue posible acceder a Vault"


@respx.mock
def test_gateway_proxies_valid_request(gateway_module):
    backend_route = respx.get(
        f"{gateway_module.BACKEND_URL}/products",
        headers={
            "X-Gateway-Secret": BACKEND_SECRET,
            "X-Authenticated-Client": "student-client",
        },
    ).mock(
        return_value=httpx.Response(
            200,
            json={"products": [{"id": 1, "name": "Notebook"}]},
        )
    )

    response = TestClient(gateway_module.app).get(
        "/api/products",
        headers={"Authorization": f"Bearer {CLIENT_TOKEN}"},
    )

    assert response.status_code == 200
    assert backend_route.called
    assert response.json()["products"][0]["name"] == "Notebook"


@respx.mock
def test_gateway_preserves_backend_403(gateway_module):
    respx.get(f"{gateway_module.BACKEND_URL}/orders").mock(
        return_value=httpx.Response(403, json={"detail": "Forbidden"})
    )
    response = TestClient(gateway_module.app).get(
        "/api/orders",
        headers={"Authorization": f"Bearer {CLIENT_TOKEN}"},
    )
    assert response.status_code == 403


@respx.mock
def test_gateway_returns_502_when_backend_is_down(gateway_module):
    respx.get(f"{gateway_module.BACKEND_URL}/orders").mock(
        side_effect=httpx.ConnectError("backend sin conexion")
    )
    response = TestClient(gateway_module.app).get(
        "/api/orders",
        headers={"Authorization": f"Bearer {CLIENT_TOKEN}"},
    )
    assert response.status_code == 502
    assert response.json()["detail"] == "Backend no disponible"
