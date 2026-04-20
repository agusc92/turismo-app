
import { View, Text, StyleSheet, ScrollView, Image, Dimensions } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Tabs } from 'expo-router';
import { Colors } from "../constants/Styles";
import HeaderPage from "../components/HeaderPage";
import MenuCard from "../components/MenuCard";

import Carousel from "../components/Carousel";
const { width } = Dimensions.get('window');

export default function Home() {

    return (
        <ScreenLayout>
            <Tabs.Screen options={{
                title: "Inicio",
                headerShown: true,
                headerTitle: () => <HeaderPage title="Necochea" logo={true} />,
                headerStyle: { backgroundColor: Colors.backgroundLight },
                headerShadowVisible: false,
                headerTitleAlign: 'center',
            }} />

            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false}>
                <Text style={styles.sectionTitle}>Eventos destacados</Text>

                <Carousel />

                <View style={styles.menuContainer}>
                    <MenuCard title="Alojamiento" image='https://files.catbox.moe/gq2xwv.webp' href="/alojamiento/alojamientos" />
                    <MenuCard title="Gastronomía" image='https://files.catbox.moe/7v2awu.webp' href="/gastronomico/gastronomico" />
                    <MenuCard title="Balnearios" image='https://files.catbox.moe/j1nwb0.webp' href="/balneario/balnearios" />
                    <MenuCard title="Actividades" image='https://files.catbox.moe/fhgglt.JPG' href="/actividad/actividades" />
                    <MenuCard title="Eventos" image='https://imgs.search.brave.com/sSfnVSEYmGz8p78KR8cDIUTL7zMLg6dVvTqbfFuwhX0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9uZWNv/Y2hlYS50dXIuYXIv/d3AtY29udGVudC91/cGxvYWRzLzIwMjYv/MDMvMTYtMDMtRk9U/Ty1wcm9tby1OZWNv/Y2hlYS1lbi1BeWFj/dWNoby5qcGVn' href="/evento/eventos" />
                </View>
            </ScrollView>

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
    },
    pageContent: {
        backgroundColor: Colors.backgroundLight,
        flex: 1,
    },
    sectionTitle: {
        fontSize: 22,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginHorizontal: 20,
        marginTop: 20,
        marginBottom: 15,
    },
    carouselItemContainer: {
        width: width, // Full screen width to allow paging snapping
        paddingHorizontal: 20, // Inner padding so it doesn't touch the screen borders
        paddingBottom: 20,
    },
    carouselItem: {
        borderRadius: 16,
        overflow: 'hidden',
        backgroundColor: '#fff',
        elevation: 4, // Android shadow
        shadowColor: '#000', // iOS shadow
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 6,
    },
    carouselImage: {
        width: '100%',
        height: 200, // Make it a bit taller since it's full width now
    },
    textContainer: {
        padding: 15,
    },
    carouselText: {
        fontSize: 18,
        fontWeight: '700',
        color: Colors.textColor,
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