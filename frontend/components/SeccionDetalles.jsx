/**
 * @fileoverview Componente de sección reutilizable para la presentación de detalles de un recurso.
 * Muestra un título de sección y texto descriptivo o secundario, con estilos consistentes.
 * Se utiliza dentro de la seccion detalle de los recursos (gastronomico, actividades,etc)
 * @module components/SeccionDetalles
 * @requires react-native.View
 * @requires react-native.Text
 * @requires react-native.StyleSheet
 * @exports SeccionDetalles
 */

import { View, Text, StyleSheet } from 'react-native';
import { Colors } from '../constants/Styles';

export default function SeccionDetalles({ titulo, subtitulo }) {
    return (
        <View style={styles.section}>
            <Text style={styles.sectionTitle}>{titulo}</Text>
            <Text style={styles.sectionText}>{subtitulo}</Text>
        </View>
    )
}

const styles = StyleSheet.create({
    section: {
        marginBottom: 32,
    },
    sectionTitle: {
        fontSize: 20,
        fontFamily: 'Gotham-Bold',
        color: Colors.textColor,
        marginBottom: 12,
    },
    sectionText: {
        fontSize: 15,
        color: '#554E66',
        lineHeight: 22,
        fontFamily: 'Gotham-Medium',
    },
})