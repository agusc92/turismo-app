# Estilos y diseño visual

[← Volver al README principal](../README.md)

La interfaz utiliza el sistema de estilos de **React Native**. No se emplean hojas CSS globales para las pantallas nativas: cada componente define sus estilos mediante `StyleSheet.create`, y los valores compartidos se centralizan en `constants/Styles.js`.

## Paleta de colores

La paleta principal se exporta desde `constants/Styles.js` como `Colors`:

| Uso | Constante | Color |
| --- | --- | --- |
| Fondo general | `backgroundLight` | `#F7F6FC` |
| Texto principal | `textColor` | `#36274E` |
| Texto secundario | `textColorLight` | `#8A819C` |
| Contenedores de datos | `dataContainerColor` | `#F7F7FA` |
| Fondo de filtros | `filterBackgroundColor` | `#F0EFFF` |
| Títulos de detalle | `TitleDetalles.color` | `#31204D` |

También se utilizan colores auxiliares para estados y elementos específicos: blanco (`#FFFFFF`) para tarjetas y superficies, grises para textos o placeholders y `#007AFF` para controles activos. Los componentes pueden definir colores locales cuando dependen del contexto.

## Tipografías

La familia principal es **Gotham**, almacenada en `assets/fuentes/` y cargada al iniciar la aplicación mediante `useFonts` de `expo-font`:

| Nombre | Archivo | Uso habitual |
| --- | --- | --- |
| `Gotham-Ultra` | `Gotham-Ultra.otf` | Encabezados destacados |
| `Gotham-Black` | `Gotham-Black.otf` | Títulos principales |
| `Gotham-Bold` | `GOTHMBOL.ttf` | Títulos, etiquetas y botones |
| `Gotham-Medium` | `GOTHMMED.ttf` | Texto con énfasis y tarjetas |
| `Gotham-Book` | `GOTHMBOK.ttf` | Texto descriptivo y secundario |
| `Gotham-Light` | `GOTHMLIG.ttf` | Texto liviano |

El layout principal mantiene visible el `SplashScreen` hasta que las fuentes terminan de cargar para evitar un cambio temporal de tipografía.

## Herramientas y organización

- **`StyleSheet.create` de React Native:** organiza los estilos de pantallas y componentes.
- **`constants/Styles.js`:** concentra la paleta y estilos reutilizables.
- **Estilos dinámicos e inline:** permiten cambiar colores según el estado de filtros o categorías.
- **`expo-linear-gradient`:** crea degradados sobre imágenes para mejorar la legibilidad.
- **Leaflet:** carga `leaflet.css` y reglas CSS inline para marcadores, ventanas emergentes y controles del mapa.

Los valores repetidos deben incorporarse a `constants/Styles.js`; los estilos exclusivos deben permanecer junto al componente correspondiente.
