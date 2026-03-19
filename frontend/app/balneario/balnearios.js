import React from 'react';
import { View, Text, StyleSheet, FlatList, Image, Pressable } from 'react-native';
import { Link, Stack } from 'expo-router';
import { balnearios } from '../../assets/mokup';
import { Colors } from '../../constants/Styles';

export default function BalneariosList() {
    const renderItem = ({ item }) => {
        // Obtenemos una imagen de ejemplo
        const imageUrl = `https://picsum.photos/seed/${item.idBalneario + 20}/200/120`;

        // Capitalizar el nombre
        const nombreCapitalizado = item.nombre
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');

        return (
            <Link href={`/balneario/${item.idBalneario}`} asChild>
                <Pressable style={styles.cardContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} />
                    <View style={styles.textContainer}>
                        <Text style={styles.name}>{nombreCapitalizado}</Text>
                        <Text style={styles.address}>{String(item.direccion)}</Text>
                    </View>
                </Pressable>
            </Link>
        );
    };

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ title: 'Balnearios' }} />
            <FlatList
                data={balnearios}
                keyExtractor={(item) => item.idBalneario.toString()}
                renderItem={renderItem}
                contentContainerStyle={styles.listContent}
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
    address: {
        fontSize: 14,
        color: '#9E94B6',
    }
});
