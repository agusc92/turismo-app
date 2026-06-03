import React from 'react';
import { TouchableOpacity, View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../constants/Styles';

export default function ContactButton({ iconName, iconColor, iconBgColor, label, value, onPress }) {
    return (
        <TouchableOpacity style={styles.contactCard} onPress={onPress}>
            <View style={[styles.iconWrapper, { backgroundColor: iconBgColor }]}>
                <Ionicons name={iconName} size={24} color={iconColor} />
            </View>
            <View style={styles.cardTextContainer}>
                <Text style={styles.cardLabel}>{label}</Text>
                <Text style={styles.cardValue}>{value}</Text>
            </View>
            <Ionicons name="chevron-forward" size={20} color="#8A819C" />
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    contactCard: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#FFF',
        borderRadius: 16,
        padding: 14,
        marginBottom: 10,
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.05,
        shadowRadius: 3,
    },
    iconWrapper: {
        width: 46,
        height: 46,
        borderRadius: 12,
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 14,
    },
    cardTextContainer: {
        flex: 1,
    },
    cardLabel: {
        fontSize: 12,
        fontFamily: 'Gotham-Book',
        color: Colors.textColorLight,
        marginBottom: 2,
    },
    cardValue: {
        fontSize: 15,
        fontFamily: 'Gotham-Medium',
        color: Colors.textColor,
    },
});
