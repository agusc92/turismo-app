

<p align="center">
  <img src="public/dark.png#gh-dark-mode-only" width="400">
  <img src="public/light.png#gh-light-mode-only" width="400">
</p>

[![React Native](https://img.shields.io/badge/React_Native-0.83.2-20232a?style=for-the-badge&logo=react&logoColor=61DAFB)](https://reactnative.dev/)
[![Expo](https://img.shields.io/badge/Expo-~55.0.6-000020?style=for-the-badge&logo=expo&logoColor=white)](https://expo.dev/)
[![Expo Router](https://img.shields.io/badge/Expo_Router-~55.0.5-000020?style=for-the-badge&logo=expo&logoColor=white)](https://docs.expo.dev/router/introduction/)
[![React](https://img.shields.io/badge/React-^19.2.4-61DAFB?style=for-the-badge&logo=react&logoColor=black)](https://react.dev/)
[![EAS Build](https://img.shields.io/badge/EAS_Build-Configured-4630EB?style=for-the-badge&logo=expo&logoColor=white)](https://expo.dev/eas)

# Frontend de Descubrí Necochea




## Puesta en Marcha

Para levantar el entorno de desarrollo de la aplicación móvil utilizando Expo, sigue estos pasos:

1. **Navega a la raíz del proyecto móvil:**

    ```bash
    cd turismo-app
    ```

2. **Configura la conexión con la API:** En este proyecto, la configuración de red se maneja directamente en el archivo `api.js`. Abre el archivo y asegúrate de que la IP corresponda a la de tu computadora en la red local.

    ```javascript
    // api.js
    export const IP_ADDRESS = "192.168.1.7"; // <-- Cambia esto por tu IPv4 local
    export const API_URL = `http://${IP_ADDRESS}:8000/api`;
    ```

    **Importante:** Si pruebas en un dispositivo físico, es obligatorio usar la IP de tu computadora (ej. `192.168.x.x`), no uses `localhost` ya que el celular buscará la API dentro de sí mismo.

3. **Instala las dependencias del proyecto (¡Importante!):**

    ```bash
    npm install
    ```

4. **Levanta el servidor de desarrollo de Expo:**

    ```bash
    npx expo start
    ```

    **Nota sobre `npx expo start`:**

    *   `npx expo start`: Inicia el Metro Bundler y genera un código QR. Escanéalo con la app **Expo Go** en tu celular para compilar la app (tu celular y PC deben estar en la misma red Wi-Fi). Si usas emulador, presiona `a` (Android) o `i` (iOS) en la terminal.
    *   `npx expo start -c`: Este comando levanta el servidor limpiando la caché. Es crucial usar `-c` cuando has instalado nuevas dependencias o si notas comportamientos extraños tras actualizar el código.
## Estructura del Proyecto

El proyecto utiliza **Expo Router** para el enrutamiento basado en archivos, lo que significa que la estructura de carpetas define la navegación de la aplicación.

*   `/app`: Contiene las pantallas y la lógica de enrutamiento principal de la aplicación.
    *   `/(tabs)`: Pantallas principales que componen la barra de navegación inferior.
    *   `/actividad`, `/alojamiento`, `/evento`, `/gastronomico`, etc.: Carpetas que representan las distintas categorías de recursos.
    *   `[id].js`: Archivos entre corchetes que representan rutas dinámicas (ej. para ver el detalle de un local gastronómico específico).
    *   `/hooks`: Custom Hooks de React (ej. manejo de fetch de datos).
*   `/assets`: Recursos estáticos como imágenes, íconos y fuentes.
*   `/components`: Componentes de interfaz de usuario reutilizables (tarjetas, botones de filtro, cabeceras).
*   `/constants`: Variables constantes de la aplicación (colores, temas, tipografías).
*   `api.js`: Archivo de configuración centralizado que contiene la dirección IP y la URL base para conectar con el backend.
*   `app.json` / `eas.json`: Archivos de configuración general de Expo y de los perfiles de compilación para la nube (EAS).

## Estilos y diseño visual

La interfaz utiliza el sistema de estilos de **React Native**. No se emplean hojas de estilo CSS globales para las pantallas nativas: cada componente define sus estilos mediante `StyleSheet.create`, y los valores compartidos se centralizan en `constants/Styles.js`.

### Paleta de colores

La paleta principal se exporta desde `constants/Styles.js` como `Colors`, para mantener una apariencia consistente:

| Uso | Constante | Color |
| --- | --- | --- |
| Fondo general de la aplicación | `backgroundLight` | `#F7F6FC` |
| Texto principal | `textColor` | `#36274E` |
| Texto secundario | `textColorLight` | `#8A819C` |
| Contenedores de datos | `dataContainerColor` | `#F7F7FA` |
| Fondo de filtros | `filterBackgroundColor` | `#F0EFFF` |
| Títulos de detalle | `TitleDetalles.color` | `#31204D` |

Además, las pantallas utilizan colores auxiliares para estados y elementos específicos: blanco (`#FFFFFF`) para tarjetas y superficies, grises para textos o placeholders, y colores de acción como `#007AFF` para controles activos. Los componentes pueden definir estos valores localmente cuando el color depende del contexto, por ejemplo los botones de contacto, los indicadores de navegación y los marcadores del mapa.

### Tipografías

La familia tipográfica principal es **Gotham**, almacenada en `assets/fuentes/` y cargada al iniciar la aplicación mediante `useFonts` de `expo-font`. Las variantes registradas son:

| Nombre usado en los estilos | Archivo | Uso habitual |
| --- | --- | --- |
| `Gotham-Ultra` | `Gotham-Ultra.otf` | Encabezados destacados |
| `Gotham-Black` | `Gotham-Black.otf` | Títulos principales |
| `Gotham-Bold` | `GOTHMBOL.ttf` | Títulos, etiquetas y botones |
| `Gotham-Medium` | `GOTHMMED.ttf` | Texto con énfasis y contenido de tarjetas |
| `Gotham-Book` | `GOTHMBOK.ttf` | Texto descriptivo y secundario |
| `Gotham-Light` | `GOTHMLIG.ttf` | Texto liviano |

El layout principal mantiene visible el `SplashScreen` hasta que las fuentes terminan de cargar. Esto evita que la interfaz se muestre temporalmente con una tipografía de reemplazo.

### Herramientas y organización de estilos

- **`StyleSheet.create` de React Native:** organiza los estilos de cada pantalla y componente con objetos reutilizables y optimizados para iOS, Android y web.
- **`constants/Styles.js`:** concentra la paleta compartida y los estilos reutilizables de títulos de detalle.
- **Estilos combinados y dinámicos:** React Native permite combinar estilos con arreglos y objetos inline, por ejemplo para cambiar el color de un filtro según su estado o aplicar el color de una categoría en el mapa.
- **`expo-linear-gradient`:** se utiliza para superponer degradados sobre imágenes y mejorar la legibilidad del texto.
- **Leaflet:** el mapa carga la hoja de estilos externa `leaflet.css` y agrega reglas CSS inline para sus marcadores, ventanas emergentes y controles embebidos.

Para agregar o modificar estilos, se recomienda reutilizar `Colors` y las familias Gotham ya registradas antes de crear nuevos valores. Los estilos exclusivos de un componente deben permanecer junto a ese componente; los valores que se repiten en varias pantallas deben incorporarse a `constants/Styles.js`.

## Navegación (Expo Router)

La navegación de la aplicación está basada en la estructura de archivos dentro de `/app`. Expo Router convierte cada archivo de pantalla en una ruta, por lo que la ubicación y el nombre de cada archivo determinan cómo se accede a esa pantalla.

### Pantallas y rutas

Las carpetas `/actividad`, `/alojamiento`, `/balneario`, `/complejo`, `/evento` y `/gastronomico` agrupan las pantallas según la categoría de contenido. Los archivos con parámetros dinámicos, como `[id].js`, permiten reutilizar una misma pantalla para mostrar el detalle de distintos registros.

### Tabs y Stack

La carpeta `/(tabs)` define las pestañas principales de la aplicación y organiza la navegación inferior. Los paréntesis indican un **route group** de Expo Router: agrupan rutas sin agregar `tabs` a la URL.

Las pantallas que se abren desde una pestaña, como los listados y sus vistas de detalle, se gestionan mediante una navegación tipo **Stack**. De esta forma, el usuario puede avanzar desde una pestaña hacia una pantalla secundaria y volver con el botón de navegación del dispositivo o de la interfaz.


## Gestión de Estado y Fetching

La aplicación utiliza **custom hooks de React** para centralizar las peticiones a la API y mantener separadas la obtención de datos y la lógica de las pantallas. La URL base se importa desde `api.js` mediante la constante `API_URL`.

### Hooks principales

- `useActividadesData`: obtiene en paralelo las actividades y sus tipos mediante `Promise.all`. Devuelve `actividades`, `tipoActividades` y `loading`.
- `useGastronomiaData`: consulta locales gastronómicos, tipos de gastronomí­a y menús, y normaliza respuestas recibidas como objetos o cadenas simples.
- `useData(ruta)`: hook genérico para consultar un recurso, por ejemplo `balnearios` o `actividades`, a partir de la ruta recibida.
- `useFetchDetalle(endpoint, id)`: obtiene el detalle de un recurso específico usando su endpoint y su identificador.

### Flujo de datos

Cada hook utiliza `useState` para almacenar los datos y el estado `loading`, y `useEffect` para ejecutar el `fetch` cuando se monta el componente o cambian sus dependencias. Mientras la respuesta está pendiente, la pantalla puede mostrar un indicador de carga. Cuando termina la petición, el hook actualiza el estado y el componente se renderiza nuevamente con la información recibida.

Por ejemplo, una pantalla puede consumir `useActividadesData` así:

```javascript
const { actividades, tipoActividades, loading } = useActividadesData();

if (loading) {
    return <ActivityIndicator />;
}

return <ListaActividades datos={actividades} tipos={tipoActividades} />;
```

Si ocurre un error durante la consulta, los hooks lo registran en la consola y finalizan el estado de carga para que la interfaz pueda continuar su flujo habitual.

## Configuración de Entorno y API

A diferencia de muchos proyectos estándar de React/Expo, este proyecto **no utiliza un archivo `.env`** para manejar las variables de entorno. En su lugar, toda la configuración de red y la URL base para consumir los datos del backend se centralizan en el archivo `api.js` ubicado en la raíz del proyecto.

Es fundamental modificar este archivo dependiendo de si estás programando en tu computadora (Desarrollo) o si vas a compilar la aplicación para subirla a las tiendas (Producción).


### Entorno de Producción (Despliegue)

Cuando el proyecto esté listo para ser empaquetado (Build) y publicado en la Google Play Store o Apple App Store, el backend local ya no servirá. La aplicación debe apuntar al servidor público y definitivo donde la Municipalidad o la Secretaría de Turismo tenga alojada la API.

### ¿Cómo configurar el archivo `api.js`?

Antes de programar o antes de compilar, abre el archivo `api.js` y comenta/descomenta las líneas según el entorno que necesites:

```javascript
// api.js

// ==========================================
// 1. ENTORNO DE DESARROLLO (Local)
// ==========================================
// Descomenta estas líneas para probar la app en tu red Wi-Fi.
// Asegúrate de actualizar la IP con tu IPv4 actual.

export const IP_ADDRESS = "192.168.1.7"; 
export const API_URL = `http://${IP_ADDRESS}:8000/api`;


// ==========================================
// 2. ENTORNO DE PRODUCCIÓN (Despliegue final)
// ==========================================
// Comenta las líneas de arriba y descomenta esta línea ANTES de ejecutar 
// el comando de compilación (EAS Build). Reemplaza la URL por el dominio real.

// export const API_URL = "[https://api.turismonecochea.app/api](https://api.turismonecochea.app/api)";
```
## Configuración de la App (`app.json`)

Antes de compilar la aplicación para su publicación, la persona encargada del despliegue debe revisar y ajustar los siguientes valores clave dentro del archivo `app.json`:

*   **Nombre público:** El campo `name` define el nombre visible para los usuarios debajo del ícono en sus celulares (ej. `"Descubrí Necochea"`). 
*   **Versión de la app:** El campo `version` indica la iteración actual (ej. `"1.0.0"`). **Importante:** Cada vez que se envíe una actualización a las tiendas, este número debe incrementarse obligatoriamente (ej. a `"1.0.1"` o `"1.1.0"`). Si no se actualiza, Google Play rechazará el binario.
*   **Identificadores Únicos (Package y Bundle Identifier):** Actúan como el "DNI" inmutable de la app a nivel mundial, utilizando un formato de dominio inverso (ej. `"com.descubrinecochea.turismo"`). Una vez publicada la primera versión, este identificador **no se puede cambiar nunca más**.
    *   **Android:** Se configura en el campo `android.package`.
    
*   **Íconos (`icon`):** Define la ruta de la imagen principal. Para Android, se utiliza la subsección `android.adaptiveIcon`, donde se especifica una imagen frontal (`foregroundImage`) y un color de fondo sólido (`backgroundColor`) para asegurar que el ícono se adapte a las distintas formas de pantalla de los dispositivos.
*   **Pantalla de Carga (`splash`):** Es la vista que aparece mientras la app se inicia. Requiere indicar la ruta del logo central en el campo `splash.image` y el color que llenará el resto del fondo en `splash.backgroundColor`.

# Guía de Compilación de Android (APK para Pruebas y AAB para Google Play)

Esta sección detalla el proceso para compilar y generar el archivo instalable **.apk** de la aplicación móvil utilizando **EAS Build** (Expo Application Services).

### Requisitos Previos

* Tener una cuenta registrada en [Expo.dev](https://expo.dev/).

### Pasos de Configuración y Compilación

### 1. Instalar EAS CLI

Instala la herramienta de consola de EAS de forma global:

```
npm install -g eas-cli

```

#### 2. Iniciar Sesión en Expo

Autentícate en tu cuenta de Expo desde la terminal:

```
eas login

```

#### 3. Configurar el Proyecto con EAS

Inicializa la configuración de EAS en la raíz del proyecto:

```
eas build:configure

```

### Archivos de Configuración



### 1. Configuración en `app.json`

Asegúrate de definir el ID único de la aplicación (`package`), la versión del proyecto y el número de compilación (`versionCode`).

> 

```
{
  "expo": {
    "name": "TurismoApp",
    "slug": "turismo-app",
    "version": "1.0.0",
    "orientation": "portrait",
    "icon": "./assets/icon.png",
    "userInterfaceStyle": "light",
    "android": {
      "package": "com.agusc92.turismoapp",
      "versionCode": 1,
      "adaptiveIcon": {
        "foregroundImage": "./assets/android-icon-foreground.png",
        "backgroundColor": "#FFFFFF"
      }
    }
  }
}

```

### 2. Configuración en `eas.json`

Abre o edita el archivo `eas.json` en la raíz de tu proyecto. Configuraremos dos perfiles distintos: `preview` (para generar **.apk**) y `production` (para generar **.aab**).

```
{
  "cli": {
    "version": ">= 9.0.0"
  },
  "build": {
    "development": {
      "developmentClient": true,
      "distribution": "internal"
    },
    "preview": {
      "distribution": "internal",
      "android": {
        "buildType": "apk"
      }
    },
    "production": {
      "android": {
        "buildType": "app-bundle"
      }
    }
  }
}

```

## Comandos de Compilación

### Opción A: Compilar APK (Pruebas / Instalación Directa)

Para generar el archivo `.apk` instalable en teléfonos móviles para pruebas internas o distribución directa:

```
eas build --platform android --profile preview

```

### Opción B: Compilar AAB (Despliegue a Google Play Store)

Para generar el paquete `.aab` que subirás a la consola de desarrolladores de Google Play (*Google Play Console*):

```
eas build --platform android --profile production

```

## Resumen de Diferencias

| Formato | Archivo Generado | Perfil de EAS | Uso Principal | 
| ----- | ----- | ----- | ----- | 
| **APK** | `.apk` | `preview` | Instalación manual en dispositivos físicos (testing / demo). | 
| **AAB** | `.aab` | `production` | Subir a Google Play Console (publicación oficial). | 

## Flujo durante la Compilación en la Nube

1. **Gestión de Claves (Keystore):** Si es la primera vez que ejecutas la compilación para producción o preview, Expo te preguntará si deseas que gestionen la *Android Keystore*. Acepta (`Generate new keystore`).

2. **Proceso:** Tu código se empaquetará y enviará a los servidores de Expo para procesarse.

3. **Descarga:** Al finalizar, el terminal te dará una URL para descargar el archivo (`.apk` o `.aab`) según la opción seleccionada.
