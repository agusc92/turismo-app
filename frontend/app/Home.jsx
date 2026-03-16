import { View, Text, StyleSheet } from "react-native";
import { ScreenLayout } from "../components/ScreenLayout";
export default function Home() {
    return (
        <ScreenLayout>
            <View>
                <Text style={{ color: 'white' }}>Home</Text>
            </View>
        </ScreenLayout>
    );
}
const styles = StyleSheet.create({

});