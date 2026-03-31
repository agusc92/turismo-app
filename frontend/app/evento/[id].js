import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { API_URL } from '../../api';
import { Colors, BackButton } from '../../constants/Styles';
import { Ionicons } from '@expo/vector-icons';

export default function EventoDetalle() {
    const { id } = useLocalSearchParams();
    const [evento, setEvento] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchEvento = async () => {
            try {
                const response = await fetch(`${API_URL}/eventos/${id}`);
                const data = await response.json();
                if (response.ok) {
                    setEvento(data);
                } else {
                    setEvento(null);
                }
            } catch (error) {
                console.error("Error fetching evento:", error);
                setEvento(null);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchEvento();
        }
    }, [id]);

    if (loading) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'Cargando...' }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!evento) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>El evento no existe.</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ headerShown: false }} />
            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    {evento.imagen ? (
                        <Image source={{ uri: evento.imagen }} style={styles.image} resizeMode="cover" />
                    ) : (
                        <View style={styles.placeholderImage}></View>
                    )}
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{evento.nombre}</Text>

                    <View style={styles.dateRow}>
                        <Ionicons name="calendar-outline" size={22} color="#2C1B4D" style={styles.icon} />
                        <Text style={styles.dateLabel}>Inicio</Text>
                        <Text style={styles.dateValue}>{evento.fecha}</Text>
                    </View>

                    <View style={styles.descriptionContainer}>
                        <Text style={styles.description}>{evento.descripcion}</Text>
                    </View>

                    <Text style={styles.sectionTitle}>Ubicación</Text>
                </View>

                <View style={styles.locationContainer}>
                    <Ionicons name="location-outline" size={22} color="#2C1B4D" style={styles.iconLocation} />
                    <Text style={styles.locationText}>{evento.direccion}</Text>
                </View>

                <View style={styles.mapWrap}>
                    <Image
                        source={{ uri: 'https://necochea.tur.ar/wp-content/uploads/2021/11/necochea-mapa.jpg' }} // Placeholder genérico para el mapa visual
                        style={styles.mapImage}
                        resizeMode="cover"
                    />
                </View>

            </ScrollView>

            {/* Fixed Back Button */}
            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
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
    placeholderImage: {
        width: '100%',
        height: '100%',
        backgroundColor: '#e0e0e0',
    },
    contentContainer: {
        paddingHorizontal: 20,
        paddingTop: 20,
        backgroundColor: '#fff',
    },
    title: {
        fontSize: 28,
        fontWeight: '900',
        color: '#2C1B4D',
        marginBottom: 25,
        textTransform: 'capitalize',
        letterSpacing: -0.5,
    },
    dateRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 30,
    },
    icon: {
        marginRight: 15,
    },
    dateLabel: {
        fontSize: 16,
        color: '#555',
        marginRight: 10,
    },
    dateValue: {
        fontSize: 16,
        color: '#444',
    },
    descriptionContainer: {
        marginBottom: 30,
    },
    description: {
        fontSize: 16,
        color: '#555',
        lineHeight: 24,
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
        paddingHorizontal: 20,
        paddingVertical: 15,
        marginBottom: 20,
    },
    iconLocation: {
        marginRight: 10,
    },
    locationText: {
        fontSize: 16,
        color: '#444',
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
