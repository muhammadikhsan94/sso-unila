# SSO-UNILA
Library PHP-Laravel untuk memudahkan aplikasi menggunakan fasilitas login SSO Universitas Lampung.

## Instalasi di Laravel
1. Install Composer di Laptop/PC anda

2. Install library *composer* dengan menjalankan perintah berikut di terminal *projejct* anda:

        composer install

3. Install library *Unila/SSO* dengan menjalankan perintah berikut di terminal *project* anda:

        composer require unila/sso

## Penggunaan

### 1. Menghilangkan Namespace

Agar kode anda terlihat rapi dan membuat kode anda tidak buruk, anda dapat menghilangkan *namespace* dengan menambahkan perintah di header *controller* anda:

    use SSO\SSO;

Setelah itu untuk pemanggilan hanya menggunakan perintah seperti ini:

    SSO::$command();

### 2. Otentikasi

    SSO::authenticate();

Pemanggilan ini akan me-*redirect* browser ke login SSO. Jika otentikasi berhasil maka fungsi ini akan mengembalikan nilai `true` dan meneruskan nya ke aplikasi yang membutuhkan otentikasi tersebut.

### 3. Mendapatkan detail user

    SSO::getUser();

Fungsi ini akan mengembalikan object `stdClass` yang memiliki detail dari user yang berhasil diotentikasi. Potongan kode nya sebagai berikut:

    $user = SSO::getUser();
    echo $user->username                // menampilkan username/email dari user
    echo $user->nm_pengguna             // menampilkan nama lengkap dari user
    echo $user->a_aktif                 // menampilkan status aktif dari user
    echo $user->last_sync               // menampilkan tanggal sinkronisasi terakhir user

### 3. Memeriksa otentikasi

    SSO::check();

Fungsi ini digunakan untuk mengecek apakah user pernah berhasil diotentikasi atau belum.

### 4. Logout

    SSO::logout();

Pemanggilan ini akan mengakhiri otentikasi user.

    SSO::logout(url('http://unila.ac.id'));

Pemanggilan ini akan mengakhiri otentikasi user dan me-*redirect* ke halaman dashboard Unila.

## Koneksi dengan Aplikasi

### Login

    public function signing_process() {
        if(SSO::authenticate() == true) //mengecek apakah user telah login atau belum
        {
            if(SSO::check() == true) {
                $check = User::where('username', SSO::getUser()->username)->first(); //mengecek apakah pengguna SSO memiliki username yang sama dengan database aplikasi
                if(!is_null($check)) {
                    Auth::loginUsingId($check->id_pengguna); //mengotentikasi pengguna aplikasi
                    session()->flash('success', 'You are logged in!');
                    return redirect()->route('index');
                } else {
                    alert()->error('Data pengguna tidak ditemukan, silahkan hubungi administrator.')->html(true);
                    return redirect()->route('auth.login'); //mengarahkan ke halaman login jika pengguna gagal diotentikasi oleh aplikasi
                }
            }
        } else {
            return redirect()->route('auth.logout'); //me-*redirect* user jika otentikasi SSO gagal, diarahkan untuk mengakhiri sesi login (jika ada)
        }
    }

Fungsi ini digunakan untuk mengecek otentikasi SSO pada aplikasi

### Logout
    public function logout() {
        if(Auth::check()) { //mengecek otentikasi pada aplikasi
            Auth::logout(); //mengakhiri otentikasi pada aplikasi
            Session::flush(); //menghapus session pada aplikasi
            alert()->success('Berhasil logout');
            if(SSO::check()==true) { //mengecek otentikasi pada SSO
                SSO::logout(url('/')); //mengakhiri otentikasi pada SSO dan me-*redirect* halaman ke aplikasi
            } else {
                return redirect('auth/login')->with('pesan', 'berhasil logout'); //menampilkan halaman login
            }
        } else {
            return redirect('auth/login'); //menampilkan halaman login
        }
    }

Fungsi ini digunakan untuk mengakhiri sesi otentikasi pada SSO dan Aplikasi

### Middleware

    if( ( SSO::check()==true && Auth::check() ) || Auth::check() ) {
        return $next($request);
    } else {
        return redirect()->route('auth.logout');
    }

Fungsi ini digunakan untuk mengecek otentikasi pada SSO atau bawaan laravel

## Thanks to

1. PHP CAS
2. RistekCSUI/SSO