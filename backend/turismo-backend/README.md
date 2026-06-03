# Backend de Turismo Necochea

Este repositorio contiene el código fuente del backend de la aplicación de Turismo Necochea, desarrollado con Laravel. La API RESTful gestiona la información sobre alojamientos, gastronomía, eventos, actividades, balnearios, complejos y usuarios.

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
    docker-compose up -d
    ```
    Esto construirá las imágenes (si es la primera vez) y levantará los contenedores en segundo plano.

4.  **Instala las dependencias de Composer**:
    Ejecuta Composer dentro del contenedor PHP para instalar todas las dependencias del proyecto.
    ```bash
    docker-compose exec php composer install
    ```

5.  **Genera la clave de la aplicación Laravel**:
    ```bash
    docker-compose exec php php artisan key:generate
    ```

##  Base de Datos

Después de levantar los servicios y instalar las dependencias, necesitas configurar la base de datos:

-   **Ejecuta las migraciones**:
    Esto creará las tablas en tu base de datos MySQL.
    ```bash
    docker-compose exec php php artisan migrate
    ```

##  Testing

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
*   **Menús**: Gestión de tipos de menús.

##  Importación Masiva de Datos (CSV)

Puedes importar datos iniciales para varias entidades utilizando comandos Artisan que leen archivos CSV.

### **Preparación de Archivos CSV**

1.  **Ubicación**: Coloca tus archivos CSV en el directorio `database/imports/` dentro de la raíz de tu proyecto backend.
    *   Ejemplo: `turismo-app/backend/turismo-backend/database/imports/complejos.csv`
2.  **Formato**: Asegúrate de que la primera fila del CSV contenga los encabezados de las columnas y que los datos estén correctamente delimitados por comas y, si un campo contiene comas o saltos de línea, que esté encerrado entre comillas dobles.

### **Comandos de Importación**

Puedes ejecutar comandos individuales o un comando maestro para importar todos los datos:

*   **Importar todos los datos (recomendado)**:
    ```bash
    docker-compose exec php php artisan import:all
    ```
    Este comando ejecutará secuencialmente todos los importadores.

*   **Comandos individuales**:
    *   **Complejos**:
        ```bash
        docker-compose exec php php artisan import:complejos
        ```
    *   **Eventos**:
        ```bash
        docker-compose exec php php artisan import:eventos
        ```
    *   **Balnearios**:
        ```bash
        docker-compose exec php php artisan import:balnearios
        ```
    *   **Alojamientos**:
        ```bash
        docker-compose exec php php artisan import:alojamientos
        ```
    *   **Actividades**:
        ```bash
        docker-compose exec php php artisan import:actividades
        ```
    *   **Gastronómicos**:
        ```bash
        docker-compose exec php php artisan import:gastronomicos
        ```

**Nota**: Si ejecutas los comandos individuales, asegúrate de que los tipos y menús necesarios existan antes de importar actividades y gastronómicos, o que la lógica `firstOrCreate` en los comandos maneje su creación. El comando `import:all` ya considera un orden adecuado.

##  Detener los Servicios

Para detener y eliminar los contenedores de Docker (manteniendo los volúmenes de datos):

```bash
docker-compose down
```

Para detener y eliminar los contenedores y sus volúmenes (borrando los datos de la base de datos):

```bash
docker-compose down -v
```
