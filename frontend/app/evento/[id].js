import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { useFetchDetalle } from '../hooks/useFetchDetalle';
import { Colors, BackButton } from '../../constants/Styles';
import { Ionicons } from '@expo/vector-icons';
import UbicacionDetalles from '../../components/UbicacionDetalles';

export default function EventoDetalle() {
    const { id } = useLocalSearchParams();
    const { data: evento, loading } = useFetchDetalle('eventos', id);

    if (loading) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'Cargando...' }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!evento) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>El evento no existe.</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ headerShown: false }} />
            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    {evento.imagen ? (
                        <Image source={{ uri: evento.imagen }} style={styles.image} resizeMode="cover" />
                    ) : (
                        <View style={styles.placeholderImage}></View>
                    )}
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{evento.nombre}</Text>

                    <View style={styles.dateRow}>
                        <Ionicons name="calendar-outline" size={22} color="#2C1B4D" style={styles.icon} />
                        <Text style={styles.dateLabel}>Inicio</Text>
                        <Text style={styles.dateValue}>{evento.fecha}</Text>
                    </View>

                    <View style={styles.descriptionContainer}>
                        <Text style={styles.description}>{evento.descripcion}</Text>
                    </View>

                    <UbicacionDetalles direccion={evento.direccion} />
                </View>
            </ScrollView>

            {/* Fixed Back Button */}
            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    errorContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f5f5f5',
    },
    errorText: {
        fontSize: 18,
        color: '#333',
    },
    pageContent: {
        flex: 1,
    },
    headerImageContainer: {
        position: 'relative',
        width: '100%',
        height: 320,
    },
    image: {
        width: '100%',
        height: '100%',
    },
    placeholderImage: {
        width: '100%',
        height: '100%',
        backgroundColor: '#e0e0e0',
    },
    contentContainer: {
        paddingHorizontal: 20,
        paddingTop: 20,
        backgroundColor: '#fff',
    },
    title: {
        fontSize: 28,
        fontWeight: '900',
        color: '#2C1B4D',
        marginBottom: 25,
        textTransform: 'capitalize',
        letterSpacing: -0.5,
    },
    dateRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 30,
    },
    icon: {
        marginRight: 15,
    },
    dateLabel: {
        fontSize: 16,
        color: '#555',
        marginRight: 10,
    },
    dateValue: {
        fontSize: 16,
        color: '#444',
    },
    descriptionContainer: {
        marginBottom: 30,
    },
    description: {
        fontSize: 16,
        color: '#555',
        lineHeight: 24,
    },
});
