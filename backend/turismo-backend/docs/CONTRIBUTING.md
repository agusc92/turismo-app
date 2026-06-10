# Guía de Contribución para el Backend de Turismo Necochea

Este documento te guiará a través del proceso para realizar tus aportes de manera efectiva y mantener la coherencia del proyecto.

## 1. Flujo de Trabajo de Git

Utilizamos un flujo de trabajo basado en ramas de característica (feature branches) que se integran en una rama de desarrollo principal (`backend`) antes de ser fusionadas en `main`.

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

5.  **Realiza commits atómicos**: Intenta que cada commit resuelva una única preocupación o implemente una parte lógica de la funcionalidad.

6.  **Actualiza tu rama con los últimos cambios de la rama base**: Antes de crear un Pull Request, asegúrate de que tu rama esté al día con `main`.
    ```bash
    git checkout <tu-rama>
    git pull origin <rama-base> # ej. git pull origin main
    # Resuelve cualquier conflicto que pueda surgir.
    ```

7.  **Sube tus cambios a tu rama remota**:
    ```bash
    git push origin feature/nombre-de-tu-caracteristica
    ```

8.  **Crea un Pull Request (PR)**:
    *   Dirige tu PR desde tu rama de característica hacia la rama principal.
    *   Asegúrate de que todos los tests pasen antes de crear el PR.

## 2. Convenciones de Commits

Utilizamos un formato de mensaje de commit que sigue la convención [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/). Esto ayuda a generar un historial de cambios legible y facilita la automatización de tareas (ej. generación de changelogs).

### Formato Básico:

```
<tipo>(<ámbito>): <descripción corta>

[cuerpo opcional]

[pie de página opcional]
```

### Ejemplos:

-   `feat(alojamiento): Add image, latitude, and longitude fields`
-   `fix(auth): Correct password hashing on user registration`
-   `test(gastronomico): Add unit tests for casts and accessors`
-   `docs(readme): Update testing section with unit test details`

### Tipos Comunes:

-   **`feat`**: Una nueva característica.
-   **`fix`**: Una corrección de un error.
-   **`docs`**: Cambios en la documentación.
-   **`style`**: Cambios que no afectan el significado del código (espacios en blanco, formato, puntos y comas faltantes, etc.).
-   **`refactor`**: Un cambio de código que no corrige un error ni añade una característica.
-   **`perf`**: Un cambio de código que mejora el rendimiento.
-   **`test`**: Añadiendo tests faltantes o corrigiendo tests existentes.
-   **`chore`**: Cambios en el proceso de construcción o herramientas auxiliares y librerías (ej. actualización de dependencias).
-   **`build`**: Cambios que afectan el sistema de construcción o dependencias externas (ej. npm, composer).
-   **`ci`**: Cambios en nuestros archivos y scripts de configuración de CI.

## 3. Ejecución de Tests

Antes de enviar un Pull Request, es **obligatorio** que todos los tests pasen.

1.  **Asegúrate de que tus servicios Docker estén corriendo**:
    ```bash
    docker-compose up -d
    ```

2.  **Ejecuta la suite completa de tests**:
    ```bash
    docker-compose exec php php artisan test
    ```
    Si algún test falla, corrígelo y vuelve a ejecutar los tests hasta que todos pasen.

## 4. Estilo de Código

Sigue el estilo de código existente en el proyecto. Utiliza herramientas de formato automático si están configuradas (ej. PHP-CS-Fixer, Laravel Pint).

## 5. Documentación de la API (Swagger)

Si tus cambios afectan la API (nuevos endpoints, cambios en modelos, validaciones), asegúrate de:

1.  **Actualizar las anotaciones de Swagger** (`#[OA\...]`) en los controladores y modelos afectados.
2.  **Regenerar la documentación de Swagger**:
    ```bash
    docker-compose exec php php artisan config:clear
    docker-compose exec php php artisan l5-swagger:generate
    ```

## 6. Mantenimiento de Diagramas

Los diagramas visuales (ej. ERD, Arquitectura de Componentes) son una parte importante de la documentación y se encuentran en `docs/diagrams/`.

**Es fundamental mantener estos diagramas actualizados.** Si realizas cambios en la estructura de la base de datos (migraciones), en las relaciones entre modelos, o en la arquitectura de los componentes del sistema, debes actualizar los diagramas correspondientes.


---
