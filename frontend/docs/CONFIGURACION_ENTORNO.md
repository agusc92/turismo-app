# Configuración de entorno y de la aplicación

[← Volver al README principal](../README.md)

## Configuración de Entorno y API

Este proyecto **no utiliza un archivo `.env`**. La configuración de red y la URL base del backend se centralizan en `api.js`.

Antes de programar o compilar, modifica el archivo según el entorno:

```javascript
// api.js

// Desarrollo local
export const IP_ADDRESS = "192.168.1.7";
export const API_URL = `http://${IP_ADDRESS}:8000/api`;

// Producción: reemplaza la URL por el dominio real del backend
// export const API_URL = "https://api.turismonecochea.app/api";
```

Si se prueba en un dispositivo físico, debe utilizarse la IP de la computadora en la red local; `localhost` apunta al propio teléfono y no al equipo de desarrollo.

En producción, el backend local debe reemplazarse por el servidor público donde la Municipalidad o la Secretaría de Turismo aloje la API.

## Configuración de la App (`app.json`)

Antes de compilar, hay que revisar los valores principales de `app.json`:

- **Nombre público:** `name` define el nombre visible de la aplicación.
- **Versión:** `version` indica la iteración actual y debe incrementarse en cada actualización publicada.
- **Identificador único:** `android.package` y el Bundle Identifier identifican la aplicación de forma permanente.
- **Íconos:** `icon` y `android.adaptiveIcon` definen el ícono principal y sus imágenes de adaptación.
- **Pantalla de carga:** `splash.image` y `splash.backgroundColor` configuran la pantalla inicial.

El identificador de la aplicación no debe cambiar después de la primera publicación.
