import React from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image, Linking, Alert, Dimensions, Platform } from 'react-native';
import { Stack, Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../../constants/Styles';
import { LinearGradient } from 'expo-linear-gradient';
import { Logo } from '../../assets/images';
import ContactButton from '../../components/ContactButton';
import SocialButton from '../../components/SocialButton';

const { width } = Dimensions.get('window');

export default function ContactoScreen() {
    const contactInfo = {
        phone: '+54 2262 425665',
        whatsapp: '+54 2262 431155',
        email: 'turismo@necochea.tur.ar',
        instagram: 'necocheatur',
        facebook: 'necocheatur',
        youtube: 'necocheatur',
        tiktok: 'necocheatur',
        website: 'https://necochea.tur.ar',
        address: 'Av. 2 y Calle 87, Necochea, Buenos Aires',
    };

    const handleOpenURL = async (url, errorMessage = 'No se pudo abrir el enlace.') => {
        try {
            await Linking.openURL(url);
        } catch (error) {
            Alert.alert('Error', errorMessage);
        }
    };

    const handleMap = async () => {
        const query = encodeURIComponent(contactInfo.address);
        const nativeUrl = Platform.select({
            ios: `maps:0,0?q=${query}`,
            android: `geo:0,0?q=${query}`,
            default: `https://www.google.com/maps/search/?api=1&query=${query}`
        });
        const webUrl = `https://www.google.com/maps/search/?api=1&query=${query}`;

        try {
            const supported = await Linking.canOpenURL(nativeUrl);
            if (supported) {
                await Linking.openURL(nativeUrl);
            } else {
                await Linking.openURL(webUrl);
            }
        } catch (error) {
            handleOpenURL(webUrl, 'No se pudo abrir la aplicación de mapas.');
        }
    };

    return (
        <View style={styles.container}>
            <Tabs.Screen options={{
                title: "Contacto",
                headerShown: false
            }} />

            <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContainer}>
                <LinearGradient
                    colors={['#36274E', '#5B4282']}
                    style={styles.headerGradient}
                    start={{ x: 0, y: 0 }}
                    end={{ x: 1, y: 1 }}
                >
                    <View style={styles.logoContainer}>
                        <Logo />
                    </View>
                    <Text style={styles.headerTitle}>Necochea</Text>
                    <Text style={styles.headerSubtitle}>Secretaría de Turismo</Text>
                </LinearGradient>

                <View style={styles.content}>
                    <Text style={styles.sectionTitle}>Canales de Atención</Text>

                    <ContactButton
                        iconName="logo-whatsapp"
                        iconColor="#25D366"
                        iconBgColor="#E8F8EF"
                        label="WhatsApp"
                        value={contactInfo.whatsapp}
                        onPress={() => handleOpenURL(`https://wa.me/${contactInfo.whatsapp}`, 'No se pudo abrir WhatsApp. Asegúrese de tener la aplicación instalada.')}
                    />

                    <ContactButton
                        iconName="call"
                        iconColor="#007AFF"
                        iconBgColor="#EAF2FD"
                        label="Teléfono de Informes"
                        value={contactInfo.phone}
                        onPress={() => handleOpenURL(`tel:${contactInfo.phone.replace(/\s+/g, '')}`, 'No se puede realizar la llamada desde este dispositivo.')}
                    />

                    <ContactButton
                        iconName="mail"
                        iconColor="#FF3B30"
                        iconBgColor="#FDEEEE"
                        label="Correo Electrónico"
                        value={contactInfo.email}
                        onPress={() => handleOpenURL(`mailto:${contactInfo.email}`, 'No se pudo abrir la aplicación de correo electrónico.')}
                    />

                    <ContactButton
                        iconName="globe"
                        iconColor="#5B4282"
                        iconBgColor="#F1EFFF"
                        label="Sitio Web Oficial"
                        value={contactInfo.website}
                        onPress={() => handleOpenURL(contactInfo.website, 'No se pudo abrir el sitio web.')}
                    />

                    <Text style={styles.sectionTitle}>Redes Sociales</Text>

                    <View style={styles.socialContainer}>
                        <SocialButton
                            iconName="instagram"
                            onPress={() => handleOpenURL(`https://www.instagram.com/${contactInfo.instagram}/`, 'No se pudo abrir Instagram.')}
                        />

                        <SocialButton
                            iconName="facebook"
                            onPress={() => handleOpenURL(`https://www.facebook.com/${contactInfo.facebook}/`, 'No se pudo abrir Facebook.')}
                        />

                        <SocialButton
                            iconName="youtube"
                            onPress={() => handleOpenURL(`https://www.youtube.com/@${contactInfo.youtube}`, 'No se pudo abrir YouTube.')}
                        />

                        <SocialButton
                            iconName="tik-tok"
                            onPress={() => handleOpenURL(`https://www.tiktok.com/@${contactInfo.tiktok}`, 'No se pudo abrir TikTok.')}
                        />
                    </View>

                    <Text style={styles.sectionTitle}>¿Dónde estamos?</Text>

                    <TouchableOpacity style={styles.addressCard} onPress={handleMap}>
                        <View style={styles.addressHeader}>
                            <Ionicons name="location" size={24} color="#36274E" />
                            <Text style={styles.addressTitle}>Oficina de Informes</Text>
                        </View>
                        <Text style={styles.addressText}>{contactInfo.address}</Text>
                        <View style={styles.mapButton}>
                            <Text style={styles.mapButtonText}>Cómo llegar en el mapa</Text>
                            <Ionicons name="map-outline" size={16} color="#FAFAFD" style={{ marginLeft: 6 }} />
                        </View>
                    </TouchableOpacity>

                </View>
            </ScrollView>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    scrollContainer: {
        paddingBottom: 40,
    },
    headerGradient: {
        paddingTop: 50,
        paddingBottom: 30,
        paddingHorizontal: 24,
        alignItems: 'center',
        borderBottomLeftRadius: 28,
        borderBottomRightRadius: 28,
        elevation: 6,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.15,
        shadowRadius: 8,
    },
    logoContainer: {
        backgroundColor: 'rgba(255, 255, 255, 0.9)',
        borderRadius: 50,
        padding: 8,
        marginBottom: 16,
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
    },
    headerTitle: {
        fontSize: 24,
        fontFamily: 'Gotham-Black',
        color: '#FAFAFD',
        marginBottom: 4,
        textAlign: 'center',
    },
    headerSubtitle: {
        fontSize: 16,
        fontFamily: 'Gotham-Bold',
        color: '#D2C9E3',
        marginBottom: 12,
        textAlign: 'center',
        textTransform: 'uppercase',
        letterSpacing: 1,
    },
    headerDescription: {
        fontSize: 14,
        fontFamily: 'Gotham-Book',
        color: '#F0EFFF',
        textAlign: 'center',
        lineHeight: 20,
        maxWidth: '90%',
    },
    content: {
        paddingHorizontal: 20,
        paddingTop: 16,
    },
    sectionTitle: {
        fontSize: 18,
        fontFamily: 'Gotham-Bold',
        color: Colors.textColor,
        marginTop: 24,
        marginBottom: 12,
    },

    socialContainer: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        gap: 12,
    },

    addressCard: {
        backgroundColor: '#FFF',
        borderRadius: 16,
        padding: 16,
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.05,
        shadowRadius: 3,
    },
    addressHeader: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 8,
    },
    addressTitle: {
        fontSize: 15,
        fontFamily: 'Gotham-Bold',
        color: Colors.textColor,
        marginLeft: 8,
    },
    addressText: {
        fontSize: 14,
        fontFamily: 'Gotham-Book',
        color: Colors.textColorLight,
        lineHeight: 20,
        marginBottom: 16,
    },
    mapButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#36274E',
        borderRadius: 12,
        paddingVertical: 12,
    },
    mapButtonText: {
        fontSize: 14,
        fontFamily: 'Gotham-Bold',
        color: '#FAFAFD',
    },
});
