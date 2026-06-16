import { FlatList, View, Text, Image, TouchableOpacity, StyleSheet, Dimensions } from "react-native";
import { Link } from "expo-router";
import { LinearGradient } from 'expo-linear-gradient';
import { Colors } from "../constants/Styles";
import { API_URL } from "../api";
import { getResourceImage } from "../assets/images";
import { useEffect, useRef, useState } from "react";
const { width } = Dimensions.get('window');

export default function Carousel() {
    const flatListRef = useRef(null);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [eventosDestacados, setEventosDestacados] = useState([]);

    const onMomentumScrollEnd = (event) => {
        const slideSize = event.nativeEvent.layoutMeasurement.width;
        const index = event.nativeEvent.contentOffset.x / slideSize;
        const roundIndex = Math.round(index);
        if (roundIndex !== currentIndex) {
            setCurrentIndex(roundIndex);
        }
    };

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

    useEffect(() => {
        if (eventosDestacados.length === 0) return;

        const interval = setInterval(() => {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= eventosDestacados.length) {
                nextIndex = 0;
            }
            setCurrentIndex(nextIndex);
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
                    <Link href={`/evento/${item.id}`} asChild>
                        <TouchableOpacity style={styles.carouselItem} activeOpacity={0.8}>
                            <Image
                                source={getResourceImage('evento', item)}
                                style={styles.carouselImage}
                                resizeMode="cover"
                            />
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
