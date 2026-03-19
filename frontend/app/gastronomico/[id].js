import React from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, SafeAreaView, Platform } from 'react-native';
import { useLocalSearchParams, Stack, router } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { gastronomico } from '../../assets/mokup';

export default function GastronomicoDetalle() {
    const { id } = useLocalSearchParams();
    const itemId = parseInt(id, 10);
    
    // As gastronomico is an array of arrays in mokup.js
    const dataGastronomica = gastronomico[0] || [];
    const item = dataGastronomica.find(g => g.idGastronomico === itemId);

    if (!item) {
        return (
            <View style={styles.errorContainer}>
                <Stack.Screen options={{ headerShown: true, title: 'No encontrado' }} />
                <Text style={styles.errorText}>El lugar no existe.</Text>
            </View>
        );
    }

    // Parsers
    const tipos = item.tipo ? item.tipo.map(t => t.charAt(0).toUpperCase() + t.slice(1)).join(' / ') : '';
    const menus = item.menu ? item.menu.map(m => m.charAt(0).toUpperCase() + m.slice(1)).join(', ') : '';
    const extras = item.extras ? item.extras.split('|').map(e => e.trim().charAt(0).toUpperCase() + e.trim().slice(1)).join(', ') : '';
    
    // Redes parser
    let fb = '', ig = '', tw = '';
    if (item.redesSociales) {
        const redesList = item.redesSociales.split('|').map(r => r.trim());
        redesList.forEach(red => {
            if (red.toLowerCase().startsWith('fb:')) fb = red.slice(3).trim();
            if (red.toLowerCase().startsWith('ig:')) ig = red.slice(3).trim();
            if (red.toLowerCase().startsWith('x:')) tw = red.slice(2).trim();
        });
    }

    // We'll trust whatever is in the image, or fallback to dummy
    const imageUrl = item.imagen && item.imagen.startsWith('http') 
        ? item.imagen 
        : `https://dummyimage.com/600x400/2a61a3/ffffff.png&text=${encodeURIComponent(item.nombre)}`;

    let mapImageUrl = 'https://necochea.tur.ar/wp-content/uploads/2021/11/necochea-mapa.jpg';

    return (
        <View style={styles.container}>
            <Stack.Screen options={{ headerShown: false }} />
            
            <ScrollView style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}>
                <View style={styles.headerImageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.image} resizeMode="cover" />
                </View>
                
                <View style={styles.contentContainer}>
                    {/* Header Title section */}
                    <Text style={styles.title}>{item.nombre}</Text>
                    {tipos ? <Text style={styles.subtitle}>{tipos}</Text> : null}
                    
                    {/* Horario */}
                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Horario</Text>
                        <Text style={styles.sectionText}>{item.horario ? String(item.horario) : 'No especificado'}</Text>
                    </View>

                    {/* Menús Especiales */}
                    {menus ? (
                        <View style={styles.section}>
                            <Text style={styles.sectionTitle}>Menús Especiales</Text>
                            <Text style={styles.sectionText}>{menus}</Text>
                        </View>
                    ) : null}

                    {/* Extras */}
                    {extras ? (
                        <View style={styles.section}>
                            <Text style={styles.sectionTitle}>Extras</Text>
                            <Text style={styles.sectionText}>{extras}</Text>
                        </View>
                    ) : null}

                    {/* Contacto */}
                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Contacto</Text>
                        
                        {item.telefono ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-whatsapp" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.telefono)}</Text>
                            </View>
                        ) : null}

                        {ig ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-instagram" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{ig}</Text>
                            </View>
                        ) : null}

                        {fb ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-facebook" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{fb}</Text>
                            </View>
                        ) : null}

                        {item.tiendaOnline ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="globe-outline" size={20} color="#31204D" style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.tiendaOnline).replace(/https?:\/\//, '').trim()}</Text>
                            </View>
                        ) : null}
                    </View>

                    {/* Ubicación */}
                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Ubicación</Text>
                        <View style={styles.contactRow}>
                            <Ionicons name="location-outline" size={20} color="#31204D" style={styles.contactIcon} />
                            <Text style={styles.contactText}>{item.direccion ? String(item.direccion) : 'No especificada'}</Text>
                        </View>
                    </View>

                    {/* Mapa estático */}
                    <View style={styles.mapWrap}>
                        <Image source={{ uri: mapImageUrl }} style={styles.mapImage} resizeMode="cover" />
                    </View>
                    
                </View>
            </ScrollView>

            {/* Back Button floating overlay */}
            <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color="#555" />
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FAFAFD', // Fondo súper claro tirando a lila
    },
    errorContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f5f5f5',
    },
    errorText: {
        fontSize: 18,
        color: '#333',
    },
    pageContent: {
        flex: 1,
    },
    headerImageContainer: {
        width: '100%',
        height: 260,
        backgroundColor: '#e0e0e0',
    },
    image: {
        width: '100%',
        height: '100%',
    },
    backButton: {
        position: 'absolute',
        top: Platform.OS === 'ios' ? 60 : 40,
        left: 20,
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(255, 255, 255, 0.65)',
        justifyContent: 'center',
        alignItems: 'center',
        elevation: 3,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.2,
        shadowRadius: 3,
    },
    contentContainer: {
        paddingHorizontal: 25,
        paddingTop: 30,
        backgroundColor: '#FAFAFD',
    },
    title: {
        fontSize: 32,
        fontWeight: '900',
        color: '#31204D',
        textTransform: 'capitalize',
        letterSpacing: -0.5,
    },
    subtitle: {
        fontSize: 16,
        color: '#4B465C',
        marginTop: 5,
        marginBottom: 30,
    },
    section: {
        marginBottom: 25,
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#31204D',
        marginBottom: 10,
    },
    sectionText: {
        fontSize: 15,
        color: '#4B465C',
        lineHeight: 24,
    },
    contactRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 12,
    },
    contactIcon: {
        marginRight: 12,
    },
    contactText: {
        fontSize: 15,
        color: '#4B465C',
    },
    mapWrap: {
        marginBottom: 50,
        height: 200,
        borderRadius: 16,
        overflow: 'hidden',
        backgroundColor: '#ddd',
    },
    mapImage: {
        width: '100%',
        height: '100%',
    }
});
