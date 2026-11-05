import { api } from '@/boot/axios';

export function getAutorsDelLlibre(llibreId) {
    return api.get(`/api/llibres/${llibreId}/autors`);
}

export function getLlibres(params = {}) {
    return api.get('/api/llibres', { params });
}

export function getLlibreById(id) {
    return api.get(`/api/llibres/${id}`)
}

export function createLlibre(formData) {
    return api.post('/api/llibres', formData)
}

export function updateLlibre(id, formData) {
    formData.append('_method', 'PUT')
    return api.post(`/api/llibres/${id}`, formData)
}

export function deleteLlibre(id) {
    return api.delete(`/api/llibres/${id}`)
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

export function getUltimosLibros() {
    return api.get('/api/llibres/ultimos');
}

export function crearAutor(payload) {
    return api.post('/api/autors', payload)
}