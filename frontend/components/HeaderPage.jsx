import { View, Text, StyleSheet } from "react-native";
import { Logo } from "../assets/images";
import { Colors } from "../constants/Styles";

export default function HeaderPage({ title, logo = false }) {
    return (
        <View style={styles.headerTitle}>
            {logo && (
                <View>
                    <Logo />
                </View>
            )}
            <Text style={styles.headerText}>{title}</Text>

        </View>
    );
}

const styles = StyleSheet.create({
    headerTitle: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 10,
    },
    headerText: {
        color: Colors.textColor,
        fontSize: 28,
        fontWeight: 'bold',
        textTransform: 'capitalize'
    }
});