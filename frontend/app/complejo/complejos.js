import { View, StyleSheet, FlatList, ActivityIndicator } from "react-native";
import { Stack } from "expo-router";
import { Colors } from "../../constants/Styles";
import ItemCard from "../../components/ItemCard";
import { getResourceImage } from '../../assets/images';
import { useData } from '../hooks/UseData';

export default function ComplejosList() {
    const { data: complejos, loading } = useData('complejos');

    const renderItem = ({ item }) => {
        const itemId = item.id || item.idComplejo;
        const imageSource = getResourceImage('complejo', item);

        // Capitalizar el nombre
        const nombreCapitalizado = item.nombre
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');

        return (
            <ItemCard item={item} subtitle={item.direccion} imageSource={imageSource} link={`/complejo/${itemId}`} />
        );
    };

    if (loading) {
        return (
            <View style={[styles.container, { justifyContent: 'center', alignItems: 'center' }]}>
                <Stack.Screen options={{ title: 'Complejos' }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ title: 'Complejos' }} />
            <FlatList
                data={complejos}
                keyExtractor={(item) => (item.id || item.idComplejo || Math.random()).toString()}
                renderItem={renderItem}
                contentContainerStyle={styles.listContainer}
                showsVerticalScrollIndicator={false}
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
    }
});
