import { View, StyleSheet, FlatList, ActivityIndicator } from 'react-native';
import { Stack } from 'expo-router';
import { Colors } from '../../constants/Styles';
import ItemCard from '../../components/ItemCard';
import { useData } from '../hooks/UseData';

export default function BalneariosList() {
    const { data: balnearios, loading } = useData('balnearios');

    const renderItem = ({ item }) => {
        const itemId = item.id || item.idBalneario;
        // Obtenemos una imagen de la API si existe, o una de ejemplo
        const imageUrl = item.imagen || `https://picsum.photos/seed/${itemId + 20}/200/120`;

        // Capitalizar el nombre
        const nombreCapitalizado = item.nombre
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');

        return (
            <ItemCard item={item} subtitle={item.direccion} imageUrl={imageUrl} link={`/balneario/${itemId}`} />
        );
    };

    if (loading) {
        return (
            <View style={[styles.container, { justifyContent: 'center', alignItems: 'center' }]}>
                <Stack.Screen options={{ title: 'Balnearios' }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ title: 'Balnearios' }} />
            <FlatList
                data={balnearios}
                keyExtractor={(item) => (item.id || item.idBalneario || Math.random()).toString()}
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
    address: {
        fontSize: 14,
        color: '#9E94B6',
    }
});
