/**
 * @fileoverview Custom Hook para la gestión de datos de detalle de un recurso.
 * Centraliza la petición HTTP a la API para obtener la información detallada de un recurso específico.
 * 
 * Se encarga de:
 * 1. Obtener los datos de un recurso específico a partir de la ruta y el ID proporcionados.
 * 2. Manejar el estado de carga (`loading`) para la UI.
 *
 * @module useFetchDetalle
 * @requires react
 */

import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

/**
 * Custom Hook que consulta y gestiona los datos de detalle de un recurso específico.
 * 
 * @param {string} endpoint - La ruta del recurso (ej. 'balnearios', 'alojamientos').
 * @param {string|number} id - El identificador único del recurso.
 * @returns {Object} Objeto con los datos procesados y el estado de carga.
 * @returns {Object} return.data - Objeto con los datos detallados del recurso.
 * @returns {boolean} return.loading - Flag indicando si la petición sigue en curso.
 */
export const useFetchDetalle = (endpoint, id) => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDetalle = async () => {
            try {
                const response = await fetch(`${API_URL}/${endpoint}/${id}`);
                const result = await response.json();
                if (response.ok) {
                    setData(result);
                } else {
                    setData(null);
                }
            } catch (error) {
                console.error(`Error fetching ${endpoint}:`, error);
                setData(null);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchDetalle();
        }
    }, [endpoint, id]);

    return { data, loading };
};
