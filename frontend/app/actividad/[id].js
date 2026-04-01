import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { WebView } from 'react-native-webview';
import { API_URL } from '../../api';
import { Ionicons } from '@expo/vector-icons';
import { Colors, BackButton } from '../../constants/Styles';

export default function ActividadDetalle() {
    const { id } = useLocalSearchParams();
    const [actividad, setActividad] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchActividad = async () => {
            try {
                const response = await fetch(`${API_URL}/actividades/${id}`);
                const data = await response.json();
                if (response.ok) {
                    setActividad(data);
                } else {
                    setActividad(null);
                }
            } catch (error) {
                console.error("Error fetching actividad:", error);
                setActividad(null);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchActividad();
        }
    }, [id]);

    if (loading) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!actividad) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>La actividad no existe.</Text>
            </View>
        );
    }

    // Usamos una imagen genérica si no tiene URL propia
    const fallbackImageUrl = 'https://dummyimage.com/1000x800/ffb347/ffffff.png&text=' + encodeURIComponent(actividad.nombre);
    let imageUrl = fallbackImageUrl;
    if (actividad.imagen && actividad.imagen.startsWith('http')) {
        imageUrl = actividad.imagen;
    }

    // Helper para formatear la dirección
    const direccionText = actividad.direccion ? `Calle ${actividad.direccion}` : 'Dirección no disponible';

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ headerShown: false }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} resizeMode="cover" />
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{actividad.nombre}</Text>

                    <Text style={styles.paragraphText}>
                        {actividad.descripcion}
                    </Text>

                    <Text style={styles.sectionTitle}>Contacto</Text>

                    <Text style={styles.paragraphText}>
                        {actividad.redes_sociales}
                    </Text>

                    <Text style={styles.sectionTitle}>Ubicación</Text>

                    <View style={styles.locationContainer}>
                        <Ionicons name="location-outline" size={22} color="#2C1B4D" style={styles.iconLocation} />
                        <Text style={styles.locationText}>
                            {direccionText}
                        </Text>
                    </View>
                </View>

                {actividad.direccion ? (
                    <View style={styles.mapWrap}>
                        <WebView
                            source={{ html: `<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><style>body { margin: 0; padding: 0; }</style><iframe width="100%" height="100%" frameborder="0" style="border:0;" src="https://www.google.com/maps?q=${encodeURIComponent(actividad.direccion + ', Necochea, Argentina')}&output=embed" allowfullscreen></iframe>` }}
                            style={styles.mapImage}
                            scrollEnabled={false}
                            bounces={false}
                            showsVerticalScrollIndicator={false}
                            showsHorizontalScrollIndicator={false}
                        />
                    </View>
                ) : null}
            </ScrollView>

            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color="#000" />
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    errorContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f5f5f5',
    },
    errorText: {
        fontSize: 18,
        color: '#333',
    },
    pageContent: {
        flex: 1,
    },
    headerImageContainer: {
        position: 'relative',
        width: '100%',
        height: 320,
    },
    image: {
        width: '100%',
        height: '100%',
    },
    contentContainer: {
        paddingHorizontal: 20,
        paddingTop: 25,
        backgroundColor: '#fff',
    },
    title: {
        fontSize: 32,
        fontWeight: '900',
        color: '#2C1B4D',
        marginBottom: 20,
        textTransform: 'capitalize',
        letterSpacing: -0.5,
    },
    paragraphText: {
        fontSize: 15,
        color: '#666',
        lineHeight: 24,
        marginBottom: 30,
    },
    sectionTitle: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#2C1B4D',
        marginBottom: 15,
    },
    locationContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 30,
    },
    iconLocation: {
        marginRight: 10,
    },
    locationText: {
        fontSize: 15,
        color: '#666',
        flex: 1,
    },
    mapWrap: {
        marginHorizontal: 20,
        marginBottom: 40,
        height: 200,
        borderRadius: 16,
        overflow: 'hidden',
    },
    mapImage: {
        width: '100%',
        height: '100%',
        backgroundColor: '#ddd',
    }
});
