# Validación del avance

- Python 3.12, dependencias instaladas desde requirements.txt.
- `python -m pytest -q`: **24 passed** (10 anteriores, 14 nuevas).
- Prueba HTTP real con Uvicorn: login 200, introspection activa,
  logout 200, introspection posterior inactiva y login incorrecto 401.
- Backend, Gateway y test_security.py conservados byte a byte desde semana 8.
- Docker/Vault y los scripts PowerShell no se ejecutaron en este entorno:
  Docker y PowerShell no están instalados. Las pruebas anteriores simulan
  Vault y el backend; el README incluye verificación manual para Windows.
- Una advertencia de deprecación de Starlette/TestClient no impidió las pruebas.


