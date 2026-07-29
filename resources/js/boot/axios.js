import axios from 'axios';

const api = axios.create({
    baseURL: 'https://my-laravel-project.ddev.site'
});

export { api };