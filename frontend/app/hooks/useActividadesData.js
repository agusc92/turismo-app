import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

export const useActividadesData = () => {
    const [actividades, setActividades] = useState([]);
    const [tipoActividades, setTipoActividades] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Hacemos las dos peticiones en paralelo: a actividades y a tipos
                const [actividadesRes, tiposRes] = await Promise.all([
                    fetch(`${API_URL}/actividades`),
                    fetch(`${API_URL}/tipos`)
                ]);

                const actividadesData = await actividadesRes.json();
                const tiposData = await tiposRes.json();

                setActividades(actividadesData);

                // Mapeamos los tipos, tanto si la API devuelve objetos {nombre: 'Aventura'} como una lista de strings
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
