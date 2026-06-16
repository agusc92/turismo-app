import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, Stack } from "expo-router";
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Colors } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import SeccionDetalles from "../../components/SeccionDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";
import ContactoDetalles from "../../components/ContactoDetalles";
import TransparentHeader from "../../components/TransparentHeader";
import { getResourceImage } from '../../assets/images';

export default function AlojamientoDetail() {
    const { id } = useLocalSearchParams();
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
    const imageSource = getResourceImage('alojamiento', item);

    return (
        <View style={styles.container}>
            <TransparentHeader />

            <ScrollView
                style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}
            >

                <View style={styles.imageContainer}>
                    <Image source={imageSource} style={styles.headerImage} />

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
        fontFamily: 'Gotham-Bold',
        color: Colors.textColor,
        marginBottom: 8,
    },
    subtitle: {
        fontSize: 16,
        color: '#7B758E',
        marginBottom: 24,
        fontFamily: 'Gotham-Book',
    },
    transparentHeaderContainer: {
        backgroundColor: 'transparent', // Sin fondo para que se vea la foto
        flexDirection: 'row',
        alignItems: 'center',
        width: '100%',
        // 💡 NO lleva padding top. Al ser un 'header' real de React Navigation, 
        // el sistema operativo ya le calcula automáticamente el espacio de la StatusBar.
        paddingBottom: 14,
    }
});
