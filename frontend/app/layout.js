import { SafeAreaView } from "react-native-safe-area-context";
import { View, StyleSheet } from "react-native";
import { Colors } from "../constants/Styles";
export default function Layout() {
    return (
        <SafeAreaView style={styles.notificationBar} edges={['top']}>
            <Text>Layout</Text>
            <View style={styles.container} >
                <StatusBar style="light" />
            </View>
        </SafeAreaView>
    );

}
const pagina = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    }
});