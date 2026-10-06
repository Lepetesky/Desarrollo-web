import os
import secrets
import time
from typing import Annotated

from argon2 import PasswordHasher
from argon2.exceptions import VerificationError, InvalidHashError
from fastapi import Depends, FastAPI, Header, HTTPException
from pydantic import BaseModel, Field

from .users import USERS

app = FastAPI(title="Auth Service - Semana 9")
password_hasher = PasswordHasher()
SESSION_SECONDS = 900
# En este laboratorio las sesiones quedan en memoria. Al reiniciar se pierden.
SESSIONS: dict[str, dict] = {}


class LoginInput(BaseModel):
    username: str = Field(min_length=1, max_length=100)
    password: str = Field(min_length=1, max_length=1024)


class TokenInput(BaseModel):
    token: str = Field(min_length=1, max_length=512)


def verify_internal_secret(
    x_auth_secret: Annotated[str, Header()] = "",
):
    # Este secreto es entre servicios; no es la contraseña ni el token del usuario.
    configured = os.getenv("AUTH_INTROSPECTION_SECRET")
    if not configured:
        raise HTTPException(503, "AUTH_INTROSPECTION_SECRET no configurado")
    if not secrets.compare_digest(x_auth_secret.encode(), configured.encode()):
        raise HTTPException(403, "Secreto interno incorrecto")


def remove_expired_sessions():
    now = time.time()
    for token in list(SESSIONS):
        if SESSIONS[token]["expires_at"] <= now:
            SESSIONS.pop(token, None)


@app.get("/health")
def health():
    return {"status": "OK", "service": "Auth Service"}


@app.post("/login")
def login(data: LoginInput):
    user = USERS.get(data.username)
    valid = False
    if user:
        try:
            valid = password_hasher.verify(user["password_hash"], data.password)
        except (VerificationError, InvalidHashError):
            valid = False
    if not valid:
        raise HTTPException(401, "Credenciales incorrectas")

    remove_expired_sessions()
    # El token es opaco: no contiene la identidad, nosotros la guardamos aparte.
    token = secrets.token_urlsafe(32)
    SESSIONS[token] = {
        "user_id": user["user_id"],
        "username": data.username,
        "roles": list(user["roles"]),
        "expires_at": time.time() + SESSION_SECONDS,
    }
    return {
        "access_token": token,
        "token_type": "bearer",
        "expires_in": SESSION_SECONDS,
    }


@app.post("/introspect", dependencies=[Depends(verify_internal_secret)])
def introspect(data: TokenInput):
    remove_expired_sessions()
    session = SESSIONS.get(data.token)
    if session is None:
        return {"active": False}
    return {
        "active": True,
        "user_id": session["user_id"],
        "username": session["username"],
        "roles": session["roles"],
    }


@app.post("/logout", dependencies=[Depends(verify_internal_secret)])
def logout(data: TokenInput):
    # Borrar la sesión hace que ese mismo token deje de estar activo.
    SESSIONS.pop(data.token, None)
    return {"message": "Sesion cerrada"}
