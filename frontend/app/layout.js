import { SafeAreaView } from "react-native-safe-area-context";
import { View } from "react-native";
export default function Layout() {
    return (
        <SafeAreaView style={styles.notificationBar} edges={['top']}>
            <Text>Layout</Text>
            <View style={styles.container} >
                <StatusBar style="light" />
            </View>
        </SafeAreaView>
    );
}   