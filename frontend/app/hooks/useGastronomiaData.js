import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

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
