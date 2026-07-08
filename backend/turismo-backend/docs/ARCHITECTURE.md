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

## 7. Variables de Entorno Clave

Las variables de entorno son cruciales para configurar la aplicación en diferentes entornos (desarrollo, producción, testing). Se definen en el archivo `.env` (basado en `.env.example`) y son accedidas a través de la función `env()`. A continuación, se listan las más relevantes:

*   **`APP_NAME`**: Nombre de la aplicación. Usado en notificaciones y logs.
    *   `Por defecto`: `Laravel`
*   **`APP_ENV`**: Entorno actual de la aplicación (ej. `local`, `production`, `testing`). Afecta la configuración de servicios y el manejo de errores.
    *   `Por defecto`: `local`
*   **`APP_KEY`**: Clave de aplicación única para cifrado. **Debe ser generada y mantenerse secreta.**
    *   `Ejemplo`: `base64:ffkz1Jg18W0qBkxaBW+Y/dfl2cceRIP2tb5YEAnuroQ=`
*   **`APP_DEBUG`**: Habilita o deshabilita el modo de depuración. `true` en desarrollo para ver errores detallados, `false` en producción por seguridad.
    *   `Por defecto`: `true`
*   **`APP_URL`**: URL base de la aplicación. Importante para la generación de URLs en la consola y en la API.
    *   `Por defecto`: `http://localhost`
*   **`DB_CONNECTION`**: Tipo de conexión a la base de datos.
    *   `Por defecto`: `mysql`
*   **`DB_HOST`**: Host de la base de datos.
    *   `Por defecto`: `mysql` (nombre del servicio Docker)
*   **`DB_PORT`**: Puerto de la base de datos.
    *   `Por defecto`: `3306`
*   **`DB_DATABASE`**: Nombre de la base de datos.
    *   `Por defecto`: `turismo_db`
*   **`DB_USERNAME`**: Usuario de la base de datos.
    *   `Por defecto`: `turismo_user`
*   **`DB_PASSWORD`**: Contraseña del usuario de la base de datos.
    *   `Por defecto`: `turismo_pass`
*   **`SANCTUM_STATEFUL_DOMAINS`**: Dominios desde los cuales se aceptarán cookies de autenticación stateful para Laravel Sanctum (útil para SPAs).
    *   `Ejemplo`: `localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1`
*   **`MAIL_MAILER`**: Driver de correo electrónico (ej. `smtp`, `sendmail`, `log`, `array`).
    *   `Por defecto`: `log`
*   **`MAIL_HOST`**: Host del servidor SMTP.
    *   `Por defecto`: `127.0.0.1`
*   **`MAIL_PORT`**: Puerto del servidor SMTP.
    *   `Por defecto`: `2525`
*   **`MAIL_USERNAME`**: Usuario para autenticación SMTP.
*   **`MAIL_PASSWORD`**: Contraseña para autenticación SMTP.
*   **`MAIL_FROM_ADDRESS`**: Dirección de correo electrónico "De" por defecto.
*   **`AWS_ACCESS_KEY_ID`**: Clave de acceso de AWS (si se utilizan servicios S3, etc.).
*   **`AWS_SECRET_ACCESS_KEY`**: Clave secreta de AWS.
*   **`AWS_DEFAULT_REGION`**: Región por defecto de AWS.
*   **`AWS_BUCKET`**: Nombre del bucket S3 de AWS.
*   **`BCRYPT_ROUNDS`**: Número de rondas para el algoritmo de hashing Bcrypt. Un valor más alto aumenta la seguridad pero también el tiempo de procesamiento.
    *   `Por defecto`: `12`
*   **`LOG_CHANNEL`**: Canal de log por defecto.
    *   `Por defecto`: `stack`

## 8. Consideraciones de Seguridad

La seguridad es un pilar fundamental en el desarrollo del backend. Se han implementado diversas medidas y se siguen las mejores prácticas de Laravel para proteger la aplicación y los datos de los usuarios.

### Autenticación y Autorización
-   **Laravel Sanctum**: Utilizado para la autenticación de la API. Permite la emisión de tokens API para la autenticación de solicitudes y también soporta la autenticación basada en cookies/sesiones para Single Page Applications (SPAs) a través de `SANCTUM_STATEFUL_DOMAINS`.
-   **Hashing de Contraseñas**: Todas las contraseñas de usuario se almacenan de forma segura utilizando el algoritmo Bcrypt, con un número de rondas configurable (`BCRYPT_ROUNDS`) para aumentar la resistencia a ataques de fuerza bruta.
-   **Middleware de Autorización**: Se utiliza middleware personalizado, como `AdminMiddleware`, para restringir el acceso a ciertas rutas o funcionalidades solo a usuarios con roles específicos (ej. administradores).

### Validación de Entrada
-   **Form Requests**: Se recomienda encarecidamente el uso de Form Requests para encapsular y centralizar la lógica de validación de las solicitudes HTTP. Esto asegura que solo los datos válidos y esperados lleguen a la lógica de negocio de los controladores.

### Protección contra Vulnerabilidades Comunes
Laravel, por diseño, proporciona protección contra muchas vulnerabilidades web comunes:
-   **Inyección SQL**: Eloquent ORM y las consultas de base de datos de Laravel utilizan PDO binding para proteger contra la inyección SQL.
-   **Cross-Site Scripting (XSS)**: Laravel escapa automáticamente el contenido en las vistas Blade, y se debe asegurar que cualquier salida de datos en la API también se maneje de forma segura.
-   **Cross-Site Request Forgery (CSRF)**: Aunque principalmente relevante para aplicaciones web basadas en sesión, Laravel incluye protección CSRF. Para APIs puras con tokens, esto es menos crítico, pero `SANCTUM_STATEFUL_DOMAINS` lo considera para SPAs.

## 9. Logging y Manejo de Errores

Una gestión robusta de logs y errores es vital para la depuración, el monitoreo y la estabilidad de la aplicación.

### Logging
-   **Configuración**: El sistema de logging de Laravel se configura a través de `config/logging.php`. Por defecto, utiliza un canal `stack` que puede enviar logs a múltiples destinos (ej. `single` para un archivo, `daily` para archivos diarios, `syslog`, `slack`).
-   **Canales**: El canal por defecto se define con la variable de entorno `LOG_CHANNEL`. En desarrollo, `single` o `daily` son comunes.
-   **Nivel de Log**: La variable `LOG_LEVEL` en `.env` controla la verbosidad de los logs (ej. `debug`, `info`, `warning`, `error`).

### Manejo de Errores
-   **Excepciones**: Laravel centraliza el manejo de excepciones en `app/Exceptions/Handler.php`. Aquí se pueden personalizar cómo se reportan y renderizan las diferentes excepciones.
-   **Modo Debug**: La variable `APP_DEBUG` en `.env` controla si los errores se muestran con detalles completos (útil en desarrollo) o como una página de error genérica (esencial en producción para evitar la exposición de información sensible).

## 10. Estrategia de Despliegue (Alto Nivel)

El proyecto está diseñado para ser desplegado utilizando contenedores Docker, lo que facilita la consistencia entre entornos de desarrollo y producción.

-   **Contenedorización**: El uso de `docker-compose.yml` define los servicios necesarios (Nginx, PHP-FPM, MySQL, phpMyAdmin), permitiendo un despliegue portable y escalable.
-   **CI/CD (Consideraciones)**: Aunque no se define un pipeline de CI/CD explícito en la documentación actual, la naturaleza contenedorizada del proyecto facilita su integración con herramientas de Integración Continua/Despliegue Continuo para automatizar pruebas, construcción de imágenes y despliegues.
-   **Escalabilidad**: La arquitectura basada en microservicios (aunque aquí es un monolito dentro de Docker) y la contenedorización permiten escalar servicios individualmente si fuera necesario en un entorno de producción.

## 11. Middleware Personalizado

El proyecto utiliza middleware para interceptar y procesar solicitudes HTTP. Además de los middleware estándar de Laravel, se han implementado algunos personalizados para lógica de negocio específica.

-   **`AdminMiddleware`**: Ubicado en `app/Http/Middleware/AdminMiddleware.php`. Este middleware se encarga de verificar si el usuario autenticado tiene el rol de "administrador" antes de permitir el acceso a ciertas rutas o grupos de rutas. Si el usuario no es administrador, se deniega el acceso, típicamente con una respuesta de error 403 (Forbidden).

---
