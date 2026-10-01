/** 
 * @fileoverview Custom Hook para la gestión de datos de Gastronomía y sus Menús.
 * Centraliza las peticiones HTTP a la API y el procesamiento de la información.
 * 
 * Se encarga de:
 * 1. Obtener de forma paralela el listado de locales gastronómicos, tipos de gastronomía y menús.
 * 2. Sanitizar/normalizar el formato de los tipos y menús recibidos (objetos o cadenas simples).
 * 3. Manejar el estado de carga (`loading`) global para la UI.
 *
 * @module useGastronomiaData
 * @requires react
 */

import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

/**
 * Custom Hook que consulta y gestiona los datos de gastronomía y sus menús.
 * 
 * @returns {Object} Objeto con los datos procesados y el estado de carga.
 * @returns {Array} return.dataGastronomica - Listado completo de locales gastronómicos.
 * @returns {Array} return.tipoGastronomico - Listado parseado y limpio de tipos de gastronomía.
 * @returns {Array} return.menu - Listado parseado y limpio de menús especiales.
 * @returns {boolean} return.loading - Flag indicando si la petición sigue en curso.
 */
export const useGastronomiaData = () => {
    const [dataGastronomica, setDataGastronomica] = useState([]);
    const [tipoGastronomico, setTipoGastronomico] = useState([]);
    const [menu, setMenu] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [gastroRes, tiposRes, menusRes] = await Promise.all([
                    fetch(`${API_URL}/gastronomicos`),
                    fetch(`${API_URL}/tipo-gastronomicos`),
                    fetch(`${API_URL}/menus`)
                ]);

                let gastroData = await gastroRes.json();
                if (Array.isArray(gastroData) && Array.isArray(gastroData[0])) {
                    gastroData = gastroData[0];
                }
                setDataGastronomica(gastroData);

                const tiposData = await tiposRes.json();
                const formatedTipos = tiposData.map((t, i) => typeof t === 'string' ? { idTipo: i, nombre: t } : t);
                setTipoGastronomico(formatedTipos);

                const menusData = await menusRes.json();
                const formatedMenus = menusData.map((m, i) => typeof m === 'string' ? { idMenu: i, tipo: m } : m);
                setMenu(formatedMenus);

            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);

    return { dataGastronomica, tipoGastronomico, menu, loading };
};
