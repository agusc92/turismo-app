import { View, Text, StyleSheet } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import WebView from "react-native-webview";
import { Colors } from "../constants/Styles";

export default function UbicacionDetalles({ direccion }) {
    return (
        <>
            {/* Ubicación */}
            <View style={styles.section}>
                <Text style={styles.sectionTitle}>Ubicación</Text>
                <View style={styles.contactRow}>
                    <Ionicons name="location-outline" size={20} color="#31204D" style={styles.contactIcon} />
                    <Text style={styles.contactText}>{direccion ? String(direccion) : 'No especificada'}</Text>
                </View>
            </View>

            {/* Mapa */}
            {direccion ? (
                <View style={styles.mapWrap}>
                    <WebView
                        source={{ html: `<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><style>body { margin: 0; padding: 0; }</style><iframe width="100%" height="100%" frameborder="0" style="border:0;" src="https://www.google.com/maps?q=${encodeURIComponent(direccion + ', Necochea, Argentina')}&output=embed" allowfullscreen></iframe>` }}
                        style={styles.mapImage}
                        scrollEnabled={false}
                        bounces={false}
                        showsVerticalScrollIndicator={false}
                        showsHorizontalScrollIndicator={false}
                    />
                </View>
            ) : null}
        </>
    );



}
const styles = StyleSheet.create({
    section: {
        marginBottom: 6,
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#31204D',
        marginBottom: 10,
    },
    sectionText: {
        fontSize: 15,
        color: '#4B465C',
        lineHeight: 24,
    },
    contactRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 12,
    },
    contactIcon: {
        marginRight: 12,
    },
    contactText: {
        fontSize: 15,
        color: '#4B465C',
    },
    mapWrap: {
        marginBottom: 50,
        height: 200,
        borderRadius: 16,
        overflow: 'hidden',
        backgroundColor: '#ddd',
    },
    mapImage: {
        width: '100%',
        height: '100%',
    }
});