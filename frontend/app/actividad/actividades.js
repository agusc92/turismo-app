import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, Image, TouchableOpacity, Modal, ActivityIndicator } from 'react-native';
import { Stack, router } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { API_URL } from '../../api';

export default function ActividadesList() {
    const [actividades, setActividades] = useState([]);
    const [tipoActividades, setTipoActividades] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedTipo, setSelectedTipo] = useState(null);
    const [showTipoModal, setShowTipoModal] = useState(false);

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Hacemos las dos peticiones en paralelo: a actividades y a tipos
                const [actividadesRes, tiposRes] = await Promise.all([
                    fetch(`${API_URL}/actividades`),
                    fetch(`${API_URL}/tipos`)
                ]);

                const actividadesData = await actividadesRes.json();
                const tiposData = await tiposRes.json();

                setActividades(actividadesData);

                // Mapeamos los tipos, tanto si la API devuelve objetos {nombre: 'Aventura'} como una lista de strings
                const parsedTipos = tiposData.map(t => typeof t === 'object' ? (t.nombre || t.tipo) : t).filter(Boolean);
                setTipoActividades(parsedTipos);

            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);

    // Filter logic
    const filteredData = actividades.filter(item => {
        let matchesTipo = true;

        if (selectedTipo) {
            console.log(item.tipo.tipo);
            console.log(selectedTipo);
            console.log(item);
            matchesTipo = item.tipo.tipo && item.tipo.tipo.includes(selectedTipo);
        }

        return matchesTipo;
    });

    const renderCustomPicker = (title, items, selectedItem, onSelect, onClose) => {
        const pickerItems = items.map(t => ({ tipo: t }));

        return (
            <Modal visible={true} transparent={true} animationType="fade">
                <TouchableOpacity style={styles.modalOverlay} activeOpacity={1} onPress={onClose}>
                    <View style={styles.modalContent}>
                        <Text style={styles.modalTitle}>{title}</Text>
                        <FlatList
                            data={[{ tipo: 'Todos', isClear: true }, ...pickerItems]}
                            keyExtractor={(i, index) => index.toString()}
                            renderItem={({ item }) => {
                                const isSelected = item.isClear
                                    ? !selectedItem
                                    : selectedItem === item.tipo;

                                return (
                                    <TouchableOpacity
                                        style={styles.modalItem}
                                        onPress={() => {
                                            if (item.isClear) {
                                                onSelect(null);
                                            } else {
                                                onSelect(item.tipo);
                                            }
                                            onClose();
                                        }}
                                    >
                                        <Text style={[styles.modalItemText, isSelected && styles.modalItemSelectedText]}>
                                            {item.tipo ? (item.tipo.charAt(0).toUpperCase() + item.tipo.slice(1)) : ''}
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
    };

    return (
        <View style={styles.container}>
            <Stack.Screen
                options={{
                    headerShown: true,
                    headerTitle: 'Actividades',
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
                        {selectedTipo ? (selectedTipo.charAt(0).toUpperCase() + selectedTipo.slice(1)) : 'Tipo'}
                    </Text>
                    <Ionicons name="chevron-down" size={16} color="#333" />
                </TouchableOpacity>
            </View>

            {showTipoModal && renderCustomPicker(
                "Seleccionar Tipo",
                tipoActividades,
                selectedTipo,
                setSelectedTipo,
                () => setShowTipoModal(false)
            )}

            <FlatList
                data={filteredData}
                keyExtractor={(item) => (item.id || item.idActividad || Math.random()).toString()}
                contentContainerStyle={styles.listContainer}
                renderItem={({ item }) => {
                    let subtitle = item.direccion ? `Calle ${item.direccion}` : 'Actividad';

                    const fallbackImageUrl = 'https://dummyimage.com/600x400/b3d4fc/2a61a3.png&text=' + encodeURIComponent(item.nombre);
                    let imageUrl = fallbackImageUrl;
                    if (item.imagen && item.imagen.startsWith('http')) {
                        imageUrl = item.imagen;
                    }

                    return (
                        <TouchableOpacity
                            style={styles.card}
                            onPress={() => router.push(`/actividad/${item.id || item.idActividad}`)}
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
        backgroundColor: '#F0EFFF',
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
