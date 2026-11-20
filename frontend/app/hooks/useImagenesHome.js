import { useEffect, useState } from 'react';
import { API_URL } from '../../api';

export const useImagenesHome = () => {
    const [imagenes, setImagenes] = useState(null);

    useEffect(() => {
        const fetchImagenes = async () => {
            try {
                const response = await fetch(`${API_URL}/imagenes`);

                if (!response.ok) {
                    throw new Error(`Error al obtener imágenes: ${response.status}`);
                }

                const result = await response.json();
                setImagenes(result.data);
            } catch (error) {
                console.error('Error fetching imágenes de la Home:', error);
            }
        };

        fetchImagenes();
    }, []);

    return { imagenes };
};
