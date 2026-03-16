import { View, Text, StyleSheet } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Link } from 'expo-router';
import { Colors } from "../constants/Styles";
export default function Home() {
    return (
        <ScreenLayout>
            <View style={styles.container}>
                <Text style={styles.primaryText}>Home</Text>
                <Link href={`/Hoteles`} asChild>
                    <Text style={styles.primaryText}>Hoteles</Text>
                </Link>
            </View>
        </ScreenLayout>
    );
}
const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    primaryText: {
        color: Colors.textColor,
    }
});