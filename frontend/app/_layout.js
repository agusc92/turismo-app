import { Link, Stack } from "expo-router";
import { View, Text, StyleSheet, Pressable } from "react-native";
import { Colors } from "../constants/Styles";
import { StatusBar } from "expo-status-bar";
import { SafeAreaView } from "react-native-safe-area-context";
import { Logo } from "../assets/images";
export default function Layout() {
    return (
        // <SafeAreaView style={styles.notificationBar} edges={['top']}>
        <View style={styles.container} >
            <StatusBar style="light" />
            <Stack

                screenOptions={{
                    headerStyle: {
                        backgroundColor: Colors.backgroundLight,
                    },
                    headerTintColor: styles.primaryText.color,
                    headerRight: () => (<View ><Logo /></View>),
                    headerShadowVisible: false, // Elimina la separación entre header y página
                    headerTitleAlign: 'center', // Centra el texto del header
                }}
            />
        </View>
        // </SafeAreaView>

    );


}
const styles = StyleSheet.create({
    container: {
        flex: 1,

    },
    notificationBar: {

        flex: 1,
    },
    primaryText: {
        color: Colors.textColor,
    }
})