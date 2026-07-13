import { View, StyleSheet, FlatList, ActivityIndicator } from "react-native";
import { Stack } from "expo-router";
import { Colors } from "../../constants/Styles";
import ItemCard from "../../components/ItemCard";
import { getResourceImage } from '../../assets/images';
import { useData } from "../hooks/UseData";

export default function AlojamientosList() {
    const { data: alojamientos, loading } = useData('alojamientos');

    const renderItem = ({ item }) => {
        const itemId = item.id || item.idAlojamiento;
        const imageSource = getResourceImage('alojamiento', item);
        const tipo = item.tipos_alojamiento ? (Array.isArray(item.tipos_alojamiento) ? item.tipos_alojamiento.map(t => t.tipo).join(' / ') : item.tipos_alojamiento[0].tipo) : '';

        return (
            <ItemCard item={item} subtitle={tipo} imageSource={imageSource} link={`/alojamiento/${itemId}`} />
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
                keyExtractor={(item) => (item.id)}
                renderItem={renderItem}
                contentContainerStyle={styles.listContainer}
            />
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    listContainer: {
        padding: 20,
        paddingTop: 15,
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
