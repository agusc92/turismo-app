/**
 * @fileoverview Componente de carrusel interactivo para la visualización de eventos destacados.
 * Incorpora navegación mediante desplazamiento horizontal, rotación automática
 * temporizada, cálculo de índice mediante gestos y redirección hacia el detalle de cada evento.
 * se Utiliza dentro de la Home
 * @module components/Carousel
 * @requires react.useRef
 * @requires react.useState
 * @requires react.useEffect
 * @requires react-native.FlatList
 * @requires react-native.View
 * @requires react-native.Text
 * @requires react-native.Image
 * @requires react-native.TouchableOpacity
 * @requires react-native.StyleSheet
 * @requires react-native.Dimensions
 * @requires expo-router.Link
 * @requires expo-linear-gradient.LinearGradient
 * @requires ../constants/Styles.Colors
 * @requires ../api.API_URL
 * @requires ../assets/images.getResourceImage
 */

import { FlatList, View, Text, Image, TouchableOpacity, StyleSheet, Dimensions } from "react-native";
import { Link } from "expo-router";
import { LinearGradient } from 'expo-linear-gradient';
import { Colors } from "../constants/Styles";
import { API_URL } from "../api";
import { getResourceImage } from "../assets/images";
import { useEffect, useRef, useState } from "react";
const { width } = Dimensions.get('window');

/**
 * Componente principal de carrusel horizontal con autoscroll para elementos destacados.
 *
 * @function Carousel
 * @returns {JSX.Element} Lista horizontal desplazable (`FlatList`) con soporte de temporizador y navegación.
 */
export default function Carousel() {
    const flatListRef = useRef(null);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [eventosDestacados, setEventosDestacados] = useState([]);

    /**
     * Evento ejecutado al finalizar el desplazamiento por inercia (momentum scroll).
     * Recalcula el índice actual en función del offset horizontal alcanzado.
     * 
     * @function onMomentumScrollEnd
     * @param {Object} event - Evento nativo emitido por el scroll.
     */
    const onMomentumScrollEnd = (event) => {
        const slideSize = event.nativeEvent.layoutMeasurement.width;
        const index = event.nativeEvent.contentOffset.x / slideSize;
        const roundIndex = Math.round(index);
        if (roundIndex !== currentIndex) {
            setCurrentIndex(roundIndex);
        }
    };

    // 2. Consulta asíncrona a la API para la obtención de los eventos destacados
    useEffect(() => {
        const fetchEventos = async () => {
            try {
                const response = await fetch(`${API_URL}/eventos/destacados`);
                const data = await response.json();
                setEventosDestacados(data);
            } catch (error) {
                console.error("Error fetching eventos destacados", error);
            }
        };
        fetchEventos();
    }, []);

    // 3. Control del temporizador para el desplazamiento automático horizontal cada 5 segundos
    useEffect(() => {
        // Interrupción si no existen elementos cargados en la lista
        if (eventosDestacados.length === 0) return;

        const interval = setInterval(() => {
            let nextIndex = currentIndex + 1;
            // Reinicio al primer elemento si se alcanza el final del listado
            if (nextIndex >= eventosDestacados.length) {
                nextIndex = 0;
            }
            setCurrentIndex(nextIndex);
            // Desplazamiento animado al siguiente índice usando la referencia
            if (flatListRef.current) {
                flatListRef.current.scrollToIndex({ index: nextIndex, animated: true });
            }
        }, 5000);

        return () => clearInterval(interval);
    }, [currentIndex, eventosDestacados]);

    return (
        <FlatList
            ref={flatListRef}
            data={eventosDestacados}
            keyExtractor={(item) => item.id.toString()}
            horizontal
            pagingEnabled
            showsHorizontalScrollIndicator={false}
            onMomentumScrollEnd={onMomentumScrollEnd}
            renderItem={({ item }) => (
                <View style={styles.carouselItemContainer}>
                    {/* Vinculación con Expo Router hacia la ruta de detalle del evento */}
                    <Link href={`/evento/${item.id}`} asChild>
                        <TouchableOpacity style={styles.carouselItem} activeOpacity={0.8}>
                            <Image
                                source={getResourceImage('evento', item)}
                                style={styles.carouselImage}
                                resizeMode="cover"
                            />
                            {/* Capa de degradado inferior para optimizar el contraste tipográfico */}
                            <LinearGradient
                                colors={['transparent', 'rgba(0,0,0,0.8)']}
                                style={styles.gradientContainer}
                            >
                                <Text style={styles.carouselText}>{item.nombre}</Text>
                            </LinearGradient>
                        </TouchableOpacity>
                    </Link>
                </View>
            )}
        />
    )
}

const styles = StyleSheet.create({
    carouselItemContainer: {
        width: width, // Full screen width to allow paging snapping
        paddingHorizontal: 20, // Inner padding so it doesn't touch the screen borders
        paddingBottom: 20,
    },
    carouselItem: {
        borderRadius: 16,
        overflow: 'hidden',
        backgroundColor: '#fff',
        elevation: 4, // Android shadow
        shadowColor: '#000', // iOS shadow
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.5,
        shadowRadius: 6,
    },
    carouselImage: {
        width: '100%',
        height: 200, // Make it a bit taller since it's full width now
    },
    gradientContainer: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        padding: 12,
        paddingTop: 60, // Para un degradado más suave hacia arriba
    },
    carouselText: {
        fontSize: 16,
        fontFamily: 'Gotham-Medium', // Usamos la fuente que acabas de cargar
        color: '#FFFFFF',
    },
});
