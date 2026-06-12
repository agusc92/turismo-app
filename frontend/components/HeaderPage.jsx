import { View, Text, StyleSheet, TouchableOpacity } from "react-native";
import { Logo } from "../assets/images";
import { Colors } from "../constants/Styles";
import { Ionicons } from '@expo/vector-icons';

export default function HeaderPage({ title, logo = false, canGoBack = false, onBackPress }) {
    return (
        <View style={styles.headerWrapper}>
            
            {/* 1. COLUMNA IZQUIERDA: Botón de atrás (o caja vacía para mantener el equilibrio) */}
            <View style={styles.columnLeft}>
                {canGoBack ? (
                    <TouchableOpacity onPress={onBackPress} style={styles.backButton}>
                        <Ionicons name="arrow-back" size={28} color={Colors.textColor} />
                    </TouchableOpacity>
                ) : null}
            </View>

            {/* 2. COLUMNA CENTRAL: Logo + Título */}
            <View style={styles.columnCenter}>
                {logo && (
                    <View style={styles.logoContainer}>
                        <Logo />
                    </View>
                )}
                <Text style={styles.headerText} numberOfLines={1}>
                    {title}
                </Text>
            </View>

            {/* 3. COLUMNA DERECHA: Caja fantasma para hacer el contrapeso Flex */}
            <View style={styles.columnRight} />

        </View>
    );
}

const styles = StyleSheet.create({
    headerWrapper: {
        flexDirection: 'row',
        width: '100%',
        alignItems: 'center',
        paddingHorizontal: 16,
        paddingVertical: 16,
    },
    // Izquierda y Derecha ocupan exactamente la misma proporción (flex: 1)
    columnLeft: {
        alignItems: 'flex-start',
        justifyContent: 'center',
        minWidth: '10%', // Espacio mínimo reservado para la flecha
    },
    columnRight: {
        alignItems: 'flex-end',
        justifyContent: 'center',
        minWidth: '10%', // Tiene que medir exactamente lo mismo que la columna izquierda
    },
    // El centro crece dinámicamente ocupando el espacio restante y acomodando todo al medio
    columnCenter: {
        flex: 1,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 10,
        maxWidth: '80%', // Evita que un título súper largo rompa el diseño
    },
    logoContainer: {
        justifyContent: 'center',
        alignItems: 'center',
    },
    backButton: {
        width: 40,
        height: 40,
        justifyContent: 'center',
        alignItems: 'center',
        borderRadius: 20,
        backgroundColor: 'rgba(255, 255, 255, 0.7)',
    },
    headerText: {
        color: Colors.textColor,
        fontSize: 32,
        fontFamily: 'Gotham-Ultra',
        textTransform: 'capitalize',
    }
});