/**
 * @fileoverview Layout principal de la aplicación.
 * Se encarga de cargar las fuentes personalizadas, gestionar el estado 
 * del SplashScreen durante la carga, y definir el enrutador principal (Stack) 
 * con un encabezado (header) de navegación global y dinámico.
 *
 * @requires expo-router
 * @requires expo-font
 * @requires expo-splash-screen
 */

import { Stack } from "expo-router";
import { StyleSheet, View } from "react-native";
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import { useFonts } from 'expo-font';
import * as SplashScreen from 'expo-splash-screen';
import { useEffect } from 'react';
import { SafeAreaView } from 'react-native-safe-area-context';



SplashScreen.preventAutoHideAsync();

/**
 * Componente Layout principal.
 * renderiza el sistema de navegación por pila (Stack).
 *
 * @returns {JSX.Element | null} El contenedor de navegación o null si las fuentes están cargando.
 */
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

        <SafeAreaView style={{ flex: 1, backgroundColor: Colors.backgroundLight }}>
            <Stack
                screenOptions={{
                    /**
                     * Renderizador de header.
                     * En la home muestra el logo (Turismo Necochea)
                     * En las demás pantallas muestra el título de la pantalla y el botón de retroceso
                     */
                    header: (props) => {
                        const currentTitle = props.options.title || props.route.name;

                        // Detectamos si hay una pantalla antes en el Stack
                        const hasBackButton = props.back ? true : false;

                        // Si no hay botón de atrás, asumimos que es la Home de las Tabs y mostramos el Logo
                        const showLogo = !hasBackButton;

                        return (
                            <View style={[
                                styles.customHeaderContainer
                            ]}>
                                <HeaderPage
                                    title={currentTitle}
                                    logo={showLogo}
                                    canGoBack={hasBackButton}
                                    onBackPress={() => props.navigation.goBack()}
                                />
                            </View>
                        );
                    },
                    contentStyle: { backgroundColor: Colors.backgroundLight },
                }}
            >
                <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
            </Stack>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    customHeaderContainer: {
        backgroundColor: Colors.backgroundLight,
        flexDirection: 'row',
        justifyContent: 'center',
        alignItems: 'center',
        width: '100%',
    },
});