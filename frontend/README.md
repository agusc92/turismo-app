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

## Índice

- [Arquitectura](docs/ARQUITECTURA.md)
- [Integración con la API](docs/INTEGRACION_API.md)
- [Estilos y diseño visual](docs/ESTILOS_Y_DISENO.md)
- [Configuración de entorno y de la aplicación](docs/CONFIGURACION_ENTORNO.md)
- [Guía de compilación](docs/GUIA_COMPILACION.md)

## Puesta en Marcha

Para levantar el entorno de desarrollo de la aplicación móvil utilizando Expo, sigue estos pasos:

1. **Navega a la raíz del proyecto móvil:**

    ```bash
    cd turismo-app
    ```

2. **Configura la conexión con la API:** en `api.js`, asegúrate de que la IP corresponda a la de tu computadora en la red local.

    ```javascript
    // api.js
    export const IP_ADDRESS = "192.168.1.7"; // Cambia esto por tu IPv4 local
    export const API_URL = `http://${IP_ADDRESS}:8000/api`;
    ```

    Si pruebas en un dispositivo físico, utiliza la IP de tu computadora (por ejemplo, `192.168.x.x`) y no `localhost`.

3. **Instala las dependencias:**

    ```bash
    npm install
    ```

4. **Levanta el servidor de desarrollo de Expo:**

    ```bash
    npx expo start
    ```

    `npx expo start` inicia Metro Bundler y genera un código QR para abrir la aplicación con **Expo Go**. El celular y la computadora deben estar en la misma red Wi-Fi. En un emulador, presiona `a` para Android o `i` para iOS.

    Para iniciar limpiando la caché, utiliza:

    ```bash
    npx expo start -c
    ```
