# Avance Unidad 2: Modelado C4 en Structurizr DSL - Tokyo Noodles

Este directorio contiene la especificación formal de arquitectura del backend de **Tokyo Noodles**, correspondiente a la Evaluación de la Unidad 2. El modelo describe el backend tal como está implementado en `tokyo-noodles-backend/` (puertos, servicios y relaciones verificados contra el código).

## 1. Niveles modelados

1. **Nivel C1 - Contexto del sistema (`C1_Contexto`)**
   - **Actores:** *Visitante* (anónimo), *Cliente* (autenticado) y *Operador / Admin*.
   - **Sistema principal:** *Plataforma Comercial Tokyo Noodles*.
   - **Dependencia externa:** *Plataforma de Pagos* (pasarela simulada `provider-mock`).

2. **Nivel C2 - Contenedores (`C2_Contenedores`)**
   - **Frontend Responsivo:** Navegador web (sus contratos vienen de la Unidad 1).
   - **API Gateway:** Único punto de entrada HTTP (puerto 8080).
   - **Auth Service:** JWT, sesiones e identidades.
   - **GraphQL Gateway:** Agregador privado GraphQL.
   - **Servicios privados:** *inventory*, *orders*, *payments*.
   - **Persistencia:** Base de datos principal (`dbMain` / MongoDB / PostgreSQL).

3. **Nivel C3 - Componentes internos**
   - **API Gateway (`C3_ApiGateway`):** HTTP Adapter, Access Filter, Route Dispatcher y Error Mapper.
   - **GraphQL Gateway (`C3_GraphQL`):** Endpoint GraphQL, Resolvers de Productos/Pedidos y Compositor de Respuestas.

## 2. Cómo visualizar los diagramas

1. Abrir [Structurizr Playground](https://structurizr.com/dsl).
2. Copiar y pegar el contenido de `workspace.dsl`.
3. Cambiar entre las vistas (`C1_Contexto`, `C2_Contenedores`, `C3_ApiGateway`, `C3_GraphQL`).

Las exportaciones PNG de cada vista están guardadas en `diagramas/`.

## 3. Archivos

- `workspace.dsl`: especificación en Structurizr DSL.
- `README.md`: especificación técnica y guía de visualización.
- `diagramas/`: exportaciones PNG de los diagramas C1, C2 y C3.
