# Arquitectura

[← Volver al README principal](../README.md)

## Estructura del Proyecto

El proyecto utiliza **Expo Router** para el enrutamiento basado en archivos, por lo que la estructura de carpetas define la navegación de la aplicación.

- `/app`: contiene las pantallas y la lógica de enrutamiento principal.
  - `/(tabs)`: pantallas principales de la barra de navegación inferior.
  - `/actividad`, `/alojamiento`, `/evento`, `/gastronomico`, etc.: categorías de recursos.
  - `[id].js`: rutas dinámicas para mostrar el detalle de un registro.
  - `/hooks`: custom hooks para la obtención de datos.
- `/assets`: imágenes, íconos y fuentes.
- `/components`: componentes de interfaz reutilizables.
- `/constants`: valores compartidos, como colores y estilos.
- `api.js`: dirección IP y URL base de la API.
- `app.json` / `eas.json`: configuración de Expo y perfiles de compilación de EAS.

## Navegación (Expo Router)

La navegación está basada en la estructura de archivos dentro de `/app`. Expo Router convierte cada archivo de pantalla en una ruta.

Las carpetas `/actividad`, `/alojamiento`, `/balneario`, `/complejo`, `/evento` y `/gastronomico` agrupan las pantallas por categoría. Los archivos `[id].js` son rutas dinámicas reutilizables para mostrar el detalle de distintos registros.

La carpeta `/(tabs)` define las pestañas principales y la navegación inferior. Los paréntesis indican un **route group**: agrupan rutas sin agregar `tabs` a la URL. Las pantallas secundarias se gestionan mediante una navegación tipo **Stack**.

## Gestión de Estado y Fetching

La aplicación utiliza **custom hooks de React** para centralizar las peticiones a la API y separar la obtención de datos de la lógica de las pantallas. La URL base se importa desde `api.js` mediante `API_URL`.

### Hooks principales

- `useActividadesData`: obtiene en paralelo las actividades y sus tipos mediante `Promise.all`.
- `useGastronomiaData`: consulta locales gastronómicos, tipos de gastronomía y menús, y normaliza las respuestas.
- `useData(ruta)`: hook genérico para consultar un recurso, como `balnearios` o `actividades`.
- `useFetchDetalle(endpoint, id)`: obtiene el detalle de un recurso específico.

### Flujo de datos

Cada hook utiliza `useState` para almacenar los datos y el estado `loading`, y `useEffect` para ejecutar el `fetch` al montar el componente o cambiar sus dependencias. Mientras la respuesta está pendiente se muestra un indicador de carga; al finalizar, el estado se actualiza y el componente vuelve a renderizarse.

Ejemplo de uso:

```javascript
const { actividades, tipoActividades, loading } = useActividadesData();

if (loading) {
    return <ActivityIndicator />;
}

return <ListaActividades datos={actividades} tipos={tipoActividades} />;
```

Si ocurre un error, los hooks lo registran en la consola y finalizan el estado de carga.
