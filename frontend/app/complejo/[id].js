import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, ActivityIndicator } from "react-native";
import { useLocalSearchParams, Stack } from "expo-router";
import { Colors } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import SeccionDetalles from "../../components/SeccionDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";
import ContactoDetalles from "../../components/ContactoDetalles";
import { useFetchDetalle } from "../hooks/useFetchDetalle";
import TransparentHeader from "../../components/TransparentHeader";

export default function ComplejoDetail() {
    const { id } = useLocalSearchParams();

    const { data: item, loading } = useFetchDetalle('complejos', id);

    if (loading) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: false }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <TransparentHeader />

            <ScrollView
                style={styles.pageContent}
                showsVerticalScrollIndicator={false}
                bounces={false}
            >

                <View style={styles.imageContainer}>
                    <Image source={{ uri: item.imagen }} style={styles.headerImage} />
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{item.nombre}</Text>

                    {item.servicios ? (
                        <SeccionDetalles titulo="Servicios" subtitulo={item.servicios} />
                    ) : null}

                    {item.adicional ? (
                        <SeccionDetalles titulo="Adicional" subtitulo={item.adicional} />
                    ) : null}

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
        fontFamily: 'Gotham-Bold',
        color: Colors.textColor,
        marginBottom: 24,
    }
});
