import { TouchableOpacity, Image, Text, StyleSheet, View } from "react-native";
import { router } from "expo-router";


export default function ItemCard({ item, subtitle, imageUrl, link }) {
    return (
        <TouchableOpacity
            style={styles.card}
            onPress={() => router.push(link)}
        >
            <Image
                source={{ uri: imageUrl }}
                style={styles.cardImage}
            />
            <View style={styles.cardInfo}>
                <Text style={styles.cardTitle}>{item.nombre}</Text>
                <Text style={styles.cardSubtitle}>{subtitle}</Text>
            </View>
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    card: {
        flexDirection: 'row',
        alignItems: 'center',
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
        fontWeight: 'bold',
        color: '#2C1B4D',
        marginBottom: 4,
        textTransform: 'capitalize',
    },
    cardSubtitle: {
        fontSize: 13,
        color: '#8A819C',
        marginTop: 2,
    },
});
