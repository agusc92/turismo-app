/**
 * @fileoverview Vista de detalle dinámico para un recurso específico (ej. Actividad).
 * Este archivo actúa como plantilla general para las rutas dinámicas `[id].js` de Expo Router, 
 * las cuales se repiten estructuralmente en cada categoría de la aplicación.
 * 
 * Se encarga de:
 * 1. Capturar el ID dinámico de la URL.
 * 2. Realizar el fetch de datos a la API mediante el hook reutilizable `useFetchDetalle`.
 * 3. Gestionar la UI en sus tres estados: carga, error/no encontrado, y éxito.
 *
 * @requires expo-router
 * @requires react-native
 */

import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack } from 'expo-router';
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Ionicons } from '@expo/vector-icons';
import UbicacionDetalles from '../../components/UbicacionDetalles';
import ContactoDetalles from '../../components/ContactoDetalles';
import TransparentHeader from "../../components/TransparentHeader";
import { getResourceImage } from '../../assets/images';

/**
 * Componente funcional para renderizar la pantalla de detalles de un ítem.
 * @returns {JSX.Element} La pantalla de carga, el mensaje de error o la vista renderizada del recurso.
 */
export default function ActividadDetalle() {
    // 1. Captura el parámetro de la ruta (ej. /actividades/4)
    const { id } = useLocalSearchParams();
    // 2. Fetch de datos centralizado
    const { data: actividad, loading } = useFetchDetalle('actividades', id);
    // Estado: Cargando
    if (loading) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }
    // Estado: Error o ID no encontrado
    if (!actividad) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>La actividad no existe.</Text>
            </View>
        );
    }
    // Estado: Éxito. Resuelve la imagen (remota de la API o fallback local).
    const imageSource = getResourceImage('actividad', actividad);

    return (
        <View style={styles.container}>
            <TransparentHeader />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    <Image source={imageSource} style={styles.image} resizeMode="cover" />
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
        fontFamily: 'Gotham-Bold',
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
