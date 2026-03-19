import React from "react";
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity } from "react-native";
import { useLocalSearchParams, useRouter, Stack } from "expo-router";
import { balnearios } from "../../assets/mokup";
import { Colors } from "../../constants/Styles";
import { Ionicons } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';

export default function BalnearioDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const insets = useSafeAreaInsets();
    
    // Find balneario data
    const item = balnearios.find((b) => b.idBalneario.toString() === id);

    if (!item) {
        return (
            <View style={styles.centered}>
                <Text style={styles.errorText}>Balneario no encontrado</Text>
            </View>
        );
    }

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

    // Temporary mock images
    const imageUrl = `https://picsum.photos/seed/${item.idBalneario + 30}/800/600`;
    const mapUrl = `https://picsum.photos/seed/map/800/400`; 

    // Formatear titulo
    const nombreC = item.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    const displayTitle = nombreC.toLowerCase().includes('balneario') ? nombreC : `Balneario ${nombreC}`;
    
    // Formatear servicios
    const detallesStr = item.servicios 
        ? item.servicios.split('|').map(s => s.trim().charAt(0).toUpperCase() + s.trim().slice(1)).filter(Boolean).join(', ') 
        : '';

    return (
        <View style={styles.container}>
            <ScrollView
                style={styles.pageContent} showsVerticalScrollIndicator={false} bounces={false}
                contentContainerStyle={{ paddingTop: insets.top }} 
            >
                <Stack.Screen options={{ headerShown: false }} />

                <View style={styles.imageContainer}>
                    <Image source={{ uri: imageUrl }} style={styles.headerImage} resizeMode="cover" />
                </View>

                <View style={styles.contentContainer}>
                    <Text style={styles.title}>{displayTitle}</Text>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Detalles</Text>
                        <Text style={styles.sectionText}>{detallesStr}</Text>
                    </View>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Contacto</Text>

                        {item.telefono ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-whatsapp" size={20} color={Colors.textColor} style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.telefono)}</Text>
                            </View>
                        ) : null}

                        {ig ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-instagram" size={20} color={Colors.textColor} style={styles.contactIcon} />
                                <Text style={styles.contactText}>{ig}</Text>
                            </View>
                        ) : null}

                        {fb ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="logo-facebook" size={20} color={Colors.textColor} style={styles.contactIcon} />
                                <Text style={styles.contactText}>{fb}</Text>
                            </View>
                        ) : null}
                        
                        {item.mail ? (
                            <View style={styles.contactRow}>
                                <Ionicons name="mail-outline" size={20} color={Colors.textColor} style={styles.contactIcon} />
                                <Text style={styles.contactText}>{String(item.mail)}</Text>
                            </View>
                        ) : null}
                    </View>

                    <View style={styles.section}>
                        <Text style={styles.sectionTitle}>Ubicación</Text>
                        <View style={styles.contactRow}>
                            <Ionicons name="location-outline" size={20} color={Colors.textColor} style={styles.contactIcon} />
                            <Text style={styles.contactText}>{String(item.direccion)}</Text>
                        </View>
                        <Image source={{ uri: mapUrl }} style={styles.mapImage} />
                    </View>
                </View>
            </ScrollView>
            
            <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color={Colors.textColor} />
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    pageContent: {
        flex: 1,
    },
    container: {
        flex: 1,
        backgroundColor: Colors.backgroundLight,
    },
    centered: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: Colors.backgroundLight,
    },
    errorText: {
        fontSize: 16,
        color: Colors.textColor,
    },
    imageContainer: {
        width: '100%',
        height: 250,
        position: 'relative',
    },
    headerImage: {
        width: '100%',
        height: '100%',
    },
    backButton: {
        position: 'absolute',
        top: 50,
        left: 20,
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(255, 255, 255, 0.7)',
        justifyContent: 'center',
        alignItems: 'center',
    },
    contentContainer: {
        padding: 24,
    },
    title: {
        fontSize: 26,
        fontWeight: '900',
        color: Colors.textColor,
        marginBottom: 24, 
    },
    section: {
        marginBottom: 32,
    },
    sectionTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: Colors.textColor,
        marginBottom: 12,
    },
    sectionText: {
        fontSize: 15,
        color: '#554E66',
        lineHeight: 22,
    },
    contactRow: {
        gap: 8,
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 16,
    },
    contactIcon: {
        width: 24,
        textAlign: 'center',
    },
    contactText: {
        fontSize: 15,
        color: '#554E66',
        flex: 1,
    },
    mapImage: {
        width: '100%',
        height: 180,
        borderRadius: 12,
        marginTop: 16,
    }
});
