import sys
from pathlib import Path

import pytest
from fastapi.testclient import TestClient

sys.path.insert(0, str(Path(__file__).resolve().parents[1]))
from auth import auth_service as auth


@pytest.fixture
def client(monkeypatch):
    monkeypatch.setenv("AUTH_INTROSPECTION_SECRET", "secreto-solo-prueba")
    auth.SESSIONS.clear()
    with TestClient(auth.app) as client:
        yield client
    auth.SESSIONS.clear()


HEADERS = {"X-Auth-Secret": "secreto-solo-prueba"}


def login(client, username="ana"):
    return client.post("/login", json={"username": username, "password": "1234"})


@pytest.mark.parametrize("username,user_id,roles", [
    ("ana", "USR-001", ["user"]),
    ("ernesto", "USR-003", ["user", "admin"]),
])
def test_login_and_identity(client, username, user_id, roles):
    response = login(client, username)
    assert response.status_code == 200
    session = response.json()
    assert session["token_type"] == "bearer"
    assert session["expires_in"] == 900
    result = client.post("/introspect", headers=HEADERS, json={"token": session["access_token"]})
    assert result.status_code == 200
    assert result.json() == {"active": True, "user_id": user_id, "username": username, "roles": roles}


@pytest.mark.parametrize("username,password", [("ana", "incorrecta"), ("desconocido", "1234")])
def test_bad_credentials_do_not_create_session(client, username, password):
    response = client.post("/login", json={"username": username, "password": password})
    assert response.status_code == 401
    assert not auth.SESSIONS


def test_unknown_token(client):
    response = client.post("/introspect", headers=HEADERS, json={"token": "inexistente"})
    assert response.status_code == 200
    assert response.json() == {"active": False}


def test_expiration(client, monkeypatch):
    monkeypatch.setattr(auth.time, "time", lambda: 1000)
    token = login(client).json()["access_token"]
    monkeypatch.setattr(auth.time, "time", lambda: 1900)
    response = client.post("/introspect", headers=HEADERS, json={"token": token})
    assert response.json() == {"active": False}
    assert token not in auth.SESSIONS


def test_logout_revokes_session(client):
    token = login(client).json()["access_token"]
    assert client.post("/logout", headers=HEADERS, json={"token": token}).status_code == 200
    assert client.post("/introspect", headers=HEADERS, json={"token": token}).json() == {"active": False}
    assert client.post("/logout", headers=HEADERS, json={"token": token}).status_code == 200


@pytest.mark.parametrize("path", ["/introspect", "/logout"])
@pytest.mark.parametrize("headers", [{}, {"X-Auth-Secret": "incorrecto"}])
def test_internal_endpoints_require_secret(client, path, headers):
    token = login(client).json()["access_token"]
    assert client.post(path, headers=headers, json={"token": token}).status_code == 403
    assert token in auth.SESSIONS


def test_missing_configuration(client, monkeypatch):
    monkeypatch.delenv("AUTH_INTROSPECTION_SECRET")
    response = client.post("/introspect", headers=HEADERS, json={"token": "prueba"})
    assert response.status_code == 503


def test_tokens_differ(client):
    assert login(client).json()["access_token"] != login(client).json()["access_token"]


def test_passwords_are_hashes():
    for user in auth.USERS.values():
        assert user["password_hash"].startswith("$argon2id$")
        assert auth.password_hasher.verify(user["password_hash"], "1234")
