import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Ionicons } from '@expo/vector-icons';
import { BackButton } from '../../constants/Styles';
import UbicacionDetalles from '../../components/UbicacionDetalles';
import ContactoDetalles from '../../components/ContactoDetalles';
export default function ActividadDetalle() {
    const { id } = useLocalSearchParams();
    const { data: actividad, loading } = useFetchDetalle('actividades', id);

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

                    <ContactoDetalles item={actividad} />

                    <UbicacionDetalles direccion={actividad.direccion} />
                </View>


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
});
