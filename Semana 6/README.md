# Tokyo Noodles - Semana 6 - Parte 2

Segunda parte de la API REST con FastAPI y MongoDB. Continúa el primer PR y completa el CRUD de items con creación, actualización, eliminación y pruebas automatizadas.

## Ejecutar

1. Copiar `.env.example` como `.env` y completar la URI privada de MongoDB Atlas.
2. Iniciar la API desde esta carpeta:

```powershell
uv run --with-requirements requirements.txt uvicorn main:app --reload
```

3. Abrir `http://127.0.0.1:8000/docs`.

## Rutas finales

- `GET /health`
- `GET /items`
- `POST /items`
- `GET /items/{item_id}`
- `PUT /items/{item_id}`
- `DELETE /items/{item_id}`

## Pruebas

```powershell
uv run --with-requirements requirements-dev.txt python -m pytest
```

Las pruebas utilizan una colección en memoria y no requieren acceso a Atlas.
