import { Image } from 'react-native';
export function Logo() {
    const logo = require('./logo-entur.png');
    return <Image source={logo} style={{ width: 50, height: 50 }} />;
}

const localImages = {
    actividad: require('./imagenes/actividad.jpeg'),
    alojamiento: require('./imagenes/alojamiento.jpeg'),
    ballena: require('./imagenes/ballena.jpeg'),
    balneario: require('./imagenes/balneario.jpeg'),
    bar: require('./imagenes/bar.jpeg'),
    cafeteria: require('./imagenes/cafeteria.jpeg'),
    complejo: require('./imagenes/complejo.jpeg'),
    evento: require('./imagenes/evento.jpeg'),
    restaurante: require('./imagenes/restaurante.jpeg'),
};

function getGastronomicoLocalImage(item) {
    if (!item) return localImages.restaurante;

    let types = [];
    if (item.tipo) {
        if (Array.isArray(item.tipo)) {
            types = item.tipo.map(t => typeof t === 'string' ? t.toLowerCase() : (t.nombre ? t.nombre.toLowerCase() : ''));
        } else if (typeof item.tipo === 'string') {
            types = [item.tipo.toLowerCase()];
        }
    }

    const isCafe = types.some(t => t.includes('cafe') || t.includes('cafeter'));
    const isBar = types.some(t => t.includes('bar') || t.includes('cervecer') || t.includes('birrer') || t.includes('pub'));

    if (isCafe) return localImages.cafeteria;
    if (isBar) return localImages.bar;

    const nombre = item.nombre ? item.nombre.toLowerCase() : '';
    if (nombre.includes('cafe') || nombre.includes('cafeter')) return localImages.cafeteria;
    if (nombre.includes('bar') || nombre.includes('cervecer') || nombre.includes('birra') || nombre.includes('pub')) return localImages.bar;

    return localImages.restaurante;
}

export function getResourceImage(type, item) {
    if (item && item.imagen && typeof item.imagen === 'string' && item.imagen.startsWith('http')) {
        return { uri: item.imagen };
    }

    switch (type) {
        case 'actividad': {
            const nombre = item?.nombre ? item.nombre.toLowerCase() : '';
            const descripcion = item?.descripcion ? item.descripcion.toLowerCase() : '';
            if (nombre.includes('ballena') || descripcion.includes('ballena') || nombre.includes('avistaje') || descripcion.includes('avistaje')) {
                return localImages.ballena;
            }
            return localImages.actividad;
        }
        case 'alojamiento':
            return localImages.alojamiento;
        case 'balneario':
            return localImages.balneario;
        case 'complejo':
            return localImages.complejo;
        case 'evento':
            return localImages.evento;
        case 'gastronomico':
            return getGastronomicoLocalImage(item);
        default:
            return localImages.actividad;
    }
}

