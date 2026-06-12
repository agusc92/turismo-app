import { View, StyleSheet } from "react-native";
import { Stack, useRouter } from "expo-router";
import HeaderPage from "./HeaderPage";

export default function TransparentHeader() {
    const router = useRouter();

    return (
        <Stack.Screen 
            options={{
                headerShown: true,
                headerTransparent: true,
                title: '',
                headerShadowVisible: false,
                header: () => (
                    <View style={styles.transparentHeaderContainer}>
                        <HeaderPage
                            title=""
                            logo={false}
                            canGoBack={true}
                            onBackPress={() => router.back()}
                        />
                    </View>
                )
            }} 
        />
    );
}

const styles = StyleSheet.create({
    transparentHeaderContainer: {
        backgroundColor: 'transparent',
        flexDirection: 'row',
        alignItems: 'center',      
        width: '100%',
        paddingBottom: 14, 
    }
});