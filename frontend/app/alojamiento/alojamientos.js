import React, { useState, useEffect } from "react";
import { View, Text, StyleSheet, FlatList, Image, Pressable, ActivityIndicator } from "react-native";
import { Link, Stack } from "expo-router";
import { API_URL } from "../../api";
import { Colors } from "../../constants/Styles";

export default function AlojamientosList() {
    const [alojamientos, setAlojamientos] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchAlojamientos = async () => {
            try {
                const response = await fetch(`${API_URL}/alojamientos`);
                const data = await response.json();
                setAlojamientos(data);
            } catch (error) {
                console.error("Error fetching alojamientos:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchAlojamientos();
    }, []);

    const renderItem = ({ item }) => {
        const itemId = item.id || item.idAlojamiento;
        // Obtenemos una imagen de ejemplo ya que el mock no tiene imágenes, pero si el backend la tiene, la usamos.
        const imageUrl = item.imagen || `https://picsum.photos/seed/${itemId + 10}/200/120`; // cambiar esto

        return (
            <Link href={`/alojamiento/${itemId}`} asChild>
                <Pressable style={styles.cardContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} />
                    <View style={styles.textContainer}>
                        <Text style={styles.name}>{item.nombre}</Text>
                        <Text style={styles.stars}>{item.tipo || item.estrellas}</Text>
                    </View>
                </Pressable>
            </Link>
        );
    };

    if (loading) {
        return (
            <View style={[styles.container, { justifyContent: 'center', alignItems: 'center' }]}>
                <Stack.Screen options={{ title: 'Alojamientos' }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ title: 'Alojamientos' }} />
            <FlatList
                data={alojamientos}
                keyExtractor={(item) => (item.id || item.idAlojamiento || Math.random()).toString()}
                renderItem={renderItem}
                contentContainerStyle={styles.listContent}
            />
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    listContent: {
        paddingVertical: 16,
    },
    cardContainer: {
        flexDirection: 'row',
        paddingHorizontal: 20,
        marginBottom: 24,
        alignItems: 'center',
    },
    image: {
        width: 110,
        height: 70,
        borderRadius: 8,
        marginRight: 16,
    },
    textContainer: {
        flex: 1,
        justifyContent: 'center',
    },
    name: {
        fontSize: 16,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginBottom: 4,
    },
    stars: {
        fontSize: 14,
        color: '#9E94B6', // Un tono gris claro/violáceo acorde al diseño
    }
});
