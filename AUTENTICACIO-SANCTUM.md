# Autenticació amb Laravel Sanctum (SPA)

Aquesta guia documenta com s'ha configurat l'autenticació al projecte fent servir **Laravel Sanctum en mode SPA (Single Page Application)**.

## Per què Sanctum en mode SPA i no tokens?

Sanctum ofereix dues formes d'autenticar-se: **tokens API** (pensats per apps mòbils o serveis externs) i **autenticació SPA basada en cookies de sessió**. Com que el frontend (Vue/Quasar) viu **dins del mateix domini** que Laravel gràcies a Vite integrat (`my-laravel-project.ddev.site`), fem servir el mode de cookies:

- És més senzill: no cal gestionar manualment l'emmagatzematge ni la renovació de tokens al frontend.
- És més segur: les cookies de sessió amb `httpOnly` no són accessibles des de JavaScript, cosa que redueix el risc d'atacs XSS que robin credencials.
- Aprofita el sistema de sessions natiu de Laravel, incloent la protecció CSRF integrada.

---

## 1. Instal·lació

```bash
ddev composer require laravel/sanctum
ddev artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
ddev artisan migrate
```

**Què fa cada comanda:**
- `composer require` descarrega el paquet Sanctum al projecte.
- `vendor:publish` copia els fitxers de configuració (`config/sanctum.php`) i la migració necessària perquè puguis personalitzar-los.
- `artisan migrate` executa la migració que crea la taula `personal_access_tokens` (encara que en mode SPA no la fem servir directament per l'autenticació web, Sanctum la necessita internament i és útil si algun dia també vols exposar una API per tokens, per exemple per una app mòbil).

---

## 2. Configuració dels dominis "stateful"

`config/sanctum.php`:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

**Per què cal això:** Sanctum només tracta com "de confiança" (és a dir, autenticables per cookie/sessió) les peticions que venen d'un domini que estigui explícitament a aquesta llista. Si el domini del frontend no hi és, Sanctum tractarà la petició com si vingués d'un tercer no autenticat, encara que la cookie de sessió sigui vàlida.

Cal afegir el domini de DDEV al `.env`:
```dotenv
SANCTUM_STATEFUL_DOMAINS=my-laravel-project.ddev.site
```

---

## 3. Variables d'entorn necessàries

Al `.env` (i reflectit també a `.env.example`):

```dotenv
SANCTUM_STATEFUL_DOMAINS=my-laravel-project.ddev.site
SESSION_DOMAIN=.ddev.site
```

**Explicació:**
- **`SANCTUM_STATEFUL_DOMAINS`**: com s'ha explicat al punt 2.
- **`SESSION_DOMAIN=.ddev.site`**: defineix per a quin domini és vàlida la cookie de sessió. El punt inicial (`.ddev.site`) permet que la cookie sigui vàlida per a qualsevol subdomini `*.ddev.site` (útil si mai tens diversos projectes DDEV que necessitin compartir sessió, encara que en la majoria de casos no serà necessari). Sense aquesta configuració correcta, el navegador podria no enviar la cookie de sessió correctament, i l'usuari semblaria "desconnectat" a cada petició.

---

## 4. Middleware

`bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})
```

**Per què cal això:** aquesta línia indica a Laravel que les rutes del grup `api` han d'acceptar autenticació basada en cookies de sessió (a més del sistema de tokens habitual d'una API). Sense això, encara que la resta estigui ben configurat, les rutes `api.php` no reconeixeran l'usuari autenticat per cookie.

---

## 5. Rutes d'autenticació

`routes/api.php`:
```php
use App\Http\Controllers\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());
});
```

**Explicació:**
- `/login` i `/register` són **públiques** (qualsevol pot accedir-hi, ja que encara no hi ha usuari autenticat).
- `/logout` i `/user` estan protegides pel middleware `auth:sanctum`, que rebutja la petició (amb un `401`) si no hi ha una sessió vàlida associada.
- `/user` és útil per al frontend: permet preguntar "qui sóc?" en carregar l'aplicació, per saber si l'usuari ja té sessió iniciada (per exemple, si torna a obrir el navegador).

---

## 6. Controlador d'autenticació

`app/Http/Controllers/Auth/AuthController.php`:
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Les credencials no coincideixen.'],
            ]);
        }

        $request->session()->regenerate();

        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
```

**Explicació punt per punt:**

- **`register()`**: valida les dades (l'email ha de ser únic, la contrasenya ha de tenir confirmació via el camp `password_confirmation`), crea l'usuari amb la contrasenya xifrada (`Hash::make`), i l'autentica immediatament (`Auth::login`) perquè no hagi de fer login just després de registrar-se.

- **`login()`**: `Auth::attempt()` verifica les credencials contra la base de dades. Si fallen, llança una excepció de validació que Laravel converteix automàticament en una resposta `422` amb el missatge d'error. `$request->session()->regenerate()` és una mesura de seguretat important: genera un nou ID de sessió després del login per evitar atacs de "session fixation" (on un atacant podria fixar l'ID de sessió abans que l'usuari iniciï sessió).

- **`logout()`**: tanca la sessió (`Auth::logout`), la invalida (`invalidate`) i regenera el token CSRF (`regenerateToken`) — també per motius de seguretat, així una sessió antiga capturada per algú no es pot reutilitzar.

---

## 7. Configuració d'Axios al frontend

```bash
ddev exec npm install axios
```

`resources/js/lib/axios.js`:
```js
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.baseURL = '/';

export default axios;
```

**Explicació:**
- **`withCredentials = true`**: indica al navegador que enviï les cookies (incloent la de sessió) en cada petició, encara que tècnicament totes les peticions van al mateix domini en aquest projecte, és necessari per la mecànica interna de Sanctum.
- **`withXSRFToken = true`**: Axios llegeix automàticament la cookie `XSRF-TOKEN` (que Laravel genera) i l'envia com a capçalera `X-XSRF-TOKEN` a cada petició. Això és el que permet a Laravel verificar que la petició no és un atac CSRF (una petició falsa des d'un altre lloc web).
- **`baseURL = '/'`**: com que el frontend i el backend comparteixen domini, no cal especificar cap URL absoluta.

---

## 8. Store de Pinia per l'estat d'autenticació

`resources/js/stores/auth.js`:
```js
import { defineStore } from 'pinia';
import axios from '../lib/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
  actions: {
    async fetchCsrf() {
      await axios.get('/sanctum/csrf-cookie');
    },
    async login(email, password) {
      await this.fetchCsrf();
      await axios.post('/api/login', { email, password });
      await this.fetchUser();
    },
    async register(name, email, password, password_confirmation) {
      await this.fetchCsrf();
      await axios.post('/api/register', { name, email, password, password_confirmation });
      await this.fetchUser();
    },
    async fetchUser() {
      const { data } = await axios.get('/api/user');
      this.user = data;
    },
    async logout() {
      await axios.post('/api/logout');
      this.user = null;
    },
  },
});
```

**Explicació punt per punt:**

- **`state.user`**: guarda l'usuari autenticat (o `null` si ningú ha iniciat sessió). Com que és un store de Pinia, qualsevol component de l'aplicació hi pot accedir sense haver de passar-lo com a `prop`.
- **`isAuthenticated`**: un getter derivat, útil per exemple per mostrar/amagar botons de "Login" vs "Logout" al menú.
- **`fetchCsrf()`**: abans de fer login o registre, cal cridar `/sanctum/csrf-cookie`. Aquesta ruta especial (que Sanctum registra automàticament) fa que Laravel enviï la cookie `XSRF-TOKEN` al navegador. Sense aquest pas previ, la primera petició de login fallaria per falta de token CSRF vàlid.
- **`login()` / `register()`**: primer demanen el token CSRF, després envien les credencials, i finalment criden `fetchUser()` per actualitzar l'estat amb les dades reals de l'usuari retornades pel backend.
- **`logout()`**: crida l'endpoint de logout i neteja l'estat local (`user = null`), perquè la interfície reflecteixi immediatament que ja no hi ha sessió.

---

## 9. Protecció de rutes al frontend

`resources/js/router/index.js`:
```js
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import MainLayout from '../layouts/MainLayout.vue';
import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Home, meta: { requiresAuth: true } },
    ],
  },
  { path: '/login', component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.user) {
    try {
      await auth.fetchUser();
    } catch {
      return '/login';
    }
  }
});

export default router;
```

**Explicació:**
- **`meta: { requiresAuth: true }`**: marca quines rutes necessiten que l'usuari estigui autenticat. Es pot afegir aquesta propietat a qualsevol ruta nova que vulguis protegir.
- **`router.beforeEach`**: és un "guarda de navegació global" — s'executa abans de carregar qualsevol ruta. Si la ruta requereix autenticació i encara no sabem qui és l'usuari (per exemple, en recarregar la pàgina), intenta demanar-ho al backend (`fetchUser()`). Si el backend respon amb error (perquè no hi ha sessió vàlida), redirigeix a `/login`.

---

## 10. Pàgina de Login

`resources/js/pages/Login.vue`:
```vue
<template>
  <q-page class="flex flex-center">
    <q-card style="width: 350px">
      <q-card-section>
        <div class="text-h6">Iniciar sessió</div>
      </q-card-section>
      <q-card-section>
        <q-input v-model="email" label="Email" type="email" />
        <q-input v-model="password" label="Contrasenya" type="password" class="q-mt-sm" />
        <div v-if="error" class="text-negative q-mt-sm">{{ error }}</div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn label="Entrar" color="primary" @click="submit" />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const email = ref('');
const password = ref('');
const error = ref(null);
const router = useRouter();
const auth = useAuthStore();

async function submit() {
  error.value = null;
  try {
    await auth.login(email.value, password.value);
    router.push('/');
  } catch (e) {
    error.value = 'Credencials incorrectes';
  }
}
</script>
```

**Explicació:**
- Formulari senzill amb components de Quasar (`q-card`, `q-input`, `q-btn`).
- `submit()` crida l'acció `login` del store, i si té èxit redirigeix a la pàgina principal (`/`). Si falla (credencials incorrectes), mostra un missatge d'error sense trencar l'aplicació.

---

## 11. Provar que tot funciona

```bash
ddev artisan migrate
ddev exec npm run dev
```

Passos per comprovar-ho manualment:

1. Ves a `https://my-laravel-project.ddev.site/login`.
2. Si encara no tens cap usuari registrat, crida `/api/register` (per exemple amb Postman/Insomnia, o crea una pàgina `Register.vue` anàloga a `Login.vue`).
3. Inicia sessió amb l'email i contrasenya de l'usuari creat.
4. Comprova que et redirigeix correctament a `/` i que `useAuthStore().user` conté les dades de l'usuari.
5. Refresca la pàgina — hauria de mantenir la sessió (gràcies al guarda de rutes que crida `fetchUser()`).
6. Prova el logout i confirma que et torna a demanar login si intentes accedir a una ruta protegida.

---

## Pendents / possibles millores futures

- **Pàgina de registre** (`Register.vue`), anàloga a la de login.
- **Botó de logout** visible al `MainLayout.vue` (per exemple, al `q-toolbar`), que cridi `authStore.logout()` i redirigeixi a `/login`.
- **Recuperació de contrasenya** (`forgot-password` / `reset-password`), si l'aplicació ho necessita.
- **Verificació d'email**, si es vol exigir que l'usuari confirmi el seu correu abans d'accedir a certes funcionalitats.
- **Rols i permisos** (per exemple amb el paquet [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission)), si l'aplicació necessita diferents nivells d'accés (admin, usuari normal, etc.).
