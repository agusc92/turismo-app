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
        overflow: 'hidden', // Importante para que la imagen respete los bordes redondeados
    },
    image: {
        height: 120,
        width: "100%",
        resizeMode: "cover",
    },
    title: {
        fontSize: 20,
        fontWeight: '700',
        color: Colors.textColor,
        marginBottom: 8,
        marginLeft: 4, // Para que no quede tan pegado al borde izquierdo del contenedor
    }
});