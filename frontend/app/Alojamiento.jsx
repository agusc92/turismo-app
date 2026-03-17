import { View, Text, StyleSheet } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
import { Colors } from "../constants/Styles";
export default function Hoteles() {
    return (
        <ScreenLayout>
            <View>
                <Text style={styles.primaryText}>Hoteles</Text>
            </View>
        </ScreenLayout>
    );
}
const styles = StyleSheet.create({
    primaryText: {
        color: Colors.textColor,
    }
});