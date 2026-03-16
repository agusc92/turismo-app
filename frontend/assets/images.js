import { Image } from 'react-native';
import Entypo from '@expo/vector-icons/Entypo';
export function Logo() {
    const logo = require('./logo-entur.png');
    return <Image source={logo} style={{ width: 50, height: 50 }} />;
}