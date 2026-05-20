import { Stack } from "expo-router";
import { StyleSheet } from "react-native";
import { Colors } from "../constants/Styles";
import { StatusBar } from "expo-status-bar";
import HeaderPage from "../components/HeaderPage";
import { useFonts } from 'expo-font';
import * as SplashScreen from 'expo-splash-screen';
import { useEffect } from 'react';

// Previene que la pantalla de carga se oculte antes de que se carguen las fuentes
SplashScreen.preventAutoHideAsync();
export default function Layout() {
    const [loaded, error] = useFonts({
        'Gotham-Black': require('../assets/fuentes/Gotham-Black.otf'),
        'Gotham-Bold': require('../assets/fuentes/GOTHMBOL.ttf'),
        'Gotham-Medium': require('../assets/fuentes/GOTHMMED.ttf'),
        'Gotham-Light': require('../assets/fuentes/GOTHMLIG.ttf'),
        'Gotham-Book': require('../assets/fuentes/GOTHMBOK.ttf'),
        'Gotham-Ultra': require('../assets/fuentes/Gotham-Ultra.otf'),
    });

    useEffect(() => {
        if (loaded || error) {
            SplashScreen.hideAsync();
        }
    }, [loaded, error]);

    if (!loaded && !error) {
        return null;
    }

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
                        fontFamily: 'Gotham-Ultra',
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