# API Gateway seguro — continuación desde la página 7

Este directorio contiene solamente el avance de `apigateway`. Continúa el
Gateway básico de la página 6 e implementa los pasos 4 al 13 de la guía.

## Funcionalidades implementadas

- Vault en modo de desarrollo mediante Docker.
- Dos identidades separadas en `secret/gateway`:
  `client_token` y `backend_shared_secret`.
- Autenticación `Authorization: Bearer <token>` en el Gateway.
- Comparación de credenciales con `secrets.compare_digest`.
- Protección del backend mediante `X-Gateway-Secret`.
- Proxy genérico con `httpx.AsyncClient`.
- Códigos controlados `401`, `403`, `500` y `502`.
- Rotación de token sin modificar el código.
- Pruebas automatizadas del flujo de seguridad.

Los valores reales se configuran en variables de entorno y en Vault. `.env`,
`.vault-token`, `.venv` y cachés están excluidos mediante `.gitignore`.

## 1. Instalar dependencias

Desde `Semana 8/apigateway` en PowerShell:

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

## 2. Configurar variables locales

Para reproducir exactamente los ejemplos académicos de la guía se pueden usar
sus credenciales de laboratorio. Para una entrega distinta, reemplazarlas por
otros valores locales y no escribirlos en archivos versionados.

```powershell
$env:VAULT_TOKEN="dev-only-token"
$env:CLIENT_TOKEN="student-token-123"
$env:BACKEND_SHARED_SECRET="gateway-api-secret-456"
$env:VAULT_ADDR="http://127.0.0.1:8200"
$env:BACKEND_URL="http://127.0.0.1:9000"
$env:INTERNAL_GATEWAY_SECRET=$env:BACKEND_SHARED_SECRET
```

## 3. Levantar Vault y guardar secretos

Con Docker Desktop iniciado:

```powershell
docker compose up -d vault
.\configurar_vault.ps1
```

La ruta resultante es `secret/gateway`. Para revisar sólo la metadata sin
imprimir los valores:

```powershell
docker compose exec -e VAULT_TOKEN=$env:VAULT_TOKEN vault `
  vault kv metadata get secret/gateway
```

## 4. Ejecutar los dos servicios

Terminal 1, desde `apigateway/fastapi`:

```powershell
$env:INTERNAL_GATEWAY_SECRET="gateway-api-secret-456"
python -m uvicorn backend_api:app --reload --host 0.0.0.0 --port 9000
```

Terminal 2, desde `apigateway/gateway`:

```powershell
$env:VAULT_ADDR="http://127.0.0.1:8200"
$env:VAULT_TOKEN="dev-only-token"
$env:BACKEND_URL="http://127.0.0.1:9000"
python -m uvicorn gateway:app --reload --host 0.0.0.0 --port 8000
```

En dos computadores, se reemplaza `127.0.0.1` por la IP real del HOST B en
`BACKEND_URL`. El backend usa el puerto 9000 y el Gateway el 8000.

## 5. Evidencias manuales

### Backend directo sin secreto: 403

```powershell
curl.exe -i http://127.0.0.1:9000/products
```

### Gateway sin Bearer: 401

```powershell
curl.exe -i http://127.0.0.1:8000/api/products
```

### Gateway con Bearer incorrecto: 401

```powershell
curl.exe -i -H "Authorization: Bearer incorrecto" `
  http://127.0.0.1:8000/api/products
```

### Gateway con Bearer válido: 200

```powershell
curl.exe -i -H "Authorization: Bearer student-token-123" `
  http://127.0.0.1:8000/api/products
```

### Backend detenido: 502

Detener la Terminal 1 y repetir la solicitud válida al Gateway.

## 6. Rotar el token

```powershell
$env:CLIENT_TOKEN="nuevo-token-789"
.\configurar_vault.ps1
```

El token anterior pasa a responder 401 y el nuevo responde 200. No se modifica
ni recompila `gateway.py` porque el Gateway consulta Vault en cada solicitud.

## 7. Ejecutar pruebas automatizadas

Desde `Semana 8/apigateway`:

```powershell
python -m pytest -v
```

Las pruebas usan credenciales ficticias aisladas de los secretos locales.

## Matriz de cumplimiento

| Prueba | Resultado esperado |
| --- | --- |
| Gateway sin token | 401 |
| Gateway con token falso | 401 |
| Gateway con token válido | 200 |
| Backend directo sin secreto | 403 |
| Vault inaccesible | 500 controlado |
| Backend inaccesible | 502 |
| Token rotado | token anterior 401 / nuevo 200 |
| Secreto interno incorrecto | 403 |

