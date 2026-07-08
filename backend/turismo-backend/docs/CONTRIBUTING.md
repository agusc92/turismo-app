# Guía de Contribución para el Backend de Turismo Necochea

Este documento te guiará a través del proceso para realizar tus aportes de manera efectiva y mantener la coherencia del proyecto.

## 1. Flujo de Trabajo de Git

Utilizamos un flujo de trabajo basado en ramas de característica.

### Pasos Generales:

1.  **Clona el repositorio**:
    ```bash
    git clone https://github.com/agusc92/turismo-app.git
    cd turismo-app/backend/turismo-backend
    ```

2.  **Asegúrate de estar en la rama `main` y actualízala**:
    ```bash
    git checkout main
    git pull origin main
    ```

3.  **Crea una nueva rama de característica**:
    ```bash
    git checkout -b feature/nombre-de-tu-caracteristica main
    ```
    *   **Nombres de rama recomendados**: Utiliza `feature/`, `bugfix/`, `hotfix/`, `chore/`, `refactor/` como prefijo, seguido de una descripción concisa en `kebab-case`.

4.  **Realiza tus cambios**: Implementa las funcionalidades, corrige errores, añade tests, etc.

3.  **Realiza tus cambios**: Implementa funcionalidades, corrige errores, etc.

4.  **Realiza commits**: Intenta que cada commit sea descriptivo de los cambios realizados.

5.  **Sube tus cambios**:
    ```bash
    git push origin feature/nombre-de-tu-caracteristica
    ```

6.  **Crea un Pull Request (PR)**:
    *   Dirige tu PR desde tu rama de característica hacia la rama `main`.
    *   Asegúrate de que los tests pasen antes de crear el PR.

## 2. Convenciones de Commits (Recomendado)

Se recomienda seguir la convención [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) para mantener un historial de cambios legible.

### Ejemplos de Tipos:

-   `feat`: Nueva característica.
-   `fix`: Corrección de un error.
-   `docs`: Cambios en la documentación.
-   `refactor`: Cambios de código que no corrigen errores ni añaden características.
-   `test`: Añadiendo o corrigiendo tests.
-   `chore`: Cambios en el proceso de construcción o herramientas.

## 3. Ejecución de Tests

Antes de enviar un Pull Request, es importante que los tests pasen.

1.  **Asegúrate de que tus servicios Docker estén corriendo**:
    ```bash
    docker-compose up -d
    ```

2.  **Ejecuta la suite completa de tests**:
    ```bash
    docker-compose exec php php artisan test
    ```

## 4. Estilo de Código

Sigue el estilo de código existente en el proyecto.

## 5. Documentación de la API (Swagger)

Si tus cambios afectan la API, recuerda actualizar las anotaciones de Swagger y regenerar la documentación:

```bash
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan l5-swagger:generate
```

## 6. Mantenimiento de Diagramas

Si realizas cambios en la estructura de la base de datos o la arquitectura, actualiza los diagramas en `docs/diagrams/`.

---
