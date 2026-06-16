import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, Stack } from "expo-router";
import { Colors } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import SeccionDetalles from "../../components/SeccionDetalles";
import { useFetchDetalle } from "../hooks/useFetchDetalle";
import ContactoDetalles from "../../components/ContactoDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";
import TransparentHeader from "../../components/TransparentHeader";
import { getResourceImage } from '../../assets/images';

export default function BalnearioDetail() {
    const { id } = useLocalSearchParams();

    const { data: item, loading } = useFetchDetalle('balnearios', id);

    if (loading) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    if (!item) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>Balneario no encontrado</Text>
            </View>
        );
    }

    const itemId = item.id || item.idBalneario;
    const imageSource = getResourceImage('balneario', item);

    // Formatear titulo
    const displayTitle = item.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');

    // Formatear servicios
    const detallesStr = item.servicios
        ? item.servicios.split('|').map(s => s.trim().charAt(0).toUpperCase() + s.trim().slice(1)).filter(Boolean).join(', ')
        : '';

    return (
        <View style={styles.container}>
            <TransparentHeader />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>

                <View style={styles.imageContainer}>
                    <Image source={imageSource} style={styles.headerImage} resizeMode="cover" />
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{displayTitle}</Text>


                    <SeccionDetalles titulo="Detalles" subtitulo={detallesStr} />


                    <ContactoDetalles item={item} />

                    <UbicacionDetalles direccion={item.direccion} />
                </View>
            </ScrollView>

        </View>
    );
}

const styles = StyleSheet.create({
    pageContent: {
        flex: 1,
    },
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    centered: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    errorText: {
        fontSize: 16,
        color: Colors.textColor,
    },
    imageContainer: {
        width: '100%',
        height: 250,
        position: 'relative',
    },
    headerImage: {
        width: '100%',
        height: '100%',
    },
    contentContainer: {
        padding: 24,
    },
    title: {
        fontSize: 26,
        fontFamily: "Gotham-Bold",
        color: Colors.textColor,
        marginBottom: 32,
    }
});
