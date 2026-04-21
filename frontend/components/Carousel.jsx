import { FlatList, View, Text, Image, TouchableOpacity, StyleSheet, Dimensions } from "react-native";
import { Link } from "expo-router";
import { Colors } from "../constants/Styles";
import { API_URL } from "../api";
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
                                source={{ uri: item.imagen }}
                                style={styles.carouselImage}
                                resizeMode="cover"
                            />
                            <View style={styles.textContainer}>
                                <Text style={styles.carouselText}>{item.nombre}</Text>
                            </View>
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
        shadowOpacity: 0.1,
        shadowRadius: 6,
    },
    carouselImage: {
        width: '100%',
        height: 200, // Make it a bit taller since it's full width now
    },
    textContainer: {
        padding: 15,
    },
    carouselText: {
        fontSize: 18,
        fontWeight: '700',
        color: Colors.textColor,
    },
});
