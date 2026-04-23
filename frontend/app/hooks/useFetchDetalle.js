import { useState, useEffect } from 'react';
import { API_URL } from '../../api';

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
