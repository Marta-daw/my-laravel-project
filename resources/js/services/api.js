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

export function getResumLectures() {
    return api.get('/api/llibres/lectures');
}