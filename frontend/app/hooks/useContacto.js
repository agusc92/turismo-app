import { useEffect, useState } from 'react';
import { API_URL } from '../../api';

const defaultContactInfo = {
    phone: '+54 2262 425665',
    whatsapp: '+54 2262 431155',
    email: 'turismo@necochea.tur.ar',
    instagram: 'necocheatur',
    facebook: 'necocheatur',
    youtube: 'necocheatur',
    tiktok: 'necocheatur',
    website: 'https://necochea.tur.ar',
    address: 'Av. 2 y Calle 87, Necochea, Buenos Aires',
};

const hasValue = (value) => value !== null && value !== undefined && String(value).trim() !== '';

export const useContacto = () => {
    const [contactInfo, setContactInfo] = useState(defaultContactInfo);

    useEffect(() => {
        const fetchContacto = async () => {
            try {
                const response = await fetch(`${API_URL}/contactos`);

                if (!response.ok) {
                    throw new Error(`Error al obtener contactos: ${response.status}`);
                }

                const result = await response.json();
                const data = result.data || {};

                setContactInfo((current) => ({
                    ...current,
                    phone: hasValue(data.telefono) ? data.telefono : current.phone,
                    whatsapp: hasValue(data.whatsapp) ? data.whatsapp : current.whatsapp,
                    email: hasValue(data.email) ? data.email : current.email,
                    instagram: hasValue(data.instagram) ? data.instagram : current.instagram,
                    facebook: hasValue(data.facebook) ? data.facebook : current.facebook,
                    youtube: hasValue(data.youtube) ? data.youtube : current.youtube,
                    tiktok: hasValue(data.tiktok) ? data.tiktok : current.tiktok,
                    website: hasValue(data.web) ? data.web : current.website,
                    address: hasValue(data.direccion) ? data.direccion : current.address,
                }));
            } catch (error) {
                console.error('Error fetching contactos:', error);
            }
        };

        fetchContacto();
    }, []);

    return contactInfo;
};
