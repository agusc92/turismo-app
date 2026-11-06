# Guía de Compilación de Android

[← Volver al README principal](../README.md)

Esta guía explica cómo generar un archivo **.apk** para pruebas o un **.aab** para publicar en Google Play mediante **EAS Build** (Expo Application Services).

## Requisitos previos

- Tener una cuenta registrada en [Expo.dev](https://expo.dev/).

## Configuración y compilación

### 1. Instalar EAS CLI

```bash
npm install -g eas-cli
```

### 2. Iniciar sesión en Expo

```bash
eas login
```

### 3. Configurar el proyecto con EAS

Ejecuta el comando en la raíz del proyecto:

```bash
eas build:configure
```

## Archivos de configuración

### `app.json`

Define el identificador único, la versión y el número de compilación de Android (`versionCode`):

```json
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

### `eas.json`

El perfil `preview` genera un **.apk** para pruebas y el perfil `production` genera un **.aab** para Google Play:

```json
{
  "cli": {
    "version": ">= 19.0.5",
    "appVersionSource": "remote"
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
      "autoIncrement": true,
      "android": {
        "buildType": "app-bundle"
      }
    }
  },
  "submit": {
    "production": {}
  }
}
```

## Comandos de compilación

### APK para pruebas

```bash
eas build --platform android --profile preview
```

### AAB para Google Play

```bash
eas build --platform android --profile production
```

## Diferencias entre APK y AAB

| Formato | Archivo | Perfil de EAS | Uso principal |
| --- | --- | --- | --- |
| **APK** | `.apk` | `preview` | Instalación manual y pruebas en dispositivos físicos. |
| **AAB** | `.aab` | `production` | Publicación en Google Play Console. |

## Flujo durante la compilación en la nube

1. **Keystore:** en la primera compilación, acepta que Expo genere y gestione la Android Keystore (`Generate new keystore`).
2. **Proceso:** EAS envía el código a los servidores de Expo para generar el paquete.
3. **Descarga:** al finalizar, la terminal proporciona una URL para descargar el `.apk` o `.aab`.
