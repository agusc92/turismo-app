import { StyleSheet, View } from "react-native";
export function ScreenLayout({ children }) {
    return (
        <View style={pagina.container}>{children}</View>
    )
}

const pagina = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'black',
    }
});