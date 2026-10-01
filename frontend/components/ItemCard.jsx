/**
 * Componente de tarjeta reutilizable para la presentación de ítems en listados.
 * Muestra una imagen del item, su nombre y un subtítulo descriptivo, y permite la navegación
 * hacia la vista de detalles al ser presionado.
 * Se utiliza en pantallas tipo recursos.js en forma de lista. por ejemplo: (gastronomia, actividades, etc).
 * 
 * @module components/ItemCard
 * @requires react-native.TouchableOpacity
 * @requires react-native.Image
 * @requires react-native.Text
 * @requires react-native.StyleSheet
 * @requires react-native.View
 * @requires expo-router.router
 */

import { TouchableOpacity, Image, Text, StyleSheet, View } from "react-native";
import { router } from "expo-router";


export default function ItemCard({ item, subtitle, imageUrl, imageSource, link }) {
    return (
        <TouchableOpacity
            style={styles.card}
            onPress={() => router.push(link)}
        >
            <Image
                source={imageSource || { uri: imageUrl }}
                style={styles.cardImage}
            />
            <View style={styles.cardInfo}>
                <Text style={styles.cardTitle} numberOfLines={2} ellipsizeMode="tail">
                    {item.nombre}
                </Text>
                <Text style={styles.cardSubtitle} numberOfLines={1} ellipsizeMode="tail">
                    {subtitle}
                </Text>
            </View>
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    card: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        marginBottom: 20,
    },
    cardImage: {
        width: 100,
        height: 70,
        borderRadius: 8,
        marginRight: 15,
        backgroundColor: '#E5E5EA',
    },
    cardInfo: {
        flex: 1,
        justifyContent: 'center',
    },
    cardTitle: {
        fontSize: 18,
        fontFamily: 'Gotham-Medium',
        color: '#2C1B4D',
        marginBottom: 4,
        textTransform: 'capitalize',
    },
    cardSubtitle: {
        fontSize: 13,
        color: '#55476eff',
        marginTop: 2,
        fontFamily: 'Gotham-Light',
    },
});
