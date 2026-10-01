/**
 * @fileoverview Componente de diseño de página reutilizable para toda la aplicación.
 * Asegura la consistencia visual en todas las pantallas.
 * 
 * @module components/ScreenLayout
 * @requires react-native.View
 * @requires react-native.StyleSheet
 * @exports ScreenLayout
 */

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