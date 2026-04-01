import { Stack } from "expo-router";
import { StyleSheet } from "react-native";
import { Colors } from "../constants/Styles";
import { StatusBar } from "expo-status-bar";
import HeaderPage from "../components/HeaderPage";

export default function Layout() {
    return (
        <>
            {/* Forzar statusBar semitransparente off para evitar que Android rompa el SafeAreaInsets al re-abrir la app */}
            <StatusBar style="light" backgroundColor="#000000" translucent={false} />

            <Stack
                screenOptions={{
                    headerTitle: (props) => {
                        return <HeaderPage title={props.children} logo={false} />
                    },
                    headerStyle: {
                        backgroundColor: Colors.backgroundLight,
                    },
                    headerTintColor: styles.primaryText.color,
                    headerShadowVisible: false,
                    headerTitleAlign: 'center',
                }}
            >
                <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
            </Stack>
        </>
    );
}

const styles = StyleSheet.create({
    primaryText: {
        color: Colors.textColor,
    },
});