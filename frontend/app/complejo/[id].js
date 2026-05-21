import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity } from "react-native";
import { useLocalSearchParams, useRouter, Stack } from "expo-router";
import { Colors, BackButton } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import SeccionDetalles from "../../components/SeccionDetalles";
import UbicacionDetalles from "../../components/UbicacionDetalles";
import ContactoDetalles from "../../components/ContactoDetalles";
import { complejos } from "../../assets/mokup";

export default function ComplejoDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();

    // Buscar el complejo por id en el mockup
    const item = complejos.find(c => (c.idComplejo || c.id).toString() === id?.toString());

    if (!item) {
        return (
            <View style={styles.centered}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>Complejo no encontrado</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <ScrollView
                style={styles.pageContent} 
                showsVerticalScrollIndicator={false} 
                bounces={false}
            >
                {/* Ocultamos el header estándar para poner un botón de atrás encima de la imagen */}
                <Stack.Screen options={{ headerShown: false }} />

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
            
            <TouchableOpacity style={BackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
            </TouchableOpacity>
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
