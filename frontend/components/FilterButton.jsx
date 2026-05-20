import { StyleSheet, Text, TouchableOpacity } from "react-native";
import { Ionicons } from "@expo/vector-icons";

export default function FilterButton({ label, onPress }) {
    return (
        <TouchableOpacity
            style={styles.filterButton}
            onPress={onPress}
        >
            <Text style={styles.filterText}>
                {label}
            </Text>
            <Ionicons name="chevron-down" size={16} color="#333" />
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    filterButton: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#e9e9f3ff', // Light purple/blue tint from mockup
        paddingHorizontal: 15,
        paddingVertical: 8,
        borderRadius: 8,
        marginRight: 10,
    },
    filterText: {
        fontSize: 14,
        color: '#2C1B4D',
        marginRight: 5,
        fontFamily: 'Gotham-Medium',
    }

});
