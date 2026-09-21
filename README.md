<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a></p>

<p align="center">
  <strong>my-laravel-project</strong><br>
    Backend en Laravel + Frontend en Vue 3 / Quasar, entorno de desarrollo local gestionado con DDEV.
</p>

---

## Stack tecnológico

- **Backend:** [Laravel](https://laravel.com) 13
- **Frontend:** [Vue 3](https://vuejs.org) + [Quasar](https://quasar.dev) (vía Vite)
- **Estado del frontend:** Vue Router + Pinia + Axios
- **Estilos:** Quasar + Tailwind CSS
- **PHP del entorno DDEV:** 8.4
- **Node.js del entorno DDEV:** 24
- **Base de datos:** MySQL 8.0
- **Entorno de desarrollo local:** [DDEV](https://ddev.com) (basado en Docker)

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [DDEV](https://ddev.com/get-started/) (v1.25+)
- [Node.js](https://nodejs.org/) solo es necesario si quieres ejecutar `npm` fuera de DDEV. El contenedor web utiliza Node.js 24.

## Puesta en marcha

Clona el repositorio y entra en la carpeta del proyecto:

```bash
git clone <repo-url>
cd my-laravel-project
```

Arranca el entorno DDEV:

```bash
ddev start
```

Instala las dependencias de PHP:

```bash
ddev composer install
```

Copia el archivo de entorno (si no existe ya) y genera la clave de la aplicación:

```bash
cp .env.example .env
ddev artisan key:generate
```

Ejecuta las migraciones de la base de datos:

```bash
ddev artisan migrate
```

Para crear la base de datos y cargar los datos iniciales:

```bash
ddev artisan migrate:fresh --seed
```

Instala las dependencias del frontend:

```bash
ddev exec npm install
```

Abre el proyecto en el navegador:

```bash
ddev launch
```

La aplicación estará disponible en:

```
https://my-laravel-project.ddev.site
```

## Desarrollo del frontend (Vue / Quasar)

Vite está integrado con Laravel y sirve los assets de Vue/Quasar con hot-module-reload (HMR).

Arranca el servidor de desarrollo de Vite:

```bash
ddev exec npm run dev
```

- La aplicación se accede siempre a través de la URL de Laravel: **`https://my-laravel-project.ddev.site`** — nunca directamente a través de `localhost:5173`. Vite simplemente inyecta los assets compilados/HMR en las vistas Blade servidas por Laravel.
- Los componentes de Vue/Quasar viven en `resources/js/`.
- `APP_URL` en `.env` debe coincidir siempre con la URL del proyecto en DDEV:

```dotenv
APP_URL=https://my-laravel-project.ddev.site
```

Compilar los assets para producción:

```bash
ddev exec npm run build
```

### Configuración actual de Vite

Las dependencias de Vue, Quasar, Pinia, Vue Router, Axios, Tailwind CSS y Sass ya están declaradas en `package.json`. Después de clonar el proyecto basta con ejecutar `ddev exec npm install`.

El archivo `vite.config.js` integra Laravel, Vue, Quasar, Tailwind CSS y las fuentes de Bunny:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        vue({
            template: { transformAssetUrls }
        }),
        quasar({
            sassVariables: path.resolve(__dirname, 'resources/css/quasar-variables.scss')
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'my-laravel-project.ddev.site',
            protocol: 'wss',
        },
    },
});
```

> En un archivo ESM (`vite.config.js`), `__dirname` no existe por defecto, por eso se reconstruye manualmente con `fileURLToPath`. La ruta de `sassVariables` se resuelve mediante `path.resolve(...)`.

El servidor de Vite está configurado para escuchar en `0.0.0.0:5173`. DDEV expone ese puerto mediante la URL de Vite, mientras que la aplicación Laravel sigue sirviéndose desde la URL principal del proyecto.

### Estructura de carpetas del frontend

```
resources/js/
├── app.js                # Punto de entrada: monta Vue + Quasar + Pinia + Router
├── App.vue               # Componente raíz (<router-view />)
├── router/
│   └── Index.js           # Definición de rutas (vue-router)
├── boot/
│   └── axios.js           # Cliente Axios
├── services/
│   └── api.js             # Llamadas al backend
├── stores/                # Stores de Pinia
├── layouts/
│   ├── MainLayout.vue      # Layout principal
│   └── ProjectLayout.vue   # Layout de la biblioteca
├── pages/
│   ├── Home.vue
│   ├── Home2.vue
│   ├── AboutMe.vue
│   └── DasboardBiblio.vue
├── components/            # Componentes reutilizables
├── css/                   # Estilos y variables de Quasar
├── assets/                # Recursos del frontend
└── i18n/                  # Configuración de idiomas
```

Las rutas actuales del frontend son `/`, `/about`, `/Home2.vue` y `/biblioteca`. La configuración actual se encuentra en `resources/js/router/Index.js`; en Linux, el uso de mayúsculas y minúsculas en el nombre `Index.js` es significativo.

La app de Vue se monta desde `resources/views/app.blade.php` (contiene `<div id="app"></div>` + `@vite(...)`), que se devuelve desde la ruta raíz en `routes/web.php`:

```php
Route::get('/', function () {
    return view('app');
});
```

## Comandos DDEV habituales

| Comando | Descripción |
|---|---|
| `ddev start` | Arranca los contenedores del proyecto |
| `ddev stop` | Detiene los contenedores del proyecto (conserva los datos) |
| `ddev restart` | Reinicia el proyecto (necesario tras cambios en `.ddev/config.yaml`) |
| `ddev poweroff` | Detiene todos los proyectos DDEV globalmente |
| `ddev launch` | Abre el proyecto en el navegador por defecto |
| `ddev describe` | Muestra información, URLs y estado del proyecto |
| `ddev ssh` | Accede por SSH al contenedor web |
| `ddev logs` | Muestra los logs del contenedor web |
| `ddev delete` | Elimina los contenedores y la base de datos del proyecto (el código se conserva) |

## Laravel / Artisan

| Comando | Descripción |
|---|---|
| `ddev artisan migrate` | Ejecuta las migraciones de la base de datos |
| `ddev artisan migrate:fresh --seed` | Resetea la base de datos y la vuelve a poblar |
| `ddev artisan make:model NombreModelo -m` | Crea un modelo junto con su migración |
| `ddev artisan route:list` | Lista todas las rutas registradas |
| `ddev artisan tinker` | Abre una shell interactiva de Laravel |
| `ddev artisan test` | Ejecuta la suite de pruebas |
| `ddev artisan config:clear` | Limpia la configuración cacheada |
| `ddev exec vendor/bin/pint --test` | Comprueba el formato PHP con Laravel Pint |

## Composer / NPM

| Comando | Descripción |
|---|---|
| `ddev composer install` | Instala las dependencias de PHP |
| `ddev composer require <paquete>` | Añade una nueva dependencia de PHP |
| `ddev exec npm install` | Instala las dependencias de JS |
| `ddev exec npm run dev` | Arranca el servidor de Vite con HMR |
| `ddev exec npm run build` | Compila los assets del frontend para producción |

Actualmente `package.json` solo define los scripts `dev` y `build`. Los comandos `lint` y `format` todavía no están configurados.

## API disponible

Las rutas API se encuentran en `routes/api.php` y Laravel les añade automáticamente el prefijo `/api`:

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/autors` | Lista de autores |
| `GET` | `/api/autors/{id}` | Detalle de un autor |
| `POST` | `/api/autors` | Crea un autor |
| `PUT` | `/api/autors/{id}` | Actualiza un autor |
| `DELETE` | `/api/autors/{id}` | Elimina un autor |
| `GET` | `/api/categorias` | Lista de categorías |
| `GET` | `/api/idiomas` | Lista de idiomas |
| `GET` | `/api/llibres` | Lista de libros con relaciones |
| `GET` | `/api/llibres/{id}` | Detalle de un libro |
| `GET` | `/api/llibres/{id}/autors` | Autores de un libro |
| `GET` | `/api/llibres/count` | Número total de libros |
| `GET` | `/api/llibres/lectures` | Estadísticas del estado de lectura |
| `POST` | `/api/llibres` | Crea un libro |
| `PUT` | `/api/llibres/{id}` | Actualiza un libro |
| `DELETE` | `/api/llibres/{id}` | Elimina un libro |

> Las operaciones CRUD son actualmente públicas. Sanctum está instalado, pero todavía no se aplica como middleware de autenticación a estas rutas.

## Base de datos

- **Conexión:** MySQL 8.0
- **Host:** `db` (red interna de DDEV — no modificar)
- **Base de datos / Usuario / Contraseña:** `db` / `db` / `db` (valores por defecto de DDEV)

```dotenv
DB_CONNECTION="mysql"
DB_HOST="db"
DB_PORT="3306"
DB_DATABASE="db"
DB_USERNAME="db"
DB_PASSWORD="db"
```

Exportar / importar la base de datos:

```bash
ddev export-db --file=backup.sql.gz
ddev import-db --file=backup.sql.gz
```

## URLs útiles

| Servicio | URL |
|---|---|
| Aplicación | https://my-laravel-project.ddev.site |
| Vite/HMR | https://my-laravel-project.ddev.site:5173 |
| Mailpit (correos de desarrollo) | `ddev launch -m` |
| Base de datos (phpMyAdmin, si está habilitado) | `ddev launch -p` |
| Panel de Traefik | http://127.0.0.1:10999 |

## Solución de problemas

- **`ddev start` falla con errores del router:** prueba `ddev poweroff` seguido de `ddev start`. Si persiste, revisa `docker logs ddev-router`.
- **El HMR de Vite no conecta desde el navegador:** asegúrate de que `web_extra_exposed_ports` está configurado en `.ddev/config.yaml` y que `vite.config.js` apunta `hmr.host` a `my-laravel-project.ddev.site`.
- **Los cambios de configuración no se aplican:** ejecuta `ddev restart` después de editar `.ddev/config.yaml`.
- **`[sass] Error: Can't find stylesheet to import` en `quasar-variables.scss`:** la ruta pasada a `sassVariables` en `vite.config.js` debe ser absoluta (`path.resolve(__dirname, ...)`), no una cadena relativa.
- **`path is not defined` / `__dirname is not defined` en `vite.config.js`:** el archivo de configuración es un módulo ES, así que `__dirname` debe reconstruirse manualmente con `fileURLToPath(import.meta.url)` (ver la sección de configuración de Quasar). Hay que reiniciar `npm run dev` tras editar `vite.config.js` — los cambios de configuración no se recargan en caliente.

## Licencia

El framework Laravel es software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).