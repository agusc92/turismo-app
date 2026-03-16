import { Link, Stack } from "expo-router";
import { View, Text, StyleSheet, Pressable } from "react-native";

import { StatusBar } from "expo-status-bar";
import { SafeAreaView } from "react-native-safe-area-context";
export default function Layout() {
    return (
        <SafeAreaView style={styles.notificationBar} edges={['top']}>
            <View style={styles.container} >
                <StatusBar style="light" />
                <Stack

                    screenOptions={{
                        headerStyle: { backgroundColor: 'white' },
                        headerTintColor: 'black',
                    }}
                />
            </View>
        </SafeAreaView>

    );


}
const styles = StyleSheet.create({
    container: {
        flex: 1,

    },
    notificationBar: {
        backgroundColor: 'black',
        flex: 1,
    }
})