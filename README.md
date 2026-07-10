<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a></p>

<p align="center">
  <strong>my-laravel-project</strong><br>
  Backend en Laravel + Frontend en Vue 3 / Quasar, entorno de desarrollo local gestionado con DDEV.
</p>

---

## Stack tecnológico

- **Backend:** [Laravel](https://laravel.com) 13
- **Frontend:** [Vue 3](https://vuejs.org) + [Quasar](https://quasar.dev) (vía Vite)
- **Base de datos:** MySQL 8.0
- **Entorno de desarrollo local:** [DDEV](https://ddev.com) (basado en Docker)

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [DDEV](https://ddev.com/get-started/) (v1.25+)
- [Node.js](https://nodejs.org/) (solo necesario si quieres ejecutar `npm` fuera de DDEV; si no, `ddev exec npm ...` usa el Node.js incluido en el contenedor web)

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

### Configuración de Quasar

Instala las dependencias:

```bash
ddev exec npm install quasar @quasar/extras vue-router pinia
ddev exec npm install -D @quasar/vite-plugin sass-embedded
```

`vite.config.js`:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: { transformAssetUrls }
        }),
        quasar({
            sassVariables: path.resolve(__dirname, 'resources/css/quasar-variables.scss')
        }),
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

> ⚠️ En un archivo ESM (`vite.config.js`), `__dirname` no existe por defecto, por eso se reconstruye manualmente con `fileURLToPath`. Sin esto, una ruta relativa en `sassVariables` falla con `[sass] Error: Can't find stylesheet to import`.

### Estructura de carpetas del frontend

```
resources/js/
├── app.js                # Punto de entrada: monta Vue + Quasar + Pinia + Router
├── App.vue               # Componente raíz (<router-view />)
├── router/
│   └── index.js           # Definición de rutas (vue-router)
├── stores/                 # Stores de Pinia
├── layouts/
│   └── MainLayout.vue      # Layout principal (q-layout, q-header, etc.)
├── pages/
│   └── Home.vue             # Páginas/vistas
└── components/               # Componentes reutilizables
```

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

## Composer / NPM

| Comando | Descripción |
|---|---|
| `ddev composer install` | Instala las dependencias de PHP |
| `ddev composer require <paquete>` | Añade una nueva dependencia de PHP |
| `ddev exec npm install` | Instala las dependencias de JS |
| `ddev exec npm run dev` | Arranca el servidor de Vite con HMR |
| `ddev exec npm run build` | Compila los assets del frontend para producción |

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