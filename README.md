#SSO-UNILA
Library PHP-Laravel untuk memudahkan aplikasi menggunakan fasilitas login SSO Universitas Lampung.

##Instalasi
1. Install Composer di Laptop/PC anda

2. Install library *composer* dengan menjalankan perintah berikut di terminal *projejct* anda:

        composer install

3. Install library *Unila/SSO* dengan menjalankan perintah berikut di terminal *project* anda:

        composer require Unila/SSO

##Penggunaan

###1. Menghilangkan Namespace

Agar kode anda terlihat rapi dan membuat kode anda tidak buruk, anda dapat menghilangkan *namespace* dengan menambahkan perintah di header *controller* anda:

        use SSO\SSO;

Setelah itu untuk pemanggilan hanya menggunakan perintah seperti ini:

        SSO::$command();

###2. Otentikasi

        SSO::authenticate();

Pemanggilan ini akan me-*redirect* browser ke login SSO. Jika otentikasi berhasil maka fungsi ini akan mengembalikan nilai `true` dan meneruskan nya ke aplikasi yang membutuhkan otentikasi tersebut.

###3. Mendapatkan detail user

    SSO::getUser();

Fungsi ini akan mengembalikan object `stdClass` yang memiliki detail dari user yang berhasil diotentikasi. Potongan kode nya sebagai berikut:

        $user = SSO::getUser();
        echo $user->username            // menampilkan username dari user
        echo $user->firstname           // menampilkan nama depan dari user
        echo $user->lastname            // menampilkan nama belakang dari user

###3. Memeriksa otentikasi

        SSO::check();

Fungsi ini digunakan untuk mengecek apakah user pernah berhasil diotentikasi atau belum.

###4. Logout

        SSO::logout();

Pemanggilan ini akan mengakhiri otentikasi user.

##Thanks to

1. PHP CAS
2. RistekCSUI/SSO