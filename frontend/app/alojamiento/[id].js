import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, useRouter, Stack } from "expo-router";
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Colors, BackButton } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import SeccionDetalles from "../../components/SeccionDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";
import ContactoDetalles from "../../components/ContactoDetalles";

export default function AlojamientoDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const { data: item, loading } = useFetchDetalle('alojamientos', id);

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

                    <SeccionDetalles titulo="Periodo de Apertura" subtitulo={item.periodoApertura} />

                    <SeccionDetalles titulo="Mascotas" subtitulo={item.mascotas} />

                    <ContactoDetalles item={item} />

                    <UbicacionDetalles direccion={item.direccion} />
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
    }
});
