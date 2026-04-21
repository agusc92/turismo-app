import { Image } from 'react-native';
export function Logo() {
    const logo = require('./logo-entur.png');
    return <Image source={logo} style={{ width: 50, height: 50 }} />;
}
