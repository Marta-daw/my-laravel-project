import { api } from '@/boot/axios';

export function getAutorsDelLlibre(llibreId) {
    return api.get(`/api/llibres/${llibreId}/autors`);
}