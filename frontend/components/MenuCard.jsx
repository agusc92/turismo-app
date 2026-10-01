/**
 * @fileoverview Componente de tarjeta reutilizable para la presentación de ítems en listados.
 * Muestra una imagen del item, su nombre y un subtítulo descriptivo, y permite la navegación
 * hacia la vista de detalles al ser presionado.
 * Se utiliza en la Home.
 * 
 * @module components/ItemCard
 * @requires react-native.TouchableOpacity
 * @requires react-native.Image
 * @requires react-native.Text
 * @requires react-native.StyleSheet
 * @requires react-native.View
 * @requires expo-router.router
 */

import { Link } from "expo-router";
import { Image, Pressable, Text, StyleSheet, View } from "react-native";
import { Colors } from "../constants/Styles";

export default function MenuCard({ title, image, href }) {
    const capitalizado = title.charAt(0).toUpperCase() + title.slice(1);
    return (
        <Link href={href || `/${title}`} asChild>
            <Pressable style={styles.wrapper}>
                <Text style={styles.title}>{capitalizado}</Text>
                <View style={styles.card}>
                    <Image source={{ uri: image }} style={styles.image} />
                </View>
            </Pressable>
        </Link>
    )
}
const styles = StyleSheet.create({
    wrapper: {
        width: "100%", // Toma casi la mitad del espacio disponible
        marginBottom: 15,
    },
    card: {
        backgroundColor: '#fff',
        borderRadius: 12,
        elevation: 3, // Sombra en Android
        shadowColor: '#000', // Sombra en iOS
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        overflow: 'hidden',
    },
    image: {
        height: 120,
        width: "100%",
        resizeMode: "cover",
    },
    title: {
        fontSize: 20,
        fontFamily: 'Gotham-Black',
        color: Colors.textColor,
        marginBottom: 8,
        marginLeft: 4,
    }
});