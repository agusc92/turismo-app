<p align="center">
  <img src="public/dark.png#gh-dark-mode-only" width="400">
  <img src="public/light.png#gh-light-mode-only" width="400">
</p>

[![Laravel](https://img.shields.io/badge/Laravel-^10.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-20.10+-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Tests](https://img.shields.io/badge/Tests-PHPUnit-6C5293?style=for-the-badge&logo=phpunit&logoColor=white)](https://phpunit.de/)
[![Swagger](https://img.shields.io/badge/API%20Docs-Swagger-85EA2D?style=for-the-badge&logo=swagger&logoColor=white)](http://localhost:8000/api/documentation)

# Backend de Descubrí Necochea

Este repositorio contiene el código fuente del backend de la aplicación de Turismo Necochea ***"Descubrí Necochea"*** , desarrollado con Laravel. La API RESTful gestiona la información sobre alojamientos, gastronomía, eventos, actividades, balnearios, complejos y usuarios.

##  Puesta en Marcha

Para levantar el entorno de desarrollo completo (servidor web Nginx, PHP-FPM, base de datos MySQL y phpMyAdmin) utilizando Docker Compose, sigue estos pasos:

1.  **Navega a la raíz del proyecto backend**:
    ```bash
    cd turismo-app/backend/turismo-backend
    ```

2.  **Copia el archivo de entorno**:
    Crea tu archivo `.env` a partir del ejemplo. Este archivo contendrá las configuraciones específicas de tu entorno (ej. credenciales de base de datos, `APP_URL`).
    ```bash
    cp .env.example .env
    ```
    **Importante**: Edita el archivo `.env` y asegúrate de que `APP_URL` esté configurado correctamente, por ejemplo:
    ```
    APP_URL=http://localhost:8000
    ```

3.  **Levanta los servicios de Docker Compose**:
    ```bash
    docker-compose up --build -d
    ```
    **Nota sobre `docker-compose up`**:
    *   `docker-compose up --build -d`: Este comando construye las imágenes de Docker desde cero (si hay cambios en los `Dockerfile` o si es la primera vez que se ejecuta) y luego levanta los contenedores en segundo plano. Es crucial usar `--build` la primera vez o cuando se han realizado cambios en la configuración de los servicios (ej. `Dockerfile`, `docker-compose.yml`).
    *   `docker-compose up -d`: Este comando levanta los contenedores en segundo plano utilizando las imágenes existentes. Si no hay imágenes construidas previamente o si las imágenes existentes están desactualizadas, puede que no funcione como se espera o use versiones antiguas.

4.  **Instala las dependencias de Composer (¡Importante!):**
    *   **Si es la primera vez o si has borrado la carpeta `vendor/`:** Debes ejecutar `composer install` directamente, ya que `php artisan` no puede funcionar sin las dependencias.
        ```bash
        docker-compose exec php composer install
        ```
    *   Si la carpeta `vendor/` ya existe, el comando `setup:dev` (paso 6) se encargará de verificar y actualizar las dependencias si es necesario.
        **Nota**: Si se han añadido o modificado dependencias en `composer.json` (ej. después de un `git pull`), es recomendable ejecutar `docker-compose exec php composer install` nuevamente para asegurar que todas las dependencias estén actualizadas.

5.  **Genera la clave de la aplicación Laravel**:
    ```bash
    docker-compose exec php php artisan key:generate
    ```

6.  ## Configuración de la Base de Datos y Setup del Entorno

    Hemos creado comandos Artisan para automatizar el setup de la base de datos, importación de datos y generación de documentación. Aquí se detallan los comandos generales y específicos para cada tarea.

    ### Comandos de Setup Integrados (Recomendado para Setup Rápido)

    *   **`php artisan setup:dev`**:
        Este comando es ideal para desarrolladores frontend o para un setup rápido. Realiza las siguientes acciones:
        *   Instala/actualiza las dependencias de Composer.
        *   Verifica que el autoloader de Composer funcione.
        *   Limpia y ejecuta todas las migraciones de la base de datos (`migrate:fresh`).
        *   Importa todos los datos iniciales desde los archivos CSV (`import:all`).
        *   Genera la documentación de la API (Swagger/OpenAPI).
        ```bash
        docker-compose exec php php artisan setup:dev
        ```

    *   **`php artisan setup:full-dev`**:
        Este comando hace todo lo que hace `setup:dev` y, además, ejecuta todos los tests del proyecto. Es útil para desarrolladores backend o para verificar la integridad completa del proyecto.
        ```bash
        docker-compose exec php php artisan setup:full-dev
        ```

    ### Comandos Individuales para usar durante el desarrollo

    Para un control más granular, los desarrolladores pueden ejecutar los siguientes comandos de forma individual:

    *   **Ejecutar Migraciones de Base de Datos**:
        Para crear las tablas de la base de datos.
        ```bash
        docker-compose exec php php artisan migrate
        ```

    *   **Reiniciar Migraciones y Sembrar la Base de Datos (Fresh Migrate & Seed)**:
        Elimina todas las tablas, vuelve a ejecutar las migraciones y luego ejecuta los seeders para poblar la base de datos con datos de prueba.
        ```bash
        docker-compose exec php php artisan migrate:fresh --seed
        ```

    *   **Ejecutar Seeders (Poblar Base de Datos)**:
        Para poblar la base de datos con datos de prueba sin reiniciar las migraciones.
        ```bash
        docker-compose exec php php artisan db:seed
        ```

    *   **Importar Datos Masivos desde CSV**:
        Para importar datos iniciales desde los archivos CSV.
        ```bash
        docker-compose exec php php artisan import:all
        ```
        (Ver sección "Importación Masiva de Datos (CSV)" para comandos individuales de importación).

    *   **Generar Documentación de la API**:
        Para regenerar la documentación de la API (Swagger/OpenAPI).
        ```bash
        docker-compose exec php php artisan l5-swagger:generate
        ```
        (Ver sección "Documentación de la API (Swagger UI)" para más detalles).

    *   **Ejecutar Tests**:
        Para ejecutar todos los tests del proyecto.
        ```bash
        docker-compose exec php php artisan test
        ```
        (Ver sección "Testing" para más opciones de ejecución de tests).

## Acceder a la Aplicación

Una vez completado el setup, el backend debería estar accesible en `http://localhost:8000` (o el puerto que hayas configurado).

## Endpoints de la API

Aquí se listan todos los endpoints principales de la API, organizados por recurso:

*   **Autenticación**:
    *   `POST /api/register` - Registro de un nuevo usuario.
    *   `POST /api/login` - Inicio de sesión de usuario.
    *   `POST /api/logout` - Cierre de sesión de usuario.
    *   `GET /api/user` - Obtener información del usuario autenticado.

*   **Usuarios**: Gestión de usuarios y sus perfiles (`InfoUsuario`).
    *   `GET /api/users` - Listar todos los usuarios.
    *   `GET /api/users/{id}` - Obtener un usuario específico.
    *   `PUT /api/users/{id}` - Actualizar un usuario.
    *   `DELETE /api/users/{id}` - Eliminar un usuario.

*   **Alojamientos**: Gestión de alojamientos, que incluyen una relación muchos a muchos con `TipoAlojamiento` a través de la tabla pivote `alojamiento_tipo_alojamiento`.
    *   `GET /api/alojamientos` - Listar todos los alojamientos.
    *   `GET /api/alojamientos/{id}` - Obtener un alojamiento específico.
    *   `POST /api/alojamientos` - Crear un nuevo alojamiento (Requiere `tipos_alojamiento_ids` en el body).
    *   `PUT /api/alojamientos/{id}` - Actualizar un alojamiento (Puede actualizar `tipos_alojamiento_ids`).
    *   `DELETE /api/alojamientos/{id}` - Eliminar un alojamiento.

*   **Tipos de Alojamiento**: Gestión de categorías específicas para alojamientos.
    *   `GET /api/tipos-alojamientos` - Listar todos los tipos de alojamiento.
    *   `GET /api/tipos-alojamientos/{id}` - Obtener un tipo de alojamiento específico.
    *   `POST /api/tipos-alojamientos` - Crear un nuevo tipo de alojamiento.
    *   `PUT /api/tipos-alojamientos/{id}` - Actualizar un tipo de alojamiento.
    *   `DELETE /api/tipos-alojamientos/{id}` - Eliminar un tipo de alojamiento.

*   **Gastronómicos**: Gestión de establecimientos gastronómicos, sus tipos y menús asociados.
    *   `GET /api/gastronomicos` - Listar todos los establecimientos gastronómicos.
    *   `GET /api/gastronomicos/{id}` - Obtener un establecimiento gastronómico específico.
    *   `POST /api/gastronomicos` - Crear un nuevo establecimiento gastronómico.
    *   `PUT /api/gastronomicos/{id}` - Actualizar un establecimiento gastronómico.
    *   `DELETE /api/gastronomicos/{id}` - Eliminar un establecimiento gastronómico.

*   **Tipos de Gastronomía**: Gestión de tipos específicos para establecimientos gastronómicos.
    *   `GET /api/tipos-gastronomia` - Listar todos los tipos de gastronomía.
    *   `GET /api/tipos-gastronomia/{id}` - Obtener un tipo de gastronomía específico.
    *   `POST /api/tipos-gastronomia` - Crear un nuevo tipo de gastronomía.
    *   `PUT /api/tipos-gastronomia/{id}` - Actualizar un tipo de gastronomía.
    *   `DELETE /api/tipos-gastronomia/{id}` - Eliminar un tipo de gastronomía.

*   **Menús**: Gestión de tipos de menús.
    *   `GET /api/menus` - Listar todos los menús.
    *   `GET /api/menus/{id}` - Obtener un menú específico.
    *   `POST /api/menus` - Crear un nuevo menú.
    *   `PUT /api/menus/{id}` - Actualizar un menú.
    *   `DELETE /api/menus/{id}` - Eliminar un menú.

*   **Eventos**: Gestión de eventos y eventos destacados.
    *   `GET /api/eventos` - Listar todos los eventos.
    *   `GET /api/eventos/{id}` - Obtener un evento específico.
    *   `POST /api/eventos` - Crear un nuevo evento.
    *   `PUT /api/eventos/{id}` - Actualizar un evento.
    *   `DELETE /api/eventos/{id}` - Eliminar un evento.

*   **Actividades**: Gestión de actividades y sus tipos.
    *   `GET /api/actividades` - Listar todas las actividades.
    *   `GET /api/actividades/{id}` - Obtener una actividad específica.
    *   `POST /api/actividades` - Crear una nueva actividad.
    *   `PUT /api/actividades/{id}` - Actualizar una actividad.
    *   `DELETE /api/actividades/{id}` - Eliminar una actividad.

*   **Tipos de Actividad**: Gestión de tipos específicos para actividades.
    *   `GET /api/tipos-actividad` - Listar todos los tipos de actividad.
    *   `GET /api/tipos-actividad/{id}` - Obtener un tipo de actividad específico.
    *   `POST /api/tipos-actividad` - Crear un nuevo tipo de actividad.
    *   `PUT /api/tipos-actividad/{id}` - Actualizar un tipo de actividad.
    *   `DELETE /api/tipos-actividad/{id}` - Eliminar un tipo de actividad.

*   **Balnearios**: Gestión de balnearios.
    *   `GET /api/balnearios` - Listar todos los balnearios.
    *   `GET /api/balnearios/{id}` - Obtener un balneario específico.
    *   `POST /api/balnearios` - Crear un nuevo balneario.
    *   `PUT /api/balnearios/{id}` - Actualizar un balneario.
    *   `DELETE /api/balnearios/{id}` - Eliminar un balneario.

*   **Complejos**: Gestión de complejos.
    *   `GET /api/complejos` - Listar todos los complejos.
    *   `GET /api/complejos/{id}` - Obtener un complejo específico.
    *   `POST /api/complejos` - Crear un nuevo complejo.
    *   `PUT /api/complejos/{id}` - Actualizar un complejo.
    *   `DELETE /api/complejos/{id}` - Eliminar un complejo.

*   **Documentación de la API**:
    *   `GET /api/documentation` - Acceso a la interfaz de Swagger UI.

## Testing

El proyecto incluye tests unitarios y de característica para asegurar la calidad y el correcto funcionamiento de la API.

### **Ejecutar Tests**

Asegúrate de que tus servicios Docker estén corriendo (`docker-compose up -d`) antes de ejecutar los tests.

*   **Ejecutar todos los tests (unitarios y de característica)**:
    ```bash
    docker-compose exec php php artisan test
    ```

*   **Ejecutar solo tests unitarios**:
    ```bash
    docker-compose exec php php artisan test --testsuite=Unit
    ```
    **Nota**: Los tests unitarios incluyen verificaciones detalladas para los casts de atributos (booleanos, floats, datetimes, hashed passwords), accesores y relaciones en los modelos.

*   **Ejecutar solo tests de característica**:
    ```bash
    docker-compose exec php php artisan test --testsuite=Feature
    ```

*   **Ejecutar un archivo de test específico**:
    ```bash
    docker-compose exec php php artisan test tests/Feature/ComplejoApiTest.php
    ```
    (Reemplaza `tests/Feature/ComplejoApiTest.php` con la ruta de tu archivo de test)

##  Documentación de la API (Swagger UI)

La API está documentada utilizando Swagger/OpenAPI, y puedes acceder a una interfaz interactiva para explorarla y probarla.

### **Generar la Documentación**

Cada vez que se realicen cambios en las anotaciones de Swagger en el código (controladores o modelos), o después de un `git pull` que incluya cambios en la documentación, debes regenerar el archivo de documentación:

1.  **Limpia la caché de configuración de Laravel**:
    ```bash
    docker-compose exec php php artisan config:clear
    ```

2.  **Genera el archivo de documentación Swagger**:
    ```bash
    docker-compose exec php php artisan l5-swagger:generate
    ```

### **Acceder a la Documentación**

Una vez que la documentación ha sido generada, puedes acceder a la interfaz de Swagger UI en tu navegador:

1.  Asegúrate de que tus servicios Docker estén corriendo (`docker-compose up -d`).
2.  Abre tu navegador y navega a:
    http://localhost:8000/api/documentation

    Aquí podrás ver todos los endpoints, sus parámetros, modelos de solicitud y respuesta, y probar las llamadas a la API.

### **Áreas Clave Documentadas**

Se ha documentado exhaustivamente la API para las siguientes funcionalidades:

*   **Autenticación**: Registro, inicio y cierre de sesión de usuarios.
*   **Usuarios**: Gestión de usuarios y sus perfiles (`InfoUsuario`).
*   **Gastronomía**: Gestión de establecimientos gastronómicos, sus tipos y menús asociados.
*   **Eventos**: Gestión de eventos y eventos destacados.
*   **Actividades**: Gestión de actividades y sus tipos.
*   **Balnearios**: Gestión de balnearios.
*   **Alojamientos**: Gestión de alojamientos.
*   **Complejos**: Gestión de complejos.
*   **Tipos**: Gestión de tipos genéricos (usados en actividades e intereses de usuario).
*   **Tipos de Gastronomía**: Gestión de tipos específicos para establecimientos gastronómicos.
*   **Tipos de Alojamiento**: Gestión de tipos específicos para alojamientos.
*   **Menús**: Gestión de tipos de menús.

##  Importación Masiva de Datos (CSV)

Puedes importar datos iniciales para varias entidades utilizando comandos Artisan que leen archivos CSV.

### **Preparación de Archivos CSV**

1.  **Ubicación**: Coloca tus archivos CSV en el directorio `database/imports/` dentro de la raíz de tu proyecto backend.
    *   Ejemplo: `turismo-app/backend/turismo-backend/database/imports/complejos.csv`
2.  **Formato General**: Asegúrate de que la primera fila del CSV contenga los encabezados de las columnas y que los datos estén correctamente delimitados por comas. Si un campo contiene comas o saltos de línea, debe estar encerrado entre comillas dobles.

### **Comandos de Importación y Especificaciones CSV**

Puedes ejecutar comandos individuales o un comando maestro para importar todos los datos:

*   **Importar todos los datos (recomendado)**:
    ```bash
    docker-compose exec php php artisan import:all
    ```
    Este comando ejecutará secuencialmente todos los importadores.

*   **`php artisan import:complejos`**
    *   **Archivo CSV**: `database/imports/complejos.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**)
        *   `direccion` (string, opcional)
        *   `mail` (string, opcional)
        *   `redesSociales` (string, opcional)
        *   `telefono` (string, opcional)
        *   `servicio` (string, opcional)
        *   `adicional` (string, opcional)
        *   `imagen` (string URL, opcional)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)

*   **`php artisan import:eventos`**
    *   **Archivo CSV**: `database/imports/eventos.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**)
        *   `dirección` (string, opcional. Se mapea a `direccion` en la DB)
        *   `descripcion` (string, opcional)
        *   `fecha` (string, opcional. Formato esperado: `DD/MM/YYYY`)
        *   `lugar` (string, opcional)
        *   `imagen` (string URL, opcional)
        *   `destacado` (booleano, opcional, por defecto `false`. Valores como "true", "1", "false", "0" son interpretados.)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)

*   **`php artisan import:balnearios`**
    *   **Archivo CSV**: `database/imports/balnearios.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**)
        *   `direccion` (string, opcional)
        *   `telefono` (string, opcional)
        *   `redesSociales` (string, opcional)
        *   `servicios` (string, opcional)
        *   `mail` (string, opcional)
        *   `accesibilidad` (string, opcional)
        *   `fecha_desde_hasta` (string, opcional)
        *   `imagen` (string URL, opcional)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)

*   **`php artisan import:actividades`**
    *   **Archivo CSV**: `database/imports/actividades.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**)
        *   `direccion` (string, opcional)
        *   `descripcion` (string, opcional)
        *   `redesSociales` (string, opcional)
        *   `web` (string URL, opcional)
        *   `mail` (string, opcional)
        *   `telefono` (string, opcional)
        *   `imagen` (string URL, opcional)
        *   `diasYHorarios` (string, opcional)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)
        *   `tipo` (string, opcional. Si no existe, se crea un `Tipo` con este nombre.)

*   **`php artisan import:alojamientos`**
    *   **Archivo CSV**: `database/imports/alojamientos.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**)
        *   `direccion` (string, opcional)
        *   `telefono` (string, opcional)
        *   `redesSociales` (string, opcional)
        *   `web` (string URL, opcional)
        *   `mail` (string, opcional)
        *   `mascotas` (booleano, opcional. Se interpreta "si" como `true`, cualquier otro valor como `false`.)
        *   `periodoApertura` (string, opcional)
        *   `tipo` (string, opcional. Puede contener múltiples tipos separados por comas (ej. "Hotel, Cabaña"). Si un `TipoAlojamiento` no existe, se crea.)
        *   `imagen` (string URL, opcional)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)

*   **`php artisan import:gastronomicos`**
    *   **Archivo CSV**: `database/imports/gastronomia.csv`
    *   **Encabezados Esperados**:
        *   `nombre` (string, **obligatorio**. Las líneas con nombre vacío serán omitidas.)
        *   `direccion` (string, opcional)
        *   `telefono` (string, opcional)
        *   `redesSociales` (string, opcional)
        *   `horario` (string, opcional)
        *   `tiendaOnline` (string URL, opcional)
        *   `extras` (string, opcional)
        *   `imagen` (string URL, opcional)
        *   `latitud` (float, opcional)
        *   `longitud` (float, opcional)
        *   `habilitado` (booleano, opcional, por defecto `true`. Valores como "true", "1", "false", "0" son interpretados.)
        *   `tipo` (string, opcional. Puede contener múltiples tipos separados por `|` (barra vertical). Si un `TipoGastronomico` no existe, se crea.)
        *   `menues_especiales` (string, opcional. Puede contener múltiples nombres de menú separados por `|`. Si un `Menu` no existe, se crea.)

**Nota**: Si ejecutas los comandos individuales, asegúrate de que los tipos y menús necesarios existan antes de importar actividades y gastronómicos, o que la lógica `firstOrCreate` en los comandos maneje su creación. El comando `import:all` ya considera un orden adecuado.

##  Diagramas del Proyecto

Para una comprensión visual de la estructura y las relaciones del proyecto, consulta los diagramas ubicados en la carpeta `docs/diagrams/`.

**Es fundamental mantener estos diagramas actualizados.** Si se modifica la estructura de la base de datos, la arquitectura de los componentes o los flujos de negocio, los diagramas deben reflejar esos cambios.

### Tipos de Diagramas Incluidos:

*   **Diagrama Entidad-Relación (ERD)**: Muestra las tablas de la base de datos y sus relaciones.
*   **Diagrama de Arquitectura / Componentes**: Ilustra la interconexión de los servicios del backend.
*   **Diagrama de Clases**: Representa la estructura de las clases y sus relaciones.

Los archivos fuente (`.drawio`) y sus exportaciones en imagen (`.png`) se encuentran en la carpeta `docs/diagrams/`.

##  Documentación Adicional

Para una comprensión más profunda del proyecto, consulta los siguientes documentos:

*   [**Arquitectura del Proyecto**](docs/ARCHITECTURE.md): Detalles sobre la estructura de carpetas, convenciones de nombres, patrones de diseño, variables de entorno clave, seguridad, logging y estrategia de despliegue.
*   [**Guía de Contribución**](docs/CONTRIBUTING.md): Información sobre cómo contribuir, flujo de trabajo de Git y estándares de código.
*   [**Guía de Uso de la API**](docs/API_GUIDELINES.md): Detalles sobre códigos de estado HTTP, filtrado, paginación, ordenamiento y ejemplos de request/response.

##  Detener los Servicios

Para detener y eliminar los contenedores de Docker (manteniendo los volúmenes de datos):

```bash
docker-compose down
```

Para detener y eliminar los contenedores y sus volúmenes (borrando los datos de la base de datos):

```bash
docker-compose down -v
```
