/**
 * @fileoverview Pantalla principal (Inicio) de la aplicación de turismo de Necochea.
 * Contiene la configuración de la pestaña principal, el carrusel de eventos 
 * destacados y el menú de navegación hacia las distintas categorías de la app.
 * 
 * @requires react-native
 * @requires expo-router
 */
import { View, Text, StyleSheet, ScrollView } from "react-native";
import { Tabs } from 'expo-router';
import { ScreenLayout } from "../components/ScreenLayout";
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import MenuCard from "../components/MenuCard";
import Carousel from "../components/Carousel";
import { getResourceImage } from "../assets/images";
import { useImagenesHome } from "./hooks/useImagenesHome";


/**
 * Componente funcional Home.
 * Representa el punto de entrada principal del usuario al abrir la aplicación.
 * 
 * @returns {JSX.Element} La vista renderizada de la pantalla de inicio.
 */
export default function Home() {
    const { imagenes } = useImagenesHome();
    const getHomeImage = (image, type) => typeof image === "string" && image.trim() ? image : getResourceImage(type);

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

                <View style={styles.menuContainer}>
                    <MenuCard title="Alojamiento" image={getHomeImage(imagenes?.alojamientos, "alojamiento")} href="/alojamiento/alojamientos" />
                    <MenuCard title="Gastronomía" image={getHomeImage(imagenes?.gastronomicos, "gastronomico")} href="/gastronomico/gastronomico" />
                    <MenuCard title="Balnearios" image={getHomeImage(imagenes?.balnearios, "balneario")} href="/balneario/balnearios" />
                    <MenuCard title="Actividades" image={getHomeImage(imagenes?.actividades, "actividad")} href="/actividad/actividades" />
                    <MenuCard title="Eventos" image={getHomeImage(imagenes?.eventos, "evento")} href="/evento/eventos" />
                    <MenuCard title="Complejos" image={getHomeImage(imagenes?.complejos, "complejo")} href="/complejo/complejos" />
                </View>
            </ScrollView>
        </ScreenLayout>
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
