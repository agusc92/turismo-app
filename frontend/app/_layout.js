import { Stack } from "expo-router";
import { StyleSheet, View } from "react-native";
import { Colors } from "../constants/Styles";
import { StatusBar } from "expo-status-bar";
import HeaderPage from "../components/HeaderPage";
import { useFonts } from 'expo-font';
import * as SplashScreen from 'expo-splash-screen';
import { useEffect } from 'react';
import { SafeAreaView } from 'react-native-safe-area-context';

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
        <SafeAreaView style={{ flex: 1, backgroundColor: Colors.backgroundLight }}>

            <Stack
                screenOptions={{
                    header: (props) => {
                        const currentTitle = props.options.title || props.route.name;

                        // 💡 Detectamos si hay una pantalla antes en el Stack
                        const hasBackButton = props.back ? true : false;

                        // 💡 Si no hay botón de atrás, asumimos que es la Home de las Tabs y mostramos el Logo
                        const showLogo = !hasBackButton;

                        return (
                            <View style={[
                                styles.customHeaderContainer
                            ]}>
                                <HeaderPage
                                    title={currentTitle}
                                    logo={showLogo} // 👈 Automático: Logo en Home, Flecha en las sub-pantallas
                                    canGoBack={hasBackButton} // 👈 Le avisa si debe renderizar la flecha
                                    onBackPress={() => props.navigation.goBack()} // 👈 Acción nativa para ir atrás
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