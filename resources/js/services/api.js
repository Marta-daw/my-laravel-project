import { api } from '@/boot/axios';

export function getAutorsDelLlibre(llibreId) {
    return api.get(`/api/llibres/${llibreId}/autors`);
}

export function getLlibres(params = {}) {
    return api.get('/api/llibres', { params });
}

export function getIdioma() {
    return api.get('/api/idiomas');
}

export function getCategoria() {
    return api.get('/api/categorias');
}

export function getAutor() {
    return api.get('/api/autors');
}

export function getCountLlibres() {
    return api.get('/api/llibres/count');
}

export function getLlibresLlegits() {
    return api.get('/api/llibres/lectures');
}