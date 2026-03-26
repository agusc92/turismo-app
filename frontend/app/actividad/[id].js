import React from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { actividades } from '../../assets/mokup';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../../constants/Styles';

export default function ActividadDetalle() {
    const { id } = useLocalSearchParams();
    const actividadId = parseInt(id, 10);

    const actividad = actividades.find(a => a.idActividad === actividadId);

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
                        {actividad.caracteristicas}
                    </Text>

                    <Text style={styles.sectionTitle}>Contacto</Text>
                    
                    <Text style={styles.paragraphText}>
                        {actividad.redesSociales}
                    </Text>

                    <Text style={styles.sectionTitle}>Ubicación</Text>
                    
                    <View style={styles.locationContainer}>
                        <Ionicons name="location-outline" size={22} color="#2C1B4D" style={styles.iconLocation} />
                        <Text style={styles.locationText}>
                            {direccionText}
                        </Text>
                    </View>
                </View>

                <View style={styles.mapWrap}>
                    <Image
                        source={{ uri: 'https://necochea.tur.ar/wp-content/uploads/2021/11/necochea-mapa.jpg' }}
                        style={styles.mapImage}
                        resizeMode="cover"
                    />
                </View>
            </ScrollView>

            <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
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
    backButton: {
        position: 'absolute',
        top: 50,
        left: 20,
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(255, 255, 255, 0.7)',
        justifyContent: 'center',
        alignItems: 'center',
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
