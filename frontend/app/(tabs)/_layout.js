import { Tabs } from 'expo-router';
import { View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

// Componente CustomTabItem eliminado

export default function TabLayout() {
    return (
        <Tabs
            screenOptions={{
                headerShown: false,
                tabBarShowLabel: true,
                tabBarStyle: styles.tabBar,
                tabBarLabel: ({ focused, children }) => (
                    <View style={styles.labelWrapper}>
                        <Text style={[styles.tabLabel, {
                            color: focused ? '#2C1B4D' : '#554E66',
                            fontWeight: focused ? '800' : '500'
                        }]}>
                            {children}
                        </Text>
                        {/* La pequeña línea indicadora debajo del texto */}
                        <View style={[styles.indicator, { backgroundColor: focused ? '#2C1B4D' : 'transparent' }]} />
                    </View>
                ),
            }}
        >
            <Tabs.Screen
                name="index"
                options={{
                    title: 'Inicio',
                    tabBarIcon: ({ focused }) => (
                        <Ionicons name={focused ? 'home' : 'home-outline'} size={24} color={focused ? '#2C1B4D' : '#554E66'} />
                    ),
                }}
            />
            <Tabs.Screen
                name="mapa"
                options={{
                    title: 'Mapa',
                    tabBarIcon: ({ focused }) => (
                        <Ionicons name={focused ? 'map' : 'map-outline'} size={24} color={focused ? '#2C1B4D' : '#554E66'} />
                    ),
                }}
            />
            <Tabs.Screen
                name="eventos"
                options={{
                    title: 'Eventos',
                    tabBarIcon: ({ focused }) => (
                        <Ionicons name={focused ? 'calendar' : 'calendar-outline'} size={24} color={focused ? '#2C1B4D' : '#554E66'} />
                    ),
                }}
            />
        </Tabs>
    );
}

const styles = StyleSheet.create({
    tabBar: {
        backgroundColor: '#FAFAFD',
        borderTopWidth: 0,
        elevation: 5,
        shadowOpacity: 0.1,
        shadowRadius: 10,
        shadowOffset: { width: 0, height: -2 },
        height: 65,
        marginBottom: 0,
    },
    labelWrapper: {
        alignItems: 'center',
        justifyContent: 'center',
        paddingBottom: 5,
    },
    tabLabel: {
        fontSize: 12,
        marginTop: 2,
    },
    indicator: {
        width: 12,
        height: 3,
        borderRadius: 2,
        marginTop: 3,
    }
});
