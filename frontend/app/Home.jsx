/**
 * @fileoverview Pantalla principal (Inicio) de la aplicación de turismo de Necochea.
 * Contiene la configuración de la pestaña principal, el carrusel de eventos 
 * destacados y el menú de navegación hacia las distintas categorías de la app.
 * 
 * @requires react-native
 * @requires expo-router
 */
import { View, Text, StyleSheet, ScrollView, Image, Dimensions } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Tabs } from 'expo-router';
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import MenuCard from "../components/MenuCard";

import Carousel from "../components/Carousel";

/**
 * Componente funcional Home.
 * Representa el punto de entrada principal del usuario al abrir la aplicación.
 * 
 * @returns {JSX.Element} La vista renderizada de la pantalla de inicio.
 */
export default function Home() {

    return (
        <ScreenLayout>
            {/* 
              * Configuración de la cabecera (Header) específica para esta pestaña.
              * Reemplaza el header por defecto de Expo Router por un componente personalizado.
              */}
            <Tabs.Screen options={{
                title: "Inicio",
                headerShown: true,

                header: () => (
                    <View style={styles.customHeaderContainer}>
                        <HeaderPage title="Necochea" logo={true} />
                    </View>
                ),
            }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false}>
                {/* Sección de eventos destacados */}
                <Text style={styles.sectionTitle}>Eventos destacados</Text>

                <Carousel />
                {/* 
                  * Grilla del menú principal. 
                  * Cada MenuCard actúa como enlace a una sección específica usando expo-router.
                  */}
                <View style={styles.menuContainer}>
                    <MenuCard title="Alojamiento" image='https://files.catbox.moe/gq2xwv.webp' href="/alojamiento/alojamientos" />
                    <MenuCard title="Gastronomía" image='https://files.catbox.moe/7v2awu.webp' href="/gastronomico/gastronomico" />
                    <MenuCard title="Balnearios" image='https://files.catbox.moe/j1nwb0.webp' href="/balneario/balnearios" />
                    <MenuCard title="Actividades" image='https://files.catbox.moe/fhgglt.JPG' href="/actividad/actividades" />
                    <MenuCard title="Eventos" image='https://imgs.search.brave.com/sSfnVSEYmGz8p78KR8cDIUTL7zMLg6dVvTqbfFuwhX0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9uZWNv/Y2hlYS50dXIuYXIv/d3AtY29udGVudC91/cGxvYWRzLzIwMjYv/MDMvMTYtMDMtRk9U/Ty1wcm9tby1OZWNv/Y2hlYS1lbi1BeWFj/dWNoby5qcGVn' href="/evento/eventos" />
                    <MenuCard title="Complejos" image='https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500&auto=format&fit=crop&q=60' href="/complejo/complejos" />
                </View>
            </ScrollView>

        </ScreenLayout>
    );
}
/**
 * Hojas de estilo para la pantalla Home.
 * @constant {StyleSheet.NamedStyles}
 */
const styles = StyleSheet.create({
    customHeaderContainer: {
        backgroundColor: Colors.backgroundLight,
        flexDirection: 'row',
        justifyContent: 'center',
        alignItems: 'center',
        width: '100%',
    },
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    pageContent: {
        backgroundColor: Colors.backgroundLight,
        flex: 1,
    },
    sectionTitle: {
        fontSize: 22,
        fontFamily: 'Gotham-Black',
        color: Colors.textColor,
        marginHorizontal: 20,
        marginTop: 20,
        marginBottom: 15,
    },
    menuContainer: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        justifyContent: 'space-between',
        paddingHorizontal: 20,
        marginTop: 10,
        marginBottom: 40,
        gap: 18,
    }
});