import { View, Text, StyleSheet } from 'react-native';

export default function Mapa() {
    return (
        <View style={styles.container}>
            <Text style={styles.text}>Mapa en desarrollo...</Text>
        </View>
    );
}

const styles = StyleSheet.create({
    container: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#FAFAFD' },
    text: { fontSize: 18, color: '#2C1B4D', fontWeight: 'bold' }
});
