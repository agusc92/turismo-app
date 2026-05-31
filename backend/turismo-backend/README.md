# Backend de Turismo Necochea

Este repositorio contiene el código fuente del backend de la aplicación de Turismo Necochea, desarrollado con Laravel. La API RESTful gestiona la información sobre alojamientos, gastronomía, eventos, actividades, balnearios y usuarios.

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

1.  **Ejecuta las migraciones**:
    Esto creará las tablas en tu base de datos MySQL.
    ```bash
    docker-compose exec php php artisan migrate
    ```

2.  **(Opcional) Si deseas poblar la base de datos con datos de prueba**:
    ```bash
    docker-compose exec php php artisan db:seed
    ```

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
*   **Tipos**: Gestión de tipos genéricos (usados en actividades e intereses de usuario).
*   **Tipos de Gastronomía**: Gestión de tipos específicos para establecimientos gastronómicos.
*   **Menús**: Gestión de tipos de menús.

##  Detener los Servicios

Para detener y eliminar los contenedores de Docker (manteniendo los volúmenes de datos):

```bash
docker-compose down
```

Para detener y eliminar los contenedores y sus volúmenes (borrando los datos de la base de datos):

```bash
docker-compose down -v
```
