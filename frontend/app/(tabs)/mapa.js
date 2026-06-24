import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ActivityIndicator, TouchableOpacity, ScrollView } from 'react-native';
import WebView from 'react-native-webview';
import * as Location from 'expo-location';
import { API_URL } from '../../api';

// Categorías con color y emoji para los pines del mapa
const CATEGORIAS = [
    { key: 'gastronomicos', label: 'Gastronomía', color: '#E05A2B', emoji: '🍽️', ruta: 'gastronomicos' },
    { key: 'balnearios', label: 'Balnearios', color: '#0EA5E9', emoji: '🏖️', ruta: 'balnearios' },
    { key: 'alojamientos', label: 'Alojamientos', color: '#7C3AED', emoji: '🏨', ruta: 'alojamientos' },
    { key: 'actividades', label: 'Actividades', color: '#16A34A', emoji: '🎯', ruta: 'actividades' },
    { key: 'complejos', label: 'Complejos', color: '#D97706', emoji: '🏕️', ruta: 'complejos' },
];

// Radio de búsqueda en km (muestra todos los lugares dentro de este radio)
const RADIO_KM = 10;

function calcularDistanciaKm(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLon = ((lon2 - lon1) * Math.PI) / 180;
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos((lat1 * Math.PI) / 180) *
        Math.cos((lat2 * Math.PI) / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

export default function Mapa() {
    const [location, setLocation] = useState(null);
    const [errorMsg, setErrorMsg] = useState(null);
    const [loadingLocation, setLoadingLocation] = useState(true);
    const [loadingPlaces, setLoadingPlaces] = useState(true);
    const [lugares, setLugares] = useState([]);
    const [filtros, setFiltros] = useState(
        Object.fromEntries(CATEGORIAS.map(c => [c.key, true]))
    );

    const fallbackLocation = { latitude: -38.552185, longitude: -58.736214 };

    // ─── 1. Obtener GPS ────────────────────────────────────────────────────────
    useEffect(() => {
        (async () => {
            let { status } = await Location.requestForegroundPermissionsAsync();
            if (status !== 'granted') {
                setErrorMsg('Permisos de ubicación denegados. Mostrando Necochea.');
                setLocation(fallbackLocation);
                setLoadingLocation(false);
                return;
            }
            try {
                let pos = await Location.getCurrentPositionAsync({});
                setLocation({ latitude: pos.coords.latitude, longitude: pos.coords.longitude });
            } catch {
                setLocation(fallbackLocation);
            } finally {
                setLoadingLocation(false);
            }
        })();
    }, []);

    // ─── 2. Fetchear lugares de la API ─────────────────────────────────────────
    useEffect(() => {
        const fetchTodos = async () => {
            try {
                const resultados = await Promise.allSettled(
                    CATEGORIAS.map(cat =>
                        fetch(`${API_URL}/${cat.ruta}`)
                            .then(r => r.json())
                            .then(data => {
                                const items = Array.isArray(data) ? data : (data.data ?? []);
                                return items.map(item => ({
                                    ...item,
                                    _categoria: cat.key,
                                    _color: cat.color,
                                    _emoji: cat.emoji,
                                    _label: cat.label,
                                }));
                            })
                    )
                );
                const todos = resultados
                    .filter(r => r.status === 'fulfilled')
                    .flatMap(r => r.value)
                    .filter(l => l.latitud != null && l.longitud != null);
                setLugares(todos);
            } catch (err) {
                console.warn('Error cargando lugares:', err);
            } finally {
                setLoadingPlaces(false);
            }
        };
        fetchTodos();
    }, []);

    const isLoading = loadingLocation || loadingPlaces;

    if (isLoading) {
        return (
            <View style={styles.centerContainer}>
                <ActivityIndicator size="large" color="#2C1B4D" />
                <Text style={styles.loadingText}>
                    {loadingLocation ? 'Obteniendo GPS...' : 'Cargando lugares...'}
                </Text>
            </View>
        );
    }

    // ─── 3. Filtrar por radio y por categoría activa ───────────────────────────
    const lugaresFiltrados = lugares.filter(l => {
        if (!filtros[l._categoria]) return false;
        const dist = calcularDistanciaKm(
            location.latitude, location.longitude,
            parseFloat(l.latitud), parseFloat(l.longitud)
        );
        return dist <= RADIO_KM;
    });

    // ─── 4. Construir el HTML con Leaflet ──────────────────────────────────────
    const markersJS = lugaresFiltrados.map(l => {
        const nombre = (l.nombre || 'Sin nombre').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        const direccion = (l.direccion || '').replace(/'/g, "\\'");
        const emoji = l._emoji;
        const color = l._color;
        const lat = parseFloat(l.latitud);
        const lng = parseFloat(l.longitud);

        return `
            (function(){
                var icon = L.divIcon({
                    className: '',
                    html: '<div style="background:${color};width:34px;height:34px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><span style="transform:rotate(45deg);font-size:15px;display:block;text-align:center;line-height:28px;">${emoji}</span></div>',
                    iconSize: [34,34],
                    iconAnchor: [17,34],
                    popupAnchor: [0,-36]
                });
                L.marker([${lat}, ${lng}], {icon: icon})
                    .addTo(map)
                    .bindPopup('<div style="font-family:sans-serif;min-width:150px"><b style="color:${color};font-size:14px;">${nombre}</b><br><span style="font-size:12px;color:#555;">${direccion}</span><br><span style="font-size:11px;background:${color};color:white;padding:2px 6px;border-radius:10px;">${emoji} ${l._label}</span></div>');
            })();
        `;
    }).join('\n');

    const mapHtml = `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <style>
            body { margin: 0; padding: 0; background: #FAFAFD; }
            #map { height: 100vh; width: 100vw; }
            .pulse-ring {
                border: 4px solid #2C1B4D;
                border-radius: 50%;
                animation: pulse 1.8s ease-out infinite;
                position: absolute;
                top: -8px; left: -8px;
                width: 36px; height: 36px;
                opacity: 0;
            }
            @keyframes pulse {
                0%   { transform: scale(0.5); opacity: 0.8; }
                100% { transform: scale(2.0); opacity: 0; }
            }
            .my-dot { position: relative; width: 20px; height: 20px; }
            .my-dot-inner {
                background: #2C1B4D;
                width: 20px; height: 20px;
                border-radius: 50%;
                border: 3px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.4);
            }
        </style>
    </head>
    <body>
        <div id="map"></div>
        <script>
            var map = L.map('map', { zoomControl: true }).setView([${location.latitude}, ${location.longitude}], 14);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Pin del usuario con animación de pulso
            var userIcon = L.divIcon({
                className: '',
                html: '<div class="my-dot"><div class="pulse-ring"></div><div class="my-dot-inner"></div></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
                popupAnchor: [0, -14]
            });
            L.marker([${location.latitude}, ${location.longitude}], { icon: userIcon, zIndexOffset: 1000 })
                .addTo(map)
                .bindPopup('<b>📍 usted está aquí</b>')
                .openPopup();

            // Círculo de radio de búsqueda
            L.circle([${location.latitude}, ${location.longitude}], {
                radius: ${RADIO_KM * 1000},
                color: '#2C1B4D',
                fillColor: '#2C1B4D',
                fillOpacity: 0.04,
                weight: 1.5,
                dashArray: '6 4'
            }).addTo(map);

            // Pins de lugares cercanos
            ${markersJS}
        </script>
    </body>
    </html>
    `;

    const totalCercanos = lugaresFiltrados.length;

    return (
        <View style={styles.container}>
            {/* Mapa */}
            <WebView
                originWhitelist={['*']}
                source={{ html: mapHtml, baseUrl: 'https://openstreetmap.org' }}
                userAgent="TurismoAppNecochea/1.0 (Mobile WebView)"
                style={styles.map}
                bounces={false}
                scrollEnabled={false}
                showsHorizontalScrollIndicator={false}
                showsVerticalScrollIndicator={false}
            />

            {/* Badge de resultados */}
            <View style={styles.badgeBox}>
                <Text style={styles.badgeText}>
                    📍 {totalCercanos} lugar{totalCercanos !== 1 ? 'es' : ''} a {RADIO_KM} km
                </Text>
            </View>

            {/* Filtros de categoría */}
            <View style={styles.filtersWrap}>
                <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.filtersScroll}>
                    {CATEGORIAS.map(cat => {
                        const activo = filtros[cat.key];
                        return (
                            <TouchableOpacity
                                key={cat.key}
                                style={[styles.filterChip, activo ? { backgroundColor: cat.color, borderColor: cat.color } : styles.filterChipOff]}
                                onPress={() => setFiltros(f => ({ ...f, [cat.key]: !f[cat.key] }))}
                                activeOpacity={0.75}
                            >
                                <Text style={styles.filterEmoji}>{cat.emoji}</Text>
                                <Text style={[styles.filterLabel, activo ? styles.filterLabelOn : styles.filterLabelOff]}>
                                    {cat.label}
                                </Text>
                            </TouchableOpacity>
                        );
                    })}
                </ScrollView>
            </View>

            {/* Banner de error GPS */}
            {errorMsg && (
                <View style={styles.errorBox}>
                    <Text style={styles.errorText}>{errorMsg}</Text>
                </View>
            )}
        </View>
    );
}

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#FAFAFD' },
    centerContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#FAFAFD',
    },
    loadingText: {
        marginTop: 12,
        fontSize: 16,
        color: '#2C1B4D',
        fontWeight: '600',
    },
    map: { flex: 1 },
    badgeBox: {
        position: 'absolute',
        top: 16,
        alignSelf: 'center',
        backgroundColor: 'rgba(44, 27, 77, 0.88)',
        paddingHorizontal: 16,
        paddingVertical: 7,
        borderRadius: 20,
        shadowColor: '#000',
        shadowOpacity: 0.2,
        shadowRadius: 6,
        shadowOffset: { width: 0, height: 2 },
        elevation: 5,
    },
    badgeText: {
        color: 'white',
        fontWeight: '700',
        fontSize: 13,
    },
    filtersWrap: {
        position: 'absolute',
        bottom: 16,
        left: 0,
        right: 0,
    },
    filtersScroll: {
        paddingHorizontal: 12,
        gap: 8,
        flexDirection: 'row',
    },
    filterChip: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingHorizontal: 12,
        paddingVertical: 8,
        borderRadius: 20,
        borderWidth: 2,
        backgroundColor: 'white',
        shadowColor: '#000',
        shadowOpacity: 0.12,
        shadowRadius: 4,
        elevation: 3,
        gap: 4,
    },
    filterChipOff: {
        borderColor: '#ddd',
        backgroundColor: 'white',
    },
    filterEmoji: { fontSize: 15 },
    filterLabel: { fontSize: 12, fontWeight: '700' },
    filterLabelOn: { color: 'white' },
    filterLabelOff: { color: '#888' },
    errorBox: {
        position: 'absolute',
        top: 60,
        alignSelf: 'center',
        backgroundColor: 'rgba(200, 50, 50, 0.85)',
        paddingHorizontal: 16,
        paddingVertical: 8,
        borderRadius: 16,
    },
    errorText: {
        color: 'white',
        fontSize: 12,
        fontWeight: '600',
    },
});
