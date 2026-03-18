import React, { useRef, useState, useEffect } from "react";
import { View, Text, StyleSheet, ScrollView, Image, Dimensions, FlatList } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Link, Stack } from 'expo-router';
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import MenuCard from "../components/MenuCard";

const { width } = Dimensions.get('window');

export default function Home() {
    const eventos = [
        {
            id: 1,
            nombre: "Evento 1",
            imagen: "https://necochea.tur.ar/wp-content/uploads/2026/03/wo2103-1.jpg",
        },
        {
            id: 2,
            nombre: "Evento 2",
            imagen: "https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg",
        },
        {
            id: 3,
            nombre: "Evento 3",
            imagen: "https://necochea.tur.ar/wp-content/uploads/2026/03/wo0404.jpg",
        },
        {
            id: 4,
            nombre: "Evento 4",
            imagen: "https://necochea.tur.ar/wp-content/uploads/2025/12/paris1104.jpg",
        },
    ];

    const flatListRef = useRef(null);
    const [currentIndex, setCurrentIndex] = useState(0);

    useEffect(() => {
        const interval = setInterval(() => {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= eventos.length) {
                nextIndex = 0;
            }
            setCurrentIndex(nextIndex);
            if (flatListRef.current) {
                flatListRef.current.scrollToIndex({ index: nextIndex, animated: true });
            }
        }, 5000);

        return () => clearInterval(interval);
    }, [currentIndex, eventos.length]);

    const onMomentumScrollEnd = (event) => {
        const slideSize = event.nativeEvent.layoutMeasurement.width;
        const index = event.nativeEvent.contentOffset.x / slideSize;
        const roundIndex = Math.round(index);
        if (roundIndex !== currentIndex) {
            setCurrentIndex(roundIndex);
        }
    };

    return (
        <ScreenLayout>
            <Stack.Screen options={{
                title: "Necochea",
                // 👇 Sobreescribimos el header solo para esta pantalla
                headerTitle: (props) => <HeaderPage title={props.children} logo={true} />
            }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false}>
                <Text style={styles.sectionTitle}>Eventos destacados</Text>

                <FlatList
                    ref={flatListRef}
                    data={eventos}
                    keyExtractor={(item) => item.id.toString()}
                    horizontal
                    pagingEnabled
                    showsHorizontalScrollIndicator={false}
                    onMomentumScrollEnd={onMomentumScrollEnd}
                    renderItem={({ item }) => (
                        <View style={styles.carouselItemContainer}>
                            <View style={styles.carouselItem}>
                                <Image
                                    source={{ uri: item.imagen }}
                                    style={styles.carouselImage}
                                    resizeMode="cover"
                                />
                                <View style={styles.textContainer}>
                                    <Text style={styles.carouselText}>{item.nombre}</Text>
                                </View>
                            </View>
                        </View>
                    )}
                />
                <View style={styles.menuContainer}>
                    <MenuCard title="Alojamiento" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' />
                    <MenuCard title="Alojamiento" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' />
                    <MenuCard title="Alojamiento" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' />
                    <MenuCard title="Alojamiento" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' />
                </View>
            </ScrollView>

        </ScreenLayout>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    primaryText: {
        color: Colors.textColor,
    },
    pageContent: {
        backgroundColor: Colors.backgroundLight,
        flex: 1,
    },
    sectionTitle: {
        fontSize: 22,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginHorizontal: 20,
        marginTop: 20,
        marginBottom: 15,
    },
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
    menuContainer: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        justifyContent: 'space-between',
        paddingHorizontal: 20,
        marginTop: 10,
        marginBottom: 40,
    }
});