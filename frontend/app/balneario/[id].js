import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, useRouter, Stack } from "expo-router";
import { Colors, BackButton } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import SeccionDetalles from "../../components/SeccionDetalles";
import { useFetchDetalle } from "../hooks/useFetchDetalle";
import ContactoDetalles from "../../components/ContactoDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";

export default function BalnearioDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const insets = useSafeAreaInsets();

    const { data: item, loading } = useFetchDetalle('balnearios', id);

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
                <Text style={styles.errorText}>Balneario no encontrado</Text>
            </View>
        );
    }

    const itemId = item.id || item.idBalneario;
    // Imagen de portada de la API si la tiene, en su defecto la temporal
    const imageUrl = item.imagen || `https://picsum.photos/seed/${itemId + 30}/800/600`;

    // Formatear titulo
    const displayTitle = item.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');

    // Formatear servicios
    const detallesStr = item.servicios
        ? item.servicios.split('|').map(s => s.trim().charAt(0).toUpperCase() + s.trim().slice(1)).filter(Boolean).join(', ')
        : '';

    return (
        <View style={styles.container}>
            <ScrollView
                style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}
                contentContainerStyle={{ paddingTop: insets.top }}
            >
                <Stack.Screen options={{ headerShown: false }} />

                <View style={styles.imageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.headerImage} resizeMode="cover" />
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{displayTitle}</Text>


                    <SeccionDetalles titulo="Detalles" subtitulo={detallesStr} />


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
        marginBottom: 24,
    }
});
