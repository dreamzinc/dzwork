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
git clone https://github.com/yourusername/dzwork.git
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
│   ├── Core/             # Komponen inti
│   ├── Database/         # Layer database
│   └── Http/             # Request/Response handlers
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

// Route grup
$router->group(['prefix' => 'admin', 'middleware' => ['auth']], function($router) {
    $router->get('/dashboard', 'AdminController@dashboard');
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

### 5. Middleware

Buat middleware di `app/Middleware`:

```php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (!is_logged_in()) {
            return redirect('/login');
        }
        return $next($request);
    }
}
```

## Konfigurasi Database

### 1. Setup Environment

1. Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

2. Sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dzwork
DB_USERNAME=root
DB_PASSWORD=
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

### Keterbatasan Current Version

Beberapa fitur yang belum tersedia di versi ini:
1. Query Builder kompleks (join, having, dll)
2. Relationships antar model
3. Database migrations
4. Transactions
5. Model events/observers
6. Soft deletes
7. Query scopes
8. Eager loading

Fitur-fitur tersebut akan ditambahkan di versi mendatang.

## Command Line Interface (CLI)

DzWork menyediakan command line interface untuk mempermudah development. Berikut adalah daftar perintah yang tersedia:

### Membuat Controller Baru
```bash
php dz make:controller NamaController
```
Contoh:
```bash
php dz make:controller UserController
```

### Membuat Model Baru
```bash
php dz make:model NamaModel
```
Contoh:
```bash
php dz make:model User
```

### Membuat Middleware Baru
```bash
php dz make:middleware NamaMiddleware
```
Contoh:
```bash
php dz make:middleware AuthMiddleware
```

### Menjalankan Development Server
```bash
php dz serve
```
Secara default server akan berjalan di `http://localhost:8000`

### Cache Management
```bash
# Membersihkan cache view
php dz cache:clear

# Membersihkan cache route
php dz route:clear

# Membersihkan semua cache
php dz clear:all
```

### Maintenance Mode
```bash
# Mengaktifkan maintenance mode
php dz down

# Menonaktifkan maintenance mode
php dz up
```

### Optimasi
```bash
# Mengoptimasi autoload
php dz optimize:autoload

# Mengoptimasi route
php dz optimize:route

# Mengoptimasi semua
php dz optimize
```

### Keamanan
```bash
# Generate application key baru
php dz key:generate

# Generate encryption key
php dz key:encrypt
```

### Informasi Framework
```bash
# Menampilkan versi framework
php dz --version

# Menampilkan daftar perintah yang tersedia
php dz list

# Menampilkan bantuan untuk perintah tertentu
php dz help nama:perintah
```

### Environment
```bash
# Menampilkan environment saat ini
php dz env

# Mengatur environment
php dz env:set NAMA_VARIABLE=nilai
```

> **Catatan**: Beberapa perintah mungkin memerlukan hak akses root/administrator tergantung pada konfigurasi sistem Anda.

## Fitur

✅ Tersedia:
- Arsitektur MVC
- Sistem routing sederhana
- View template dengan layout
- Basic database abstraction
- Error handling
- Middleware support
- Localization (ID/EN)

⚠️ Keterbatasan:
- Belum ada sistem caching bawaan
- Session handling masih dasar
- Belum ada migration system yang lengkap
- Belum ada built-in authentication
- Testing framework belum tersedia
- Belum ada form validation yang komprehensif

## Best Practices

1. Selalu gunakan prepared statements untuk query database
2. Implementasikan validasi input secara manual
3. Enkripsi data sensitif sebelum menyimpan ke database
4. Gunakan HTTPS di production
5. Aktifkan error reporting hanya di development

## Rekomendasi Penggunaan

### Cocok untuk:
- Aplikasi web skala kecil-menengah
- Prototyping dan MVP
- Pembelajaran konsep MVC
- Proyek dengan kebutuhan minimalis

### Perlu pertimbangan untuk:
- Aplikasi enterprise
- Sistem dengan kebutuhan keamanan tinggi
- Aplikasi dengan traffic/load tinggi
- Sistem yang membutuhkan fitur framework advanced

## Kontribusi

Silakan berkontribusi dengan membuat pull request. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

## Lisensi

Framework ini dilisensikan di bawah [DreamZ License](LICENSE). Penggunaan untuk tujuan komersial memerlukan izin tertulis dari DreamZ Development. Untuk informasi lebih lanjut tentang lisensi komersial, silakan hubungi:

- Email: dreamzinc.id@gmail.com
