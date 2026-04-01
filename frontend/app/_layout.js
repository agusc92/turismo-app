import { Stack } from "expo-router";
import { StyleSheet } from "react-native";
import { Colors } from "../constants/Styles";
import { StatusBar } from "expo-status-bar";
import HeaderPage from "../components/HeaderPage";

export default function Layout() {
    return (
        <>
            {/* OJO: En tu código tenías backgroundColor="#000000" (negro).
              Si quieres que se vea como en tus capturas (blanco con letras oscuras), 
              usa style="dark" y backgroundColor="transparent" o el color de tu fondo. 
              Si realmente la quieres negra, usa style="light" y backgroundColor="#000000".
            */}
            <StatusBar style="light" />

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