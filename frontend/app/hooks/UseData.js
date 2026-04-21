// hooks/useBalnearios.js
import { useState, useEffect } from 'react';
import { API_URL } from '../../api'; // Ajusta la ruta importada según dónde guardes el archivo
export const useData = (ruta) => {
    // 1. Movemos los estados aquí adentro
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