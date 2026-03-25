import React from "react";
import { View, Text, StyleSheet, FlatList, Image, Pressable } from "react-native";
import { Link, Stack } from "expo-router";
import { ajolamientos } from "../../assets/mokup";
import { Colors } from "../../constants/Styles";

export default function AlojamientosList() {
    const renderItem = ({ item }) => {
        // Obtenemos una imagen de ejemplo ya que el mock no tiene imágenes
        const imageUrl = `https://picsum.photos/seed/${item.idAlojamiento + 10}/200/120`;

        return (
            <Link href={`/alojamiento/${item.idAlojamiento}`} asChild>
                <Pressable style={styles.cardContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} />
                    <View style={styles.textContainer}>
                        <Text style={styles.name}>{item.nombre}</Text>
                        <Text style={styles.stars}>{item.tipo}</Text>
                    </View>
                </Pressable>
            </Link>
        );
    };

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ title: 'Alojamientos' }} />
            <FlatList
                data={ajolamientos}
                keyExtractor={(item) => item.idAlojamiento.toString()}
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
