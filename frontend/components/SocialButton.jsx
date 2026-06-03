import React from 'react';
import { TouchableOpacity, Image, StyleSheet } from 'react-native';

const socialIcons = {
    instagram: require('../assets/socialIcon/instagram.png'),
    facebook: require('../assets/socialIcon/facebook.png'),
    youtube: require('../assets/socialIcon/youtube.png'),
    'tik-tok': require('../assets/socialIcon/tik-tok.png'),
};

export default function SocialButton({ iconName, onPress }) {
    const iconSource = socialIcons[iconName];

    return (
        <TouchableOpacity style={styles.socialIconButton} onPress={onPress}>
            {iconSource ? (
                <Image source={iconSource} style={styles.socialIcon} resizeMode="contain" />
            ) : null}
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    socialIconButton: {
        flex: 1,
        aspectRatio: 1,
        backgroundColor: '#FFF',
        borderRadius: 16,
        justifyContent: 'center',
        alignItems: 'center',
        elevation: 3,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.08,
        shadowRadius: 4,
        padding: 12,
    },
    socialIcon: {
        width: '75%',
        height: '75%',
    },
});
