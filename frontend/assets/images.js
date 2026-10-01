/**
 * @fileoverview Módulo de gestión y resolución de imágenes locales y remotas de la aplicación.
 * Proporciona un componente gráfico para el logo principal y funciones auxiliares para la 
 * asignación dinámica de imágenes de reserva (fallback) basadas en categorías y coincidencia 
 * de palabras clave en los atributos del recurso.
 * 
 * @module utils/imageHelpers
 * @requires react-native.Image
 */

import { Image } from 'react-native';

/**
 * Componente visual que renderiza el logotipo institucional.
 *
 * @function Logo
 * @returns {JSX.Element} Elemento `Image` configurado con el logotipo institucional.
 */
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

/**
 * Determina la imagen local correspondiente para un establecimiento gastronómico
 * evaluando su tipo o subcategoría y su nombre comercial.
 */
function getGastronomicoLocalImage(item) {
    // 1. Validación inicial: si no existe el ítem se retorna la imagen base de restaurante
    if (!item) return localImages.restaurante;

    let types = [];
    if (item.tipo) {
        if (Array.isArray(item.tipo)) {
            types = item.tipo.map(t => typeof t === 'string' ? t.toLowerCase() : (t.nombre ? t.nombre.toLowerCase() : ''));
        } else if (typeof item.tipo === 'string') {
            types = [item.tipo.toLowerCase()];
        }
    }

    // 3. Evaluación de coincidencias en la lista de tipos
    const isCafe = types.some(t => t.includes('cafe') || t.includes('cafeter'));
    const isBar = types.some(t => t.includes('bar') || t.includes('cervecer') || t.includes('birrer') || t.includes('pub'));

    if (isCafe) return localImages.cafeteria;
    if (isBar) return localImages.bar;

    // 4. Evaluación de palabras clave directamente en el nombre comercial como criterio secundario
    const nombre = item.nombre ? item.nombre.toLowerCase() : '';
    if (nombre.includes('cafe') || nombre.includes('cafeter')) return localImages.cafeteria;
    if (nombre.includes('bar') || nombre.includes('cervecer') || nombre.includes('birra') || nombre.includes('pub')) return localImages.bar;

    // 5. Retorno del fallback genérico de la categoría gastronómica
    return localImages.restaurante;
}

/**
 * Función principal que determina qué imagen utilizar para un recurso dado, soportando imágenes locales y remotas.
 *
 * @param {string} type - Tipo de recurso ('actividad', 'alojamiento', 'gastronomico', etc.).
 * @param {Object} item - Objeto del recurso que puede contener propiedades 'imagen', 'nombre' o 'descripcion'.
 * @returns {Object} Objeto con el atributo 'uri' para el componente `Image` de React Native.
 */
export function getResourceImage(type, item) {

    if (item && item.imagen && typeof item.imagen === 'string' && item.imagen.startsWith('http')) {
        return { uri: item.imagen };
    }

    // Selección del recurso local según la categoría principal
    switch (type) {
        // Análisis de palabras clave en nombre y descripción para subcategorización específica
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

