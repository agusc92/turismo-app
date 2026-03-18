import React, { useState } from 'react';
import { View, Text, StyleSheet, FlatList, Image, TouchableOpacity, Modal, SafeAreaView } from 'react-native';
import { Stack, router } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { gastronomico, tipoGastronomico, menu } from '../../assets/mokup';

export default function GastronomicoList() {
    // Gastronomico in mokup is an array of arrays: [[{...}, {...}]]
    const dataGastronomica = gastronomico[0] || [];

    const [selectedTipo, setSelectedTipo] = useState(null);
    const [selectedMenu, setSelectedMenu] = useState(null);

    const [showTipoModal, setShowTipoModal] = useState(false);
    const [showMenuModal, setShowMenuModal] = useState(false);

    // Filter logic
    const filteredData = dataGastronomica.filter(item => {
        let matchesTipo = true;
        let matchesMenu = true;

        if (selectedTipo) {
            // El tipo en data es un array de strings (ej: ["cafe", "cerveceria"])
            matchesTipo = item.tipo && item.tipo.includes(selectedTipo.nombre);
        }

        if (selectedMenu) {
            // El menu es un array de strings (ej: ["vegetariano", "diabetico"])
            matchesMenu = item.menu && item.menu.includes(selectedMenu.tipo);
        }

        return matchesTipo && matchesMenu;
    });

    const renderCustomPicker = (title, items, labelKey, valueKey, selectedItem, onSelect, onClose) => (
        <Modal visible={true} transparent={true} animationType="fade">
            <TouchableOpacity style={styles.modalOverlay} activeOpacity={1} onPress={onClose}>
                <View style={styles.modalContent}>
                    <Text style={styles.modalTitle}>{title}</Text>
                    <FlatList
                        data={[{ [labelKey]: 'Todos', isClear: true }, ...items]}
                        keyExtractor={(i, index) => index.toString()}
                        renderItem={({ item }) => {
                            const isSelected = item.isClear 
                                ? !selectedItem 
                                : selectedItem && selectedItem[labelKey] === item[labelKey];

                            return (
                                <TouchableOpacity 
                                    style={styles.modalItem}
                                    onPress={() => {
                                        if (item.isClear) {
                                            onSelect(null);
                                        } else {
                                            onSelect(item);
                                        }
                                        onClose();
                                    }}
                                >
                                    <Text style={[styles.modalItemText, isSelected && styles.modalItemSelectedText]}>
                                        {item[labelKey] ? (item[labelKey].charAt(0).toUpperCase() + item[labelKey].slice(1)) : ''}
                                    </Text>
                                    {isSelected && <Ionicons name="checkmark" size={20} color="#2C1B4D" />}
                                </TouchableOpacity>
                            );
                        }}
                    />
                </View>
            </TouchableOpacity>
        </Modal>
    );

    return (
        <View style={styles.container}>
            <Stack.Screen 
                options={{ 
                    headerShown: true,
                    headerTitle: 'Gastronomía',
                    headerTitleAlign: 'center',
                    headerStyle: { backgroundColor: '#F9F9F9' },
                    headerShadowVisible: false,
                    headerTitleStyle: {
                        color: '#2C1B4D',
                        fontWeight: 'bold',
                        fontSize: 22,
                    },
                    headerLeft: () => (
                        <TouchableOpacity onPress={() => router.back()} style={styles.headerIcon}>
                            <Ionicons name="arrow-back" size={24} color="#2C1B4D" />
                        </TouchableOpacity>
                    ),
                }} 
            />

            <View style={styles.filtersContainer}>
                <TouchableOpacity 
                    style={styles.filterButton} 
                    onPress={() => setShowTipoModal(true)}
                >
                    <Text style={styles.filterText}>
                        {selectedTipo ? (selectedTipo.nombre.charAt(0).toUpperCase() + selectedTipo.nombre.slice(1)) : 'Tipo'}
                    </Text>
                    <Ionicons name="chevron-down" size={16} color="#333" />
                </TouchableOpacity>

                <TouchableOpacity 
                    style={styles.filterButton} 
                    onPress={() => setShowMenuModal(true)}
                >
                    <Text style={styles.filterText}>
                        {selectedMenu ? (selectedMenu.tipo.charAt(0).toUpperCase() + selectedMenu.tipo.slice(1)) : 'Menús especiales'}
                    </Text>
                    <Ionicons name="chevron-down" size={16} color="#333" />
                </TouchableOpacity>
            </View>

            {showTipoModal && renderCustomPicker(
                "Seleccionar Tipo", 
                tipoGastronomico, 
                "nombre", 
                "idTipo", 
                selectedTipo, 
                setSelectedTipo, 
                () => setShowTipoModal(false)
            )}

            {showMenuModal && renderCustomPicker(
                "Menús especiales", 
                menu, 
                "tipo", 
                "idMenu", 
                selectedMenu, 
                setSelectedMenu, 
                () => setShowMenuModal(false)
            )}

            <FlatList
                data={filteredData}
                keyExtractor={(item) => item.idGastronomico.toString()}
                contentContainerStyle={styles.listContainer}
                renderItem={({ item }) => {
                    let subtitle = 'Gastronomía';
                    if (item.tipo && item.tipo.length > 0) {
                        subtitle = item.tipo.map(t => t.charAt(0).toUpperCase() + t.slice(1)).join(' • ');
                    } else if (item.menu && item.menu.length > 0) {
                        subtitle = item.menu.map(m => m.charAt(0).toUpperCase() + m.slice(1)).join(' • ');
                    }

                    // A random image url to match the list style if the item doesn't have a specific working one
                    // We'll use dummy image if it's "url:xxx.com"
                    const fallbackImageUrl = 'https://dummyimage.com/600x400/b3d4fc/2a61a3.png&text=' + encodeURIComponent(item.nombre);
                    const isInvalidUrl = !item.tiendaOnline && !item.extras && item.tipo; // just a rough check, or check if image starts with http
                    // Actually, the mokup items don't have an explicitly valid image URL in the code block provided, so we'll just check it.
                    let imageUrl = fallbackImageUrl;
                    if(item.imagen && item.imagen.startsWith('http')) {
                        imageUrl = item.imagen;
                    }

                    return (
                        <TouchableOpacity 
                            style={styles.card} 
                            onPress={() => router.push(`/gastronomico/${item.idGastronomico}`)}
                        >
                            <Image 
                                source={{ uri: imageUrl }} 
                                style={styles.cardImage} 
                            />
                            <View style={styles.cardInfo}>
                                <Text style={styles.cardTitle}>{item.nombre}</Text>
                                <Text style={styles.cardSubtitle}>{subtitle}</Text>
                            </View>
                        </TouchableOpacity>
                    );
                }}
                ListEmptyComponent={
                    <View style={styles.emptyContainer}>
                        <Text style={styles.emptyText}>No hay resultados con estos filtros.</Text>
                    </View>
                }
            />
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#F9F9F9',
    },
    headerIcon: {
        padding: 5,
        marginLeft: -5,
    },
    filtersContainer: {
        flexDirection: 'row',
        paddingHorizontal: 20,
        paddingBottom: 15,
        borderBottomWidth: 1,
        borderBottomColor: '#EDEDED',
        backgroundColor: '#F9F9F9',
    },
    filterButton: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#F0EFFF', // Light purple/blue tint from mockup
        paddingHorizontal: 15,
        paddingVertical: 8,
        borderRadius: 8,
        marginRight: 10,
    },
    filterText: {
        fontSize: 14,
        color: '#2C1B4D',
        marginRight: 5,
        fontWeight: '600',
    },
    listContainer: {
        padding: 20,
        paddingTop: 15,
    },
    card: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 20,
    },
    cardImage: {
        width: 100,
        height: 70,
        borderRadius: 8,
        marginRight: 15,
        backgroundColor: '#E5E5EA',
    },
    cardInfo: {
        flex: 1,
        justifyContent: 'center',
    },
    cardTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#2C1B4D',
        marginBottom: 4,
        textTransform: 'capitalize',
    },
    cardSubtitle: {
        fontSize: 13,
        color: '#8A819C',
        marginTop: 2,
    },
    emptyContainer: {
        padding: 20,
        alignItems: 'center',
        marginTop: 50,
    },
    emptyText: {
        fontSize: 16,
        color: '#666',
    },
    // Modal Styles
    modalOverlay: {
        flex: 1,
        backgroundColor: 'rgba(0,0,0,0.4)',
        justifyContent: 'flex-end',
    },
    modalContent: {
        backgroundColor: '#fff',
        borderTopLeftRadius: 20,
        borderTopRightRadius: 20,
        maxHeight: '60%',
        paddingVertical: 20,
    },
    modalTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#2C1B4D',
        textAlign: 'center',
        marginBottom: 15,
    },
    modalItem: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingVertical: 15,
        paddingHorizontal: 25,
        borderBottomWidth: 1,
        borderBottomColor: '#f0f0f0',
    },
    modalItemText: {
        fontSize: 16,
        color: '#444',
    },
    modalItemSelectedText: {
        color: '#2C1B4D',
        fontWeight: 'bold',
    }
});
