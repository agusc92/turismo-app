# Arquitectura del Backend de Turismo Necochea

Este documento describe la arquitectura, la estructura de directorios, las convenciones de nombres y los patrones de diseño utilizados en el backend de la aplicación de Turismo Necochea.

## 1. Visión General de la Arquitectura

El backend está construido sobre el framework Laravel, siguiendo una arquitectura MVC (Modelo-Vista-Controlador) extendida con principios de diseño que buscan la modularidad, escalabilidad y mantenibilidad.

Utilizamos Docker Compose para orquestar el entorno de desarrollo, incluyendo:
-   **Nginx**: Servidor web.
-   **PHP-FPM**: Procesador de PHP.
-   **MySQL**: Base de datos relacional.
-   **phpMyAdmin**: Interfaz gráfica para la gestión de la base de datos.

## 2. Estructura de Directorios Clave

A continuación, se detallan los directorios más relevantes y su propósito:

-   `app/`: Contiene el código fuente principal de la aplicación.
    -   `Console/Commands/`: Comandos Artisan personalizados para tareas de línea de comandos (ej. importación de datos).
    -   `Http/Controllers/`: Controladores que manejan la lógica de negocio y la interacción con las solicitudes HTTP.
    -   `Models/`: Modelos Eloquent que representan las tablas de la base de datos y encapsulan la lógica de negocio relacionada.
    -   `Http/Requests/`: (Potencialmente) Form Requests para encapsular la lógica de validación de las solicitudes HTTP.
    -   `Http/Resources/`: (Potencialmente) API Resources para transformar los modelos Eloquent en formatos JSON personalizados para la API.
    -   `Http/Middleware/`: Middleware HTTP para filtrar solicitudes (ej. autenticación, CORS).
    -   `Providers/`: Proveedores de servicios para registrar servicios, eventos y otras configuraciones.
    -   `Exceptions/`: Manejo de excepciones personalizadas.
-   `database/`: Contiene archivos relacionados con la base de datos.
    -   `migrations/`: Archivos de migración para definir la estructura de la base de datos.
    -   `factories/`: Factories para generar datos de prueba para los modelos.
    -   `seeders/`: Seeders para poblar la base de datos con datos iniciales o de prueba.
    -   `imports/`: Archivos CSV utilizados para la importación masiva de datos.
-   `routes/`: Definición de las rutas de la aplicación (web, api, console).
-   `tests/`: Contiene los tests de la aplicación.
    -   `Feature/`: Tests de característica que prueban la interacción con la API.
    -   `Unit/`: Tests unitarios que prueban unidades de código aisladas (ej. modelos, clases de lógica).
-   `config/`: Archivos de configuración de la aplicación.
-   `public/`: Archivos accesibles públicamente (ej. imágenes, assets).
-   `storage/`: Archivos generados por la aplicación (logs, caché, sesiones, etc.).
    -   `api-docs/`: Archivos de documentación de Swagger generados.
-   `vendor/`: Dependencias de Composer.
-   `docs/`: Documentación adicional del proyecto.
    -   `diagrams/`: Contiene los diagramas visuales del proyecto.

## 3. Convenciones de Nombres

Se siguen las convenciones estándar de Laravel y PHP:
-   **Clases**: `PascalCase` (ej. `AlojamientoController`, `InfoUsuario`).
-   **Métodos/Funciones**: `camelCase` (ej. `index`, `store`, `getTipoAttribute`).
-   **Variables**: `camelCase` (ej. `$alojamientoData`, `$importedCount`).
-   **Archivos**: `PascalCase` para clases (ej. `Alojamiento.php`), `snake_case` para migraciones y otros archivos no-clase.
-   **Tablas de Base de Datos**: `snake_case` y plural (ej. `alojamientos`, `gastronomicos`).
-   **Columnas de Base de Datos**: `snake_case` (ej. `redes_sociales`, `tipo_id`).
-   **Relaciones Eloquent**: `camelCase` para los métodos de relación (ej. `infoUsuario()`, `tipos()`).

## 4. Patrones de Diseño Utilizados

-   **MVC (Modelo-Vista-Controlador)**: Laravel es un framework MVC.
    -   **Modelos**: Encapsulan la lógica de negocio relacionada con los datos y la interacción con la base de datos.
    -   **Controladores**: Manejan las solicitudes HTTP, orquestan la lógica de negocio (a través de modelos o servicios) y devuelven respuestas.
-   **Factory Pattern**: Utilizado en `database/factories` para la creación de instancias de modelos para tests y seeders.
-   **Repository Pattern (Implícito)**: Aunque no hay una capa de repositorios explícita, los modelos Eloquent actúan como una forma de repositorio para la interacción con la base de datos.
-   **Dependency Injection**: Laravel hace un uso extensivo de la inyección de dependencias para la gestión de clases y servicios.

## 5. Gestión de la API

-   **API RESTful**: La API sigue los principios REST para la comunicación entre el frontend y el backend.
-   **Swagger/OpenAPI**: La documentación de la API se genera automáticamente a partir de anotaciones en los modelos y controladores, proporcionando una interfaz interactiva para explorar y probar los endpoints.

## 6. Diagramas Visuales

Los diagramas visuales son una parte fundamental de la documentación de la arquitectura del proyecto. Se encuentran en la carpeta `docs/diagrams/`.

**Importancia:**
-   **Claridad**: Proporcionan una visión rápida y comprensible de estructuras complejas (ej. relaciones de base de datos, flujo de componentes).
-   **Mantenimiento**: Es crucial que estos diagramas se mantengan actualizados con cualquier cambio relevante en el proyecto. Si se modifica la estructura de la base de datos, la interacción entre componentes o los flujos de negocio, los diagramas deben reflejar esos cambios.

**Tipos de Diagramas Incluidos (o a incluir):**
-   **Diagrama Entidad-Relación (ERD)**: Muestra las tablas de la base de datos y sus relaciones.
-   **Diagrama de Arquitectura/Componentes**: Ilustra la interconexión de los servicios del backend.
-   **Diagrama de clases**: Muestra la conexión entre todas las tablas del modelo

---
