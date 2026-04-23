import { View, Text, StyleSheet } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { Colors } from "../constants/Styles";

export default function ContactoDetalles({ item }) {
    let fb = '', ig = '', tw = '';
    if (item.redesSociales) {
        const redesList = item.redesSociales.split('|').map(r => r.trim());
        redesList.forEach(red => {
            if (red.toLowerCase().startsWith('fb:')) fb = red.slice(3).trim();
            if (red.toLowerCase().startsWith('ig:')) ig = red.slice(3).trim();
            if (red.toLowerCase().startsWith('x:')) tw = red.slice(2).trim();
        });
    }

    return (
        <>
            <View style={styles.section}>
                <Text style={styles.sectionTitle}>Contacto</Text>

                {item.telefono ? (
                    <View style={styles.contactRow}>
                        <Ionicons name="logo-whatsapp" size={20} color={Colors.textColor} style={styles.contactIcon} />
                        <Text style={styles.contactText}>{String(item.telefono)}</Text>
                    </View>
                ) : null}

                {ig ? (
                    <View style={styles.contactRow}>
                        <Ionicons name="logo-instagram" size={20} color={Colors.textColor} style={styles.contactIcon} />
                        <Text style={styles.contactText}>{ig}</Text>
                    </View>
                ) : null}

                {fb ? (
                    <View style={styles.contactRow}>
                        <Ionicons name="logo-facebook" size={20} color={Colors.textColor} style={styles.contactIcon} />
                        <Text style={styles.contactText}>{fb}</Text>
                    </View>
                ) : null}

                {item.mail ? (
                    <View style={styles.contactRow}>
                        <Ionicons name="mail-outline" size={20} color={Colors.textColor} style={styles.contactIcon} />
                        <Text style={styles.contactText}>{String(item.mail)}</Text>
                    </View>
                ) : null}

                {item.tiendaOnline ? (
                    <View style={styles.contactRow}>
                        <Ionicons name="globe-outline" size={20} color={Colors.textColor} style={styles.contactIcon} />
                        <Text style={styles.contactText}>{String(item.tiendaOnline).replace(/https?:\/\//, '').trim()}</Text>
                    </View>
                ) : null}
            </View>
        </>
    );
}

const styles = StyleSheet.create({
    section: {
        marginBottom: 32,
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#31204D',
        marginBottom: 10,
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
    }
});