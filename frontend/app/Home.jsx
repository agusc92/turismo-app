import React, { useRef, useState, useEffect } from "react";
import { View, Text, StyleSheet, ScrollView, Image, Dimensions, FlatList, TouchableOpacity } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Link, Tabs } from 'expo-router';
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import MenuCard from "../components/MenuCard";
import { API_URL } from "../api";

const { width } = Dimensions.get('window');

export default function Home() {


    const flatListRef = useRef(null);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [eventosDestacados, setEventosDestacados] = useState([]);

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
            <Tabs.Screen options={{
                title: "Inicio",
                headerShown: true,
                headerTitle: () => <HeaderPage title="Necochea" logo={true} />,
                headerStyle: { backgroundColor: Colors.backgroundLight },
                headerShadowVisible: false,
                headerTitleAlign: 'center',
            }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false}>
                <Text style={styles.sectionTitle}>Eventos destacados</Text>

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
                <View style={styles.menuContainer}>
                    <MenuCard title="Alojamiento" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' href="/alojamiento/alojamientos" />
                    <MenuCard title="Gastronomía" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' href="/gastronomico/gastronomico" />
                    <MenuCard title="Balnearios" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' href="/balneario/balnearios" />
                    <MenuCard title="Actividades" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' href="/actividad/actividades" />
                    <MenuCard title="Eventos" image='https://necochea.tur.ar/wp-content/uploads/2026/03/neptuno2103.jpg' href="/evento/eventos" />
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
        gap: 18,
    }
});