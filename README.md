# DzWork PHP Framework

DzWork adalah framework PHP minimalis yang dirancang untuk membangun aplikasi web dengan struktur yang bersih dan terorganisir.

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- Composer
- Ekstensi PHP yang dibutuhkan:
  - PDO PHP Extension
  - OpenSSL PHP Extension
  - Mbstring PHP Extension

## Instalasi

1. Clone repositori
```bash
git clone https://github.com/dreamzinc/dzwork.git
```

2. Install dependensi dengan Composer
```bash
composer install
```

3. Konfigurasi web server
   - Arahkan document root ke direktori `public`
   - Pastikan mod_rewrite diaktifkan (untuk Apache)
   - Untuk Nginx, konfigurasikan location block untuk mengarahkan semua request ke `public/index.php`

4. Konfigurasi aplikasi
   - Salin `.env.example` ke `.env`
   - Sesuaikan konfigurasi database dan pengaturan lainnya di file `.env`

## Struktur Direktori

```
dzwork/
├── app/                    # Kode aplikasi
│   ├── Controllers/       # Controller aplikasi
│   ├── Models/           # Model database
│   ├── Views/            # Template view
│   └── Middleware/       # Middleware aplikasi
├── config/                # File konfigurasi
├── public/                # Document root
│   └── index.php         # Front controller
├── src/                   # Kode inti framework
│   └── Core/             # Komponen inti framework
└── vendor/                # Dependensi composer
```

## Panduan Penggunaan

### 1. Routing

Definisikan route di `app/routes.php`:

```php
// Route dasar
$router->get('/', 'HomeController@index');

// Route dengan parameter
$router->get('/users/{id}', 'UserController@show');

// Route grup dengan prefix
$router->group(['prefix' => 'admin'], function($router) {
    $router->get('/dashboard', 'AdminController@dashboard');
});

// Route grup untuk API
$router->api('1', function($router) {
    $router->get('/users', 'Api\UserController@index');
});
```

### 2. Controller

Buat controller baru di `app/Controllers`:

```php
namespace App\Controllers;

use DzWork\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home', [
            'title' => 'Selamat Datang'
        ]);
    }
}
```

### 3. Model

Buat model di `app/Models`:

```php
namespace App\Models;

use DzWork\Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email'];
}
```

### 4. View

Buat view di `app/Views`:

```php
<!-- app/Views/home.php -->
<?php $this->extend('layouts/app') ?>

<?php $this->section('content') ?>
    <h1><?= $title ?></h1>
<?php $this->endSection() ?>
```

## Fitur Framework

1. **Routing yang Fleksibel**
   - Route dasar (GET, POST, PUT, DELETE)
   - Route dengan parameter
   - Route grouping

2. **MVC Architecture**
   - Model dengan basic CRUD operations
   - Controller dengan response handling
   - View dengan layout system

3. **Database Integration**
   - PDO-based database connection
   - Basic query builder
   - Model fillable protection

4. **View System**
   - Layout inheritance
   - Section management
   - Data passing
   - Basic helper functions

5. **Error Handling**
   - Development/Production mode
   - Error logging
   - Custom error pages

6. **Localization**
   - Multi-language support
   - Timezone setting
   - Basic translation helper

## Konfigurasi Database

### 1. Setup Environment

1. Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

2. Sesuaikan konfigurasi di file `.env`:
```env
# Application Settings
APP_NAME=DzWork
APP_ENV=development
APP_DEBUG=true

# Database Configuration
DB_HOST=127.0.0.1
DB_DATABASE=dzwork
DB_USERNAME=root
DB_PASSWORD=

# Logging
LOG_PATH=storage/logs/app.log

# Timezone and Locale
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
```

### 2. Penggunaan Database

Contoh penggunaan Model:

```php
namespace App\Models;

use DzWork\Core\Model;

class User extends Model
{
    protected $table = 'users'; // Opsional, secara default akan menggunakan nama model (users)
    protected $fillable = ['name', 'email', 'password']; // Field yang boleh diisi

    // Contoh method untuk mencari user berdasarkan email
    public static function findByEmail($email)
    {
        return static::where('email', '=', $email);
    }
}
```

Penggunaan di Controller:

```php
namespace App\Controllers;

use App\Models\User;
use DzWork\Core\Controller;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua user
        $users = User::all();
        return $this->view('users/index', ['users' => $users]);
    }

    public function show($id)
    {
        // Mencari user berdasarkan ID
        $user = User::find($id);
        return $this->view('users/show', ['user' => $user]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        // Validasi input (implementasi manual)
        if (empty($data['email']) || empty($data['password'])) {
            return $this->response->json([
                'error' => 'Email dan password harus diisi'
            ], 422);
        }

        // Buat user baru
        $user = (new User())->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);

        return $this->response->json([
            'message' => 'User berhasil dibuat',
            'user' => $user
        ], 201);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $user = (new User())->update($id, $data);
        
        return $this->response->json([
            'message' => 'User berhasil diupdate',
            'user' => $user
        ]);
    }

    public function delete($id)
    {
        (new User())->delete($id);
        return $this->response->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}
```

### 3. Metode yang Tersedia di Model

```php
// Mengambil semua data
$users = User::all();

// Mencari berdasarkan ID
$user = User::find(1);

// Query where sederhana
$activeUsers = User::where('status', 'active');                  // status = 'active'
$premiumUsers = User::where('type', '=', 'premium');            // type = 'premium'
$seniorUsers = User::where('age', '>', 50);                     // age > 50

// Insert data
$user = (new User())->create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Update data
$updated = (new User())->update($id, [
    'name' => 'John Updated'
]);

// Delete data
$deleted = (new User())->delete($id);
```

## Command Line Interface (CLI)

DzWork menyediakan command line interface untuk mempermudah development. Berikut adalah daftar perintah yang tersedia:

### Membuat Controller Baru
```bash
php dz make:controller NamaController
```
Contoh:
```bash
php dz make:controller HomeController
```

### Membuat Model Baru
```bash
php dz make:model NamaModel
```
Contoh:
```bash
php dz make:model User
```

### Membuat View Baru
```bash
php dz make:view nama_view
```
Contoh:
```bash
php dz make:view home
```

### Menjalankan Development Server
```bash
php dz serve
```
Secara default server akan berjalan di `http://localhost:8000`

> **Catatan**: Beberapa perintah mungkin memerlukan hak akses root/administrator tergantung pada konfigurasi sistem Anda.

## Best Practices

1. Selalu gunakan prepared statements untuk query database
2. Implementasikan validasi input secara manual
3. Enkripsi data sensitif sebelum menyimpan ke database
4. Gunakan layout untuk konsistensi tampilan
5. Pisahkan logika bisnis ke dalam model
6. Jaga controller tetap ramping (thin controllers)

## Use Cases

DzWork cocok untuk:
- Aplikasi web skala kecil-menengah
- Prototyping dan MVP
- Pembelajaran konsep MVC
- Proyek dengan kebutuhan minimalis

## Lisensi

Framework ini dilisensikan di bawah DreamZ License. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.
