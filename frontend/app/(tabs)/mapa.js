import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ActivityIndicator } from 'react-native';
import WebView from 'react-native-webview';
import * as Location from 'expo-location';

export default function Mapa() {
    const [location, setLocation] = useState(null);
    const [errorMsg, setErrorMsg] = useState(null);
    const [loading, setLoading] = useState(true);

    // Coordenadas fijas de reserva (Centro de Necochea)
    const fallbackLocation = {
        latitude: -38.552185,
        longitude: -58.736214,
    };

    useEffect(() => {
        (async () => {
            let { status } = await Location.requestForegroundPermissionsAsync();
            if (status !== 'granted') {
                setErrorMsg('Permisos denegados. Mostrando ubicación inicial.');
                setLocation(fallbackLocation);
                setLoading(false);
                return;
            }

            try {
                let currentPos = await Location.getCurrentPositionAsync({});
                setLocation({
                    latitude: currentPos.coords.latitude,
                    longitude: currentPos.coords.longitude,
                });
            } catch (error) {
                console.warn("No se pudo obtener la ubicación exacta.", error);
                // Fallback a coordenadas de Necochea
                setLocation(fallbackLocation);
            } finally {
                setLoading(false);
            }
        })();
    }, []);

    if (loading || !location) {
        return (
            <View style={styles.centerContainer}>
                <ActivityIndicator size="large" color="#2C1B4D" />
                <Text style={styles.loadingText}>Obteniendo GPS en vivo...</Text>
            </View>
        );
    }

    // El motor HTML5 + Leaflet + OpenStreetMap 
    // Inyectamos dinámicamente tu latitud y longitud obtenida por el Hardware celular
    const mapHtml = `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <style>
            body { margin: 0; padding: 0; background-color: #FAFAFD;}
            #map { height: 100vh; width: 100vw; }
            /* Icono circular customizado para "Mi Ubicacion" */
            .my-location-icon {
                background-color: #2C1B4D;
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(0,0,0,0.5);
            }
        </style>
    </head>
    <body>
        <div id="map"></div>
        <script>
            // 1. Inicializar el mapa centrado en ${location.latitude}, ${location.longitude}
            var map = L.map('map', {
                zoomControl: false // Ocultamos el nativo para hacer lugar si se desea o se ve limpio
            }).setView([${location.latitude}, ${location.longitude}], 15);
            
            // 2. Agregar los cuadrantes desde CartoDB (basados en OSM) que NO bloquean tráfico genérico
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // 3. Crear el iconito especial con tu esquema de color
            var customIcon = L.divIcon({
                className: 'my-location-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // 4. Clavar el pin interactivo en donde el componente nativo de React obtuvo el radar
            var marker = L.marker([${location.latitude}, ${location.longitude}], { icon: customIcon }).addTo(map);
            marker.bindPopup("<div style='text-align:center;'><b>📍 Tú estás aquí</b></div>").openPopup();
            
            // Si el día de mañana inyectas más pines, se escribiran justo a continuación usando un ForLoop desde el App React!
        </script>
    </body>
    </html>
    `;

    return (
        <View style={styles.container}>
            <WebView 
                originWhitelist={['*']}
                source={{ 
                    html: mapHtml, 
                    baseUrl: 'https://openstreetmap.org' 
                }}
                userAgent="TurismoAppNecochea/1.0 (Mobile WebView)"
                style={styles.map}
                // Previene que rebote feo en iOS o muestre franjas blancas en Android
                bounces={false}
                scrollEnabled={false}
                showsHorizontalScrollIndicator={false}
                showsVerticalScrollIndicator={false}
            />
            {errorMsg && (
                <View style={styles.errorBox}>
                    <Text style={styles.errorText}>{errorMsg}</Text>
                </View>
            )}
        </View>
    );
}

const styles = StyleSheet.create({
    container: { 
        flex: 1, 
        backgroundColor: '#FAFAFD' 
    },
    centerContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#FAFAFD' 
    },
    loadingText: {
        marginTop: 10,
        fontSize: 16,
        color: '#2C1B4D'
    },
    map: {
        flex: 1,
    },
    errorBox: {
        position: 'absolute',
        bottom: 20,
        alignSelf: 'center',
        backgroundColor: 'rgba(0, 0, 0, 0.7)',
        paddingHorizontal: 20,
        paddingVertical: 10,
        borderRadius: 20,
    },
    errorText: {
        color: 'white',
        fontWeight: 'bold',
        textAlign: 'center',
        fontSize: 12
    }
});
