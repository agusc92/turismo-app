# Guía de Uso de la API

Este documento proporciona una guía más detallada sobre cómo interactuar con la API del backend de Turismo Necochea, complementando la documentación generada por Swagger/OpenAPI.

## 1. Códigos de Estado HTTP Comunes

La API utiliza códigos de estado HTTP estándar para indicar el éxito o el fracaso de una solicitud. Comprender estos códigos es fundamental para interactuar correctamente con la API.

*   **`200 OK`**: La solicitud fue exitosa. La respuesta contiene los datos solicitados.
*   **`201 Created`**: La solicitud fue exitosa y se creó un nuevo recurso. Comúnmente devuelto después de una solicitud `POST`.
*   **`204 No Content`**: La solicitud fue exitosa, pero no hay contenido para devolver. Comúnmente devuelto después de una solicitud `DELETE` exitosa.
*   **`400 Bad Request`**: La solicitud no pudo ser entendida por el servidor debido a una sintaxis inválida.
*   **`401 Unauthorized`**: La solicitud requiere autenticación. El usuario no ha proporcionado credenciales válidas o no ha iniciado sesión.
*   **`403 Forbidden`**: El servidor entendió la solicitud, pero se niega a autorizarla. El usuario autenticado no tiene los permisos necesarios para acceder al recurso.
*   **`404 Not Found`**: El recurso solicitado no pudo ser encontrado en el servidor.
*   **`405 Method Not Allowed`**: El método HTTP utilizado en la solicitud no está permitido para el recurso solicitado (ej. intentar un `POST` en un endpoint que solo acepta `GET`).
*   **`422 Unprocessable Entity`**: La solicitud fue bien formada, pero no se pudo seguir debido a errores semánticos (ej. fallos de validación de datos). La respuesta suele incluir detalles sobre los errores de validación.
*   **`500 Internal Server Error`**: Un error inesperado ocurrió en el servidor. Esto indica un problema en el backend que debe ser investigado.

## 2. Filtrado, Paginación y Ordenamiento

Para la mayoría de los endpoints que devuelven colecciones de recursos (ej. `GET /api/alojamientos`), la API soporta parámetros de consulta para filtrar, paginar y ordenar los resultados.

### Paginación
Por defecto, las colecciones de recursos están paginadas. Puedes controlar la paginación usando los siguientes parámetros de consulta:
*   **`page`**: El número de página a recuperar.
    *   `Ejemplo`: `/api/alojamientos?page=2`
*   **`per_page`**: El número de elementos por página.
    *   `Ejemplo`: `/api/alojamientos?per_page=20`

### Filtrado
Puedes filtrar los recursos por uno o más campos utilizando el siguiente formato:
*   **`filter[nombre_del_campo]=valor`**: Filtra los recursos donde `nombre_del_campo` coincide con `valor`.
    *   `Ejemplo`: `/api/eventos?filter[destacado]=true`
    *   `Ejemplo`: `/api/gastronomicos?filter[nombre]=Pizzeria`

### Ordenamiento
Puedes ordenar los recursos por uno o más campos:
*   **`sort=nombre_del_campo`**: Ordena los resultados de forma ascendente por el campo especificado.
    *   `Ejemplo`: `/api/alojamientos?sort=nombre`
*   **`sort=-nombre_del_campo`**: Ordena los resultados de forma descendente por el campo especificado (usando un prefijo `-`).
    *   `Ejemplo`: `/api/eventos?sort=-fecha`

Puedes combinar múltiples parámetros de consulta:
*   `GET /api/alojamientos?page=1&per_page=10&filter[mascotas]=true&sort=nombre`

## 3. Ejemplos de Request/Response (JSON)

La documentación de Swagger/OpenAPI (disponible en `/api/documentation`) proporciona ejemplos interactivos y detallados para cada endpoint. Sin embargo, aquí se presenta un ejemplo genérico de cómo se estructuran las solicitudes y respuestas.

### Ejemplo de Solicitud (POST /api/alojamientos)

```json
{
    "nombre": "Hotel Sol y Mar",
    "direccion": "Calle Falsa 123",
    "telefono": "2262-123456",
    "mail": "info@hotelsolymar.com",
    "mascotas": true,
    "imagen": "http://example.com/imagen_hotel.jpg",
    "latitud": -38.555,
    "longitud": -58.777,
    "habilitado": true,
    "tipos_alojamiento_ids": [1, 3]
}
```

### Ejemplo de Respuesta Exitosa (201 Created)

```json
{
    "data": {
        "id": 1,
        "nombre": "Hotel Sol y Mar",
        "direccion": "Calle Falsa 123",
        "telefono": "2262-123456",
        "redesSociales": null,
        "paginaWeb": null,
        "mail": "info@hotelsolymar.com",
        "mascotas": true,
        "periodoApertura": null,
        "imagen": "http://example.com/imagen_hotel.jpg",
        "latitud": -38.555,
        "longitud": -58.777,
        "habilitado": true,
        "created_at": "2023-10-27T10:00:00.000000Z",
        "updated_at": "2023-10-27T10:00:00.000000Z",
        "tipos_alojamiento": [
            {
                "id": 1,
                "tipo": "Hotel"
            },
            {
                "id": 3,
                "tipo": "Apart"
            }
        ]
    }
}
```

### Ejemplo de Respuesta con Error de Validación (422 Unprocessable Entity)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nombre": [
            "El campo nombre es obligatorio."
        ],
        "direccion": [
            "El campo direccion es obligatorio."
        ]
    }
}
```
