/**
 * @fileoverview Custom Hook para la gestión de datos de Actividades y sus Categorías.
 * Centraliza las peticiones HTTP a la API y el procesamiento de la información.
 * 
 * Se encarga de:
 * 1. Obtener de forma paralela el listado de actividades y los tipos de actividades.
 * 2. Normalizar el formato de los tipos recibidos (objetos o cadenas simples).
 * 3. Manejar el estado de carga (`loading`) global para la UI.
 *
 * @module useActividadesData
 * @requires react
 */

import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

/**
 * Custom Hook que consulta y gestiona los datos de actividades y sus tipos.
 * 
 * @returns {Object} Objeto con los datos procesados y el estado de carga.
 * @returns {Array} return.actividades - Listado completo de actividades.
 * @returns {Array<string>} return.tipoActividades - Listado parseado y limpio de nombres de tipos de actividades.
 * @returns {boolean} return.loading - Flag indicando si la petición sigue en curso.
 */
export const useActividadesData = () => {
    // Estados locales para los datos y el control de carga
    const [actividades, setActividades] = useState([]);
    const [tipoActividades, setTipoActividades] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Peticiones concurrentes mediante Promise.all para optimizar el tiempo de respuesta
                const [actividadesRes, tiposRes] = await Promise.all([
                    fetch(`${API_URL}/actividades`),
                    fetch(`${API_URL}/tipos`)
                ]);

                const actividadesData = await actividadesRes.json();
                const tiposData = await tiposRes.json();

                setActividades(actividadesData);

                // Mapeo y sanitización de los tipos devueltos por la API 
                // Tolera variantes como { nombre: 'X' }, { tipo: 'X' } o cadenas simples
                const parsedTipos = tiposData.map(t => typeof t === 'object' ? (t.nombre || t.tipo) : t).filter(Boolean);

                setTipoActividades(parsedTipos);

            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);

    return { actividades, tipoActividades, loading };
};
