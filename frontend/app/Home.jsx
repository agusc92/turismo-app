import { View, Text, StyleSheet } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Link, Stack } from 'expo-router';
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
export default function Home() {

    return (
        <ScreenLayout>
            <Stack.Screen options={{
                title: "Necochea",
                // 👇 Sobreescribimos el header solo para esta pantalla
                headerTitle: (props) => <HeaderPage title={props.children} logo={true} />
            }} />
            <View style={styles.container}>
                <Text style={styles.primaryText}>Home</Text>
                <Link href={`/Alojamiento`} asChild>
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