import React, { useState, useEffect } from "react";
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, useRouter, Stack } from "expo-router";
import { WebView } from 'react-native-webview';
import { API_URL } from "../../api";
import { Colors, BackButton } from "../../constants/Styles";
import { Ionicons, FontAwesome5, MaterialCommunityIcons } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';

export default function AlojamientoDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const insets = useSafeAreaInsets();
    const [item, setItem] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchAlojamiento = async () => {
            try {
                const response = await fetch(`${API_URL}/alojamientos/${id}`);
                const data = await response.json();
                if (response.ok) {
                    setItem(data);
                } else {
                    setItem(null);
                }
            } catch (error) {
                console.error("Error fetching alojamiento:", error);
                setItem(null);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchAlojamiento();
        }
    }, [id]);

    if (loading) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!item) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>Alojamiento no encontrado</Text>
            </View>
        );
    }
    let fb = '', ig = '', tw = '';
    if (item.redesSociales) {
        const redesList = item.redesSociales.split('|').map(r => r.trim());
        redesList.forEach(red => {
            if (red.toLowerCase().startsWith('fb:')) fb = red.slice(3).trim();
            if (red.toLowerCase().startsWith('ig:')) ig = red.slice(3).trim();
            if (red.toLowerCase().startsWith('x:')) tw = red.slice(2).trim();
        });
    }

    const itemId = item.id || item.idAlojamiento;
    // Imagen de portada obtenida del backend si existe o un placeholder
    const imageUrl = item.imagen || `https://picsum.photos/seed/${itemId + 10}/800/600`;

    return (
        <View style={styles.container}>
            <ScrollView
                style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}
            >
                {/* Ocultamos el header estándar para poner un botón de atrás encima de la imagen */}
                <Stack.Screen options={{ headerShown: false }} />

                <View style={styles.imageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.headerImage} />

                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{item.nombre}</Text>
                    <Text style={styles.subtitle}>{item.tipo}</Text>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Periodo de Apertura</Text>
                        <Text style={styles.sectionText}>{item.periodoApertura}</Text>
                    </View>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Mascotas</Text>
                        <Text style={styles.sectionText}>{item.mascotas}</Text>
                    </View>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Contacto</Text>

                        {item.telefono ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-whatsapp" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.telefono)}</Text>
                            </View>
                        ) : null}

                        {ig ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-instagram" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{ig}</Text>
                            </View>
                        ) : null}

                        {fb ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-facebook" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{fb}</Text>
                            </View>
                        ) : null}

                        {item.tiendaOnline ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="globe-outline" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.tiendaOnline).replace(/https?:\/\//, '').trim()}</Text>
                            </View>
                        ) : null}
                    </View>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Ubicación</Text>
                        <View style={styles.contactRow}>
                            <Ionicons name="location-outline" size={20} color={Colors.textColor} style={styles.contactIcon} />
                            <Text style={styles.contactText}>{item.direccion ? String(item.direccion) : 'No especificada'}</Text>
                        </View>
                        {item.direccion ? (
                            <WebView
                                source={{ html: `<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><style>body { margin: 0; padding: 0; }</style><iframe width="100%" height="100%" frameborder="0" style="border:0;" src="https://www.google.com/maps?q=${encodeURIComponent(item.direccion + ', Necochea, Argentina')}&output=embed" allowfullscreen></iframe>` }}
                                style={styles.mapImage}
                                scrollEnabled={false}
                                bounces={false}
                                showsVerticalScrollIndicator={false}
                                showsHorizontalScrollIndicator={false}
                            />
                        ) : null}
                    </View>
                </View>
            </ScrollView>
            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
            </TouchableOpacity>
        </View>

    );
}

const styles = StyleSheet.create({
    pageContent: {
        flex: 1,
    },
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    centered: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    errorText: {
        fontSize: 16,
        color: Colors.textColor,
    },
    imageContainer: {
        width: '100%',
        height: 250,
        position: 'relative',
    },
    headerImage: {
        width: '100%',
        height: '100%',
    },
    // backButton: {
    //     position: 'absolute',
    //     top: 10,
    //     left: 10,
    //     width: 40,
    //     height: 40,
    //     borderRadius: 20,
    //     backgroundColor: 'rgba(255, 255, 255, 0.7)',
    //     justifyContent: 'center',
    //     alignItems: 'center',
    // },
    contentContainer: {
        padding: 24,
    },
    title: {
        fontSize: 26,
        fontWeight: '900',
        color: Colors.textColor,
        marginBottom: 8,
    },
    subtitle: {
        fontSize: 16,
        color: '#7B758E',
        marginBottom: 24,
    },
    section: {
        marginBottom: 32,
    },
    sectionTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginBottom: 12,
    },
    sectionText: {
        fontSize: 15,
        color: '#554E66',
        lineHeight: 22,
    },
    contactRow: {
        gap: 8,
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 16,
    },
    contactText: {
        fontSize: 15,
        color: '#554E66',
        flex: 1,
    },
    mapImage: {
        width: '100%',
        height: 180,
        borderRadius: 12,
        marginTop: 16,
    }
});
