# Integración con la API

[← Volver al README principal](../README.md)

Este documento describe cómo la aplicación móvil obtiene información del backend y qué formato de datos espera recibir. 

## Configuración de la URL base


Las rutas indicadas aquí son relativas a `API_URL`. Por ejemplo:

```text
http://localhost:8000/api/actividades
```



## Endpoints consumidos por la aplicación

### Actividades

| Método | Ruta | Uso |
| --- | --- | --- |
| `GET` | `/actividades` | Lista de actividades para `useActividadesData`. |
| `GET` | `/actividades/{id}` | Detalle mediante `useFetchDetalle`. |
| `GET` | `/tipos` | Tipos de actividad para los filtros. |

`useActividadesData` solicita `/actividades` y `/tipos` en paralelo. El endpoint `/tipos` puede devolver cadenas u objetos; el frontend admite ambos formatos y busca `nombre` o `tipo` cuando recibe un objeto.

### Eventos

| Método | Ruta | Uso |
| --- | --- | --- |
| `GET` | `/eventos` | Listado filtrable por fecha. |
| `GET` | `/eventos/{id}` | Detalle de un evento. |
| `GET` | `/eventos/destacados` | Eventos del carrusel de la pantalla principal. |

Los eventos deben incluir `id`, `nombre`, `fecha` e `imagen` para renderizar correctamente las tarjetas y el carrusel. La fecha debe poder convertirse a una fecha JavaScript; el formato recomendado es `YYYY-MM-DD`.

### Gastronomía

| Método | Ruta | Uso |
| --- | --- | --- |
| `GET` | `/gastronomicos` | Lista de establecimientos gastronómicos. |
| `GET` | `/gastronomicos/{id}` | Detalle de un establecimiento. |
| `GET` | `/tipo-gastronomicos` | Tipos para los filtros gastronómicos. |
| `GET` | `/menus` | Tipos de menú para los filtros. |

> La ruta correcta es `/gastronomicos`, en plural. La aplicación no solicita `/gastronomico` en singular.

`useGastronomiaData` consulta los tres listados en paralelo. Para los filtros, acepta tipos y menús como cadenas u objetos.

### Otros recursos consultados

Las pantallas de alojamientos, balnearios y complejos utilizan los hooks genéricos:

| Método | Ruta | Uso |
| --- | --- | --- |
| `GET` | `/alojamientos` | Listado de alojamientos. |
| `GET` | `/alojamientos/{id}` | Detalle de alojamiento. |
| `GET` | `/balnearios` | Listado de balnearios. |
| `GET` | `/balnearios/{id}` | Detalle de balneario. |
| `GET` | `/complejos` | Listado de complejos. |
| `GET` | `/complejos/{id}` | Detalle de complejo. |

El mapa también consulta listados de recursos para mostrar sus ubicaciones. Los recursos con coordenadas deben conservar `latitud` y `longitud` como valores numéricos o convertibles a número.

## Formatos JSON esperados

Los siguientes ejemplos son mockups mínimos compatibles con los campos que utiliza actualmente la aplicación. Los campos opcionales pueden llegar con `null`.

### Actividad

`GET /actividades` devuelve un arreglo de objetos:

```json
[
  {
    "id": 1,
    "nombre": "Visita guiada al puerto",
    "direccion": "Av. 10 y 59, Necochea",
    "descripcion": "Recorrido guiado por el puerto.",
    "redes_sociales": "https://instagram.com/ejemplo",
    "web": "https://ejemplo.com",
    "mail": "contacto@ejemplo.com",
    "telefono": "2262-123456",
    "imagen": "https://ejemplo.com/actividad.jpg",
    "latitud": -38.5542,
    "longitud": -58.7396,
    "tipo_id": 2,
    "dias_y_horarios": "Sábados y domingos, 10:00 h",
    "habilitado": true,
    "tipo": { "id": 2, "nombre": "Cultura" }
  }
]
```

### Evento

`GET /eventos` y `GET /eventos/destacados` devuelven un arreglo con esta estructura:

```json
[
  {
    "id": 10,
    "nombre": "Festival de la ciudad",
    "direccion": "Parque Miguel Lillo",
    "descripcion": "Actividades para toda la familia.",
    "fecha": "2026-04-20",
    "lugar": "Anfiteatro del parque",
    "imagen": "https://ejemplo.com/evento.jpg",
    "destacado": true,
    "latitud": -38.5621,
    "longitud": -58.7391,
    "habilitado": true
  }
]
```

### Establecimiento gastronómico

`GET /gastronomicos` devuelve un arreglo de establecimientos:

```json
[
  {
    "id": 20,
    "nombre": "Restaurante del centro",
    "direccion": "Calle 59 y 60, Necochea",
    "telefono": "2262-654321",
    "redesSociales": "https://instagram.com/restaurante",
    "horario": "12:00 a 23:00 h",
    "tiendaOnline": "https://ejemplo.com/pedidos",
    "extras": "Opciones vegetarianas",
    "imagen": "https://ejemplo.com/restaurante.jpg",
    "latitud": -38.5472,
    "longitud": -58.7368,
    "habilitado": true,
    "tipo": ["Restaurante"],
    "menu": ["Almuerzo"],
    "menus": [{ "id": 1, "tipo": "Almuerzo" }],
    "tipos": [{ "id": 1, "nombre": "Restaurante" }]
  }
]
```

### Tipos y menús

El formato más simple, documentado por Swagger, es un arreglo de cadenas:

```json
[
  "Restaurante",
  "Cafetería",
  "Bar"
]
```

El frontend también tolera objetos. Para `/tipos`, reconoce `nombre` o `tipo`; para `/tipo-gastronomicos` y `/menus`, conserva el objeto recibido:

```json
[
  { "id": 1, "nombre": "Cultura" },
  { "id": 2, "tipo": "Almuerzo" }
]
```

## Reglas de compatibilidad

Al modificar el backend, conserva estas condiciones:

- Los listados deben devolver arreglos JSON, no objetos envoltorio como `{ "data": [...] }`, salvo que también se actualicen los hooks.
- Los detalles deben devolver un objeto JSON directamente.
- `id` debe estar presente y ser convertible a texto o número.
- Las tarjetas utilizan principalmente `nombre`, `imagen`, `direccion` y `descripcion`.
- Las pantallas del mapa necesitan `latitud` y `longitud`.
- Los eventos necesitan `fecha` en un formato parseable por JavaScript.
- Las URLs de imágenes deben ser accesibles desde el dispositivo o emulador.
- Los campos opcionales pueden ser `null`, pero no deben cambiar de nombre sin adaptar los componentes consumidores.

## Relación con el código del frontend

- `app/hooks/useActividadesData.js`: `/actividades` y `/tipos`.
- `app/hooks/useGastronomiaData.js`: `/gastronomicos`, `/tipo-gastronomicos` y `/menus`.
- `app/hooks/useData.js`: listados genéricos.
- `app/hooks/useFetchDetalle.js`: detalles `/{endpoint}/{id}`.
- `app/evento/eventos.js`: `/eventos`.
- `components/Carousel.jsx`: `/eventos/destacados`.
