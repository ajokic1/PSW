# PSW - Andrej Jokic

## Pokretanje projekta

Neophodno: 
- PHP >=7.2.0 
- MySQL
- Composer https://getcomposer.org/
- NPM

Klonirajte git repository:

```
git clone https://github.com/ajokic1/PSW.git
```

Instalirajte dependencies za laravel:

```
composer install
```

Kreirajte konfiguracioni fajl:

```
cp .env.example .env
```

U MySQL-u kreirajte novu bazu, a zatim u fajlu .env unesite podatke za
povezivanje sa bazom.

Kreirajte strukturu baze:

```
php artisan migrate
```

Generišite ključeve za autorizaciju:

```
php artisan key:generate && php artisan jwt:secret
```

Instalirajte front-end dependencies:

```
npm install && npm run dev
```

Pokrenite HTTP server:

```
php artisan serve
```
