# Semana 9: Auth Service más el avance anterior

Integrantes: Renato Gallardo y Nicolás López.

## Qué incluye esta entrega

Interpretamos «Auth Service más lo anterior» como conservar el laboratorio de
semana 8 (Gateway con Bearer fijo, backend protegido y Vault) y agregar el
**Paso 1: construir el Auth Service**, secciones 5.1 a 5.7 de la nueva guía
(páginas numeradas 6 a 8). El PDF describe un laboratorio más amplio, pero no
indica por sí solo el corte semanal; este alcance sigue la instrucción verbal
reportada del profesor.

No se modifica ni se conecta Tokyo Noodles. La carpeta se agrega al repositorio
como `Semana 9/apigateway`. Las semanas anteriores quedan intactas.

El Gateway de esta entrega conserva el funcionamiento de semana 8: **todavía
no usa los tokens emitidos por Auth**. Los tokens de Auth se prueban directamente
en el puerto 8100. El login por Gateway, las cookies HttpOnly, la propagación de
roles, el DELETE administrativo y el frontend pertenecen a pasos posteriores
del PDF y no forman parte de este corte. No se presenta este avance como el
laboratorio completo de las 18 páginas.

## Archivos

| Archivo | Función |
| --- | --- |
| `auth/auth_service.py` | Login, sesiones, introspection y logout nuevos |
| `auth/users.py` | Ana y Ernesto con hashes Argon2 y roles |
| `fastapi/backend_api.py` | Backend protegido conservado de semana 8 |
| `gateway/gateway.py` | Proxy y Bearer fijo conservados de semana 8 |
| `docker-compose.yml` | Vault de desarrollo; faltaba en el ZIP recibido |
| `configurar_vault.ps1` | Carga secretos en Vault; faltaba en el ZIP recibido |
| `probar_auth.ps1` | Prueba manual del nuevo servicio en PowerShell |
| `tests/test_auth.py` | Pruebas nuevas del Auth Service |
| `tests/test_security.py` | Pruebas originales de semana 8 |
| `README_SEMANA_8.md` | README anterior conservado como referencia |

## Instalar (PowerShell de VSCode)

Abre `Semana 9/apigateway` y ejecuta:

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

Si PowerShell bloquea la activación, usa directamente
`.\.venv\Scripts\python.exe` en lugar de `python`; no hace falta cambiar la
política del equipo.

## Ejecutar solo el Auth Service nuevo

Desde `Semana 9/apigateway`, en la primera terminal:

```powershell
$env:AUTH_INTROSPECTION_SECRET="secreto-auth-solo-laboratorio"
python -m uvicorn auth.auth_service:app --reload --host 127.0.0.1 --port 8100
```

Abre http://127.0.0.1:8100/docs para probar los endpoints. Este servicio no
requiere Docker, MongoDB ni el sitio del caso para probar este avance.

| Usuario de prueba | Contraseña de laboratorio | Roles |
| --- | --- | --- |
| ana | 1234 | user |
| ernesto | 1234 | user, admin |

Son cuentas simuladas, no credenciales reales. Se guardan únicamente sus hashes
en `USERS`. Las sesiones quedan en memoria y al reiniciar el servicio se pierden.
Usa un solo proceso de Auth en este laboratorio.

En una segunda terminal, activa el mismo entorno y ejecuta:

```powershell
$env:AUTH_INTROSPECTION_SECRET="secreto-auth-solo-laboratorio"
.\probar_auth.ps1
```

El script comprueba login, sesión activa, logout, sesión revocada y contraseña
incorrecta (401). El secreto debe coincidir en ambas terminales. Los valores
mostrados son ejemplos públicos de laboratorio; configura los tuyos localmente.

### Contrato de los endpoints

- `POST /login`: JSON `{"username":"ana","password":"1234"}`.
  Responde 200 con `access_token`, `token_type: bearer` y `expires_in: 900`.
  Credenciales incorrectas responden 401.
- `POST /introspect`: JSON `{"token":"TOKEN_DEL_LOGIN"}` y header
  `X-Auth-Secret` con el secreto local. Token vigente: `active: true`,
  `user_id`, `username` y `roles`. Expirado, revocado o inexistente:
  `{"active":false}` con estado 200.
- `POST /logout`: mismo JSON y header. Revoca la sesión en el servidor.
  Repetir logout es válido.
- `GET /health`: comprueba que Auth está levantado.

Elegimos el nombre `X-Auth-Secret` para el control interno; el PDF identifica
`auth_introspection_secret` pero no prescribe el nombre de ese header. Se
protegen introspection y logout para dejarlos preparados para el Gateway futuro.
No introduzcas este secreto en un frontend.

## Conservar y ejecutar el laboratorio anterior

Estas instrucciones usan los archivos anteriores, sin integración con Auth.
En la terminal de Vault, desde `Semana 9/apigateway`:

```powershell
$env:VAULT_ADDR="http://127.0.0.1:8200"
$env:VAULT_TOKEN="token-vault-solo-laboratorio"
$env:CLIENT_TOKEN="token-cliente-solo-laboratorio"
$env:BACKEND_SHARED_SECRET="secreto-backend-solo-laboratorio"
$env:AUTH_INTROSPECTION_SECRET="secreto-auth-solo-laboratorio"
docker compose up -d vault
.\configurar_vault.ps1
```

Espera a que Vault esté disponible antes de ejecutar el script. Si ya tienes
Vault ejecutándose con `vault.exe`, omite `docker compose up -d vault` y usa su
`VAULT_ADDR` y `VAULT_TOKEN`. El script funciona con ambas opciones. No levantes
dos instancias en el mismo puerto. Vault dev pierde sus datos al detenerse;
carga nuevamente los secretos después de reiniciarlo.

El script escribe `client_token`, `backend_shared_secret` y
`auth_introspection_secret` en `secret/gateway` (KV v2). Conserva `client_token`
porque el Gateway anterior aún lo necesita. El Auth Service recibe su secreto
por el entorno, al igual que el backend anterior; en esta etapa no consulta
Vault por sí mismo.

Terminal del backend, desde `Semana 9/apigateway`, entorno activado:

```powershell
$env:INTERNAL_GATEWAY_SECRET="secreto-backend-solo-laboratorio"
python -m uvicorn backend_api:app --app-dir fastapi --reload --host 127.0.0.1 --port 9000
```

Terminal del Gateway, desde `Semana 9/apigateway`, entorno activado:

```powershell
$env:VAULT_ADDR="http://127.0.0.1:8200"
$env:VAULT_TOKEN="token-vault-solo-laboratorio"
$env:BACKEND_URL="http://127.0.0.1:9000"
python -m uvicorn gateway:app --app-dir gateway --reload --host 127.0.0.1 --port 8000
```

Comprobaciones del avance anterior desde otra terminal:

```powershell
curl.exe -i http://127.0.0.1:9000/products
curl.exe -i http://127.0.0.1:8000/api/products
curl.exe -i -H "Authorization: Bearer token-cliente-solo-laboratorio" http://127.0.0.1:8000/api/products
```

Resultados: 403 directo al backend, 401 sin Bearer y 200 con el Bearer fijo.
El token que devuelve `/login` **aún no se utiliza en este Gateway**.

## Pruebas automatizadas

Desde `Semana 9/apigateway`:

```powershell
python -m pytest -v
```

Las pruebas no necesitan servicios encendidos. Las originales simulan Vault y
las llamadas HTTP al backend; las nuevas verifican Auth dentro de FastAPI.
La expiración se prueba adelantando el reloj, sin esperar 15 minutos.

## Qué subir

Agrega completa la carpeta `Semana 9` a tu repositorio. No reemplaces semanas
anteriores. Incluye los archivos fuente, dependencias, configuración e
instrucciones de esta carpeta. No subas `.venv`, caches, `vault.exe`, `.env`,
credenciales reales ni datos de Vault. El ZIP entregado contiene únicamente
este avance; el proyecto anual original queda sin modificaciones.

## Cómo explicarlo

Auth comprueba quién es el usuario. Si los datos son correctos, emite un token
aleatorio y guarda su identidad y vencimiento. Introspection permite consultar
si ese token continúa activo. Logout elimina la sesión del servidor. Los roles
se incluyen en la identidad, pero en este corte todavía no se autoriza una
operación administrativa con ellos. Vault guarda secretos técnicos, no las
sesiones ni las contraseñas de los usuarios.
