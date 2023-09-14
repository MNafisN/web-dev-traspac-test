# web-dev-traspac-test

Pengerjaan test Web Developer dari PT. Traspac oleh Mohammad Nafis Naufally

## Installation

### Pulling Repository

Untuk mendapatkan salinan repository ini saya sarankan dilakukan dengan 2 cara yaitu melalui:
- Pull repository ini pada folder lokal yang kosong dan jalankan command
`` git clone https://github.com/MNafisN/web-dev-traspac-test.git ``.
- Download arsip repository ini dan ekstrak ke direktori lokal.

### Requirements

- PHP versi 8.0 atau 8.1,
- Composer,
- Node js versi 18.x,
- NPM versi 9.x,

### Setting Environment

- Pastikan PHP terinstall dengan cara mengecek versinya dengan menjalankan command `` php -v ``.
- Pastikan Composer terinstall dengan cara mengecek versinya dengan menjalankan command `` composer ``.
- Pastikan Node js terinstall dengan cara mengecek versinya dengan menjalankan command `` node -v ``.
- Pastikan NPM terinstall dengan cara mengecek versinya dengan menjalankan command `` npm -v ``.

### Installing Project

Setting file .env project dengan cara menduplikat file .env.example atau .env.testing kemudian rename file tersebut menjadi .env dan tentukan nama aplikasi, database yang digunakan, dan juga file system untuk menentukan lokasi penyimpanan file.

Daftar command di bawah ini disarankan untuk dijalankan secara berurutan agar aplikasi siap dijalankan maupun dikembangkan lagi.
- `` composer install ``
- `` php artisan key:generate --ansi ``
- `` php artisan route cache ``
- `` php artisan jwt:secret ``
- `` php artisan migrate ``
- `` php artisan db:seed ``
- `` npm install ``

### Running Project

Untuk menjalankan API pada aplikasi tersebut, jalankan command `` php artisan serve ``

## Application Testing

### Postman

Testing dapat dilakukan melalui daftar request collection dari Postman untuk testing fitur dari aplikasi tersebut. Berikut ini adalah link dokumentasi pada Postman collection di atas:

[API Postman Documentation](https://documenter.getpostman.com/view/26426855/2s9YC4Usr7).
