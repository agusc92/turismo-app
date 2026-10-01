/**
 * @fileoverview Custom Hook genérico para la gestión de datos desde la API.
 * Centraliza la lógica de petición HTTP, manejo de estado de carga y almacenamiento de datos.
 * 
 * Se encarga de:
 * 1. Obtener el listado de un recurso específico a partir de la ruta proporcionada.
 * 2. Manejar el estado de carga (`loading`) para la UI.
 *
 * @module useData
 * @requires react
 */

import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

/**
 * Custom Hook que consulta y gestiona datos genéricos desde la API.
 * 
 * @param {string} ruta - La ruta del recurso a consultar (ej. 'balnearios', 'actividades').
 * @returns {Object} Objeto con los datos procesados y el estado de carga.
 * @returns {Array} return.data - Listado completo del recurso solicitado.
 * @returns {boolean} return.loading - Flag indicando si la petición sigue en curso.
 */
export const useData = (ruta) => {
    // Estados locales para los datos y el control de carga
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    // 2. Traemos el useEffect exactamente igual
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(`${API_URL}/${ruta}`);
                const data = await response.json();
                setData(data);
            } catch (error) {
                console.error(`Error fetching ${ruta}:`, error);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);
    // 3. Retornamos los estados que el componente va a necesitar para renderizar
    return { data, loading };
};