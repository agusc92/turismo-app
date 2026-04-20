import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, Image, TouchableOpacity, Modal, Platform, ActivityIndicator } from 'react-native';
import { Tabs, router } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { API_URL } from '../../api';
import ItemCard from '../../components/ItemCard';

// Si logramos instalar datetimepicker, lo usamos, si no, fallará. 
// Como alternativa, podriamos usar un mock o un select simple.
import DateTimePicker from '@react-native-community/datetimepicker';

export default function EventosList() {
    const [eventos, setEventos] = useState([]);
    const [date, setDate] = useState(new Date());
    const [showPicker, setShowPicker] = useState(false);
    const [filterActive, setFilterActive] = useState(false);
    const [loading, setLoading] = useState(true);
    useEffect(() => {
        const fetchEventos = async () => {
            try {
                const response = await fetch(`${API_URL}/eventos`);
                const data = await response.json();
                setEventos(data);
            } catch (error) {
                console.error("Error fetching eventos:", error);
            }
            finally {
                setLoading(false);
            }
        };
        fetchEventos();
    }, []);

    // Formatear fecha para validación y UI
    const formatDateForUI = (dateObj) => {
        const opciones = { weekday: 'long', day: 'numeric', month: 'long' };
        let str = dateObj.toLocaleDateString('es-ES', opciones);
        // Capitalizar la primera letra
        return str.charAt(0).toUpperCase() + str.slice(1);
    };

    // Filtro simulado, usando 'fecha' del mokup si logramos parsearla
    const filteredEventos = eventos.filter(evento => {
        if (!filterActive) return true;

        // La fecha en mokup es "2026-04-20 12:30:00"
        const eventDate = new Date(evento.fecha.replace(" ", "T"));

        // Solo mostramos eventos a partir de la fecha seleccionada (ignorando horas)
        eventDate.setHours(0, 0, 0, 0);
        const selectedDate = new Date(date);
        selectedDate.setHours(0, 0, 0, 0);

        return eventDate.getTime() >= selectedDate.getTime();
    });

    const onDateChange = (event, selectedDate) => {
        const currentDate = selectedDate || date;
        setShowPicker(Platform.OS === 'ios');
        setDate(currentDate);
        setFilterActive(true);
    };

    const clearFilter = () => {
        setFilterActive(false);
        setDate(new Date());
    };
    if (loading) {
        return (
            <View style={[styles.container, { justifyContent: 'center', alignItems: 'center' }]}>
                <Tabs.Screen options={{ title: 'Eventos', headerShown: true }} />
                <ActivityIndicator size="large" color="#2C1B4D" />
            </View>
        )
    }
    return (
        <View style={styles.container}>
            <Tabs.Screen
                options={{
                    headerShown: true,
                    headerTitle: 'Eventos',
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
                    headerRight: () => (
                        <TouchableOpacity onPress={() => setShowPicker(true)} style={styles.headerIcon}>
                            <Ionicons name="filter-outline" size={24} color={filterActive ? "#007AFF" : "#2C1B4D"} />
                        </TouchableOpacity>
                    )
                }}
            />

            {filterActive && (
                <View style={styles.activeFilterContainer}>
                    <Text style={styles.activeFilterText}>
                        Mostrando desde: {formatDateForUI(date)}
                    </Text>
                    <TouchableOpacity onPress={clearFilter}>
                        <Ionicons name="close-circle" size={20} color="#666" />
                    </TouchableOpacity>
                </View>
            )}

            <FlatList
                data={filteredEventos}
                keyExtractor={(item) => (item.id || item.idEvento || Math.random()).toString()}
                contentContainerStyle={styles.listContainer}
                renderItem={({ item }) => {
                    // Si el mokup tiene fecha en formato '2026-04-20', la formateamos
                    let displayDate = "";
                    try {
                        const d = new Date(item.fecha.replace(" ", "T"));
                        displayDate = formatDateForUI(d);
                    } catch (e) {
                        displayDate = "Fecha no disponible";
                    }

                    return (
                        <ItemCard item={item} subtitle={displayDate} imageUrl={item.imagen} link={`/evento/${item.id || item.idEvento}`} />
                    );

                }}
                ListEmptyComponent={
                    <View style={styles.emptyContainer}>
                        <Text style={styles.emptyText}>No hay eventos a partir de esta fecha.</Text>
                    </View>
                }
            />

            {showPicker && (
                <DateTimePicker
                    testID="dateTimePicker"
                    value={date}
                    mode="date"
                    is24Hour={true}
                    display="default"
                    onChange={onDateChange}
                />
            )}
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
    },
    activeFilterContainer: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        paddingHorizontal: 20,
        paddingVertical: 10,
        backgroundColor: '#E5E5EA',
        borderBottomWidth: 1,
        borderBottomColor: '#ccc',
    },
    activeFilterText: {
        fontSize: 14,
        color: '#333',
        fontWeight: '500',
    },
    listContainer: {
        padding: 20,
        paddingTop: 10,
    },
    emptyContainer: {
        padding: 20,
        alignItems: 'center',
        marginTop: 50,
    },
    emptyText: {
        fontSize: 16,
        color: '#666',
    }
});
