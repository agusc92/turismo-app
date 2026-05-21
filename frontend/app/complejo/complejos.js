import { View, StyleSheet, FlatList } from "react-native";
import { Stack } from "expo-router";
import { Colors } from "../../constants/Styles";
import ItemCard from "../../components/ItemCard";
import { complejos } from "../../assets/mokup";

export default function ComplejosList() {
    const renderItem = ({ item }) => {
        const itemId = item.id || item.idComplejo;
        return (
            <ItemCard 
                item={item} 
                subtitle={item.direccion} 
                imageUrl={item.imagen} 
                link={`/complejo/${itemId}`} 
            />
        );
    };

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
