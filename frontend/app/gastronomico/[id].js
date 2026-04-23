import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Ionicons } from '@expo/vector-icons';
import { Colors, BackButton } from "../../constants/Styles";
import ContactoDetalles from '../../components/ContactoDetalles';
import UbicacionDetalles from '../../components/UbicacionDetalles';
import SeccionDetalles from '../../components/SeccionDetalles';

export default function GastronomicoDetalle() {
    const { id } = useLocalSearchParams();
    const { data: item, loading } = useFetchDetalle('gastronomicos', id);

    if (loading) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!item) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>El lugar no existe.</Text>
            </View>
        );
    }

    // Parsers adaptados por si vienen como strings sueltos o como arreglos de un solo item
    const tiposArr = Array.isArray(item.tipo) ? item.tipo : (item.tipo ? [item.tipo] : []);
    const tipos = tiposArr.map(t => (t.nombre || t).charAt(0).toUpperCase() + (t.nombre || t).slice(1)).join(' / ');

    const menusArr = Array.isArray(item.menu) ? item.menu : (item.menu ? [item.menu] : []);
    const menus = menusArr.map(m => (m.tipo || m).charAt(0).toUpperCase() + (m.tipo || m).slice(1)).join(', ');

    // extras is a string delimited by |
    const extras = item.extras ? String(item.extras).split('|').map(e => e.trim().charAt(0).toUpperCase() + e.trim().slice(1)).join(', ') : '';

    // We'll trust whatever is in the image, or fallback to dummy
    const imageUrl = item.imagen && item.imagen.startsWith('http')
        ? item.imagen
        : `https://loremflickr.com/320/240/restaurant`;

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ headerShown: false }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} resizeMode="cover" />
                </View>

                <View style={styles.contentContainer}>
                    {/* Header Title section */}
                    <Text style={styles.title}>{item.nombre}</Text>
                    {tipos ? <Text style={styles.subtitle}>{tipos}</Text> : null}

                    {/* Horario */}
                    {item.horario ? (
                        <SeccionDetalles titulo="Horario" subtitulo={item.horario} />
                    ) : null}

                    {/* Menús Especiales */}
                    {menus ? (
                        <SeccionDetalles titulo="Menús Especiales" subtitulo={menus} />
                    ) : null}

                    {/* Extras */}
                    {extras ? (
                        <SeccionDetalles titulo="Extras" subtitulo={extras} />
                    ) : null}

                    <ContactoDetalles item={item} />

                    <UbicacionDetalles direccion={item.direccion} />

                </View>
            </ScrollView>

            {/* Back Button floating overlay */}
            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FAFAFD', // Fondo súper claro tirando a lila
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
        width: '100%',
        height: 260,
        backgroundColor: '#e0e0e0',
    },
    image: {
        width: '100%',
        height: '100%',
    },
    contentContainer: {
        paddingHorizontal: 25,
        paddingTop: 30,
        backgroundColor: '#FAFAFD',
    },
    title: {
        fontSize: 32,
        fontWeight: '900',
        color: '#31204D',
        textTransform: 'capitalize',
        letterSpacing: -0.5,
    },
    subtitle: {
        fontSize: 16,
        color: '#4B465C',
        marginTop: 5,
        marginBottom: 30,
    },
});
