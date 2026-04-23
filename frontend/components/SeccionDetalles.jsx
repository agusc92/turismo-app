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
        fontSize: 18,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginBottom: 12,
    },
    sectionText: {
        fontSize: 15,
        color: '#554E66',
        lineHeight: 22,
    },
})