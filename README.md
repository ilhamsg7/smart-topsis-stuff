<h1 style="text-align:center; font-weight:bolder;">Catatan Sebelum Melakukan Kloning Job Calling</h1>

## Beberapa Catatan Terkait Penggunaan Repository Ini
1. Silahkan melakukan kloning pada repository ini dengan meng-copy url repository

2. Setelah melakukan kloning ketikan di terminal perintah berikut. Bertujuan agar APP KEY update otomatis dan vendor akan terinstal serta .env akan terbentuk
     ```shell
        composer update
     ```
     ```shell
        cp .env.example .env
     ```
     ```shell
        php artisan key:generate
     ```
3. Install beberapa package berikut
    - Laravel Debugbar -> Untuk membantu proses debug
        ```shell
        composer require barryvdh/laravel-debugbar --dev
        ```
    - Laravel Query Detector -> Membantu proses pengecekan query
        ```shell
        composer require beyondcode/laravel-query-detector --dev
        ```
    - Laravel IDE Helper
        ```shell
        composer require --dev barryvdh/laravel-ide-helper
        ```
    - Doctrine/dbal
        ```shell
        composer require doctrine/dbal
        ```

4. Untuk halaman admin bisa menggunakan template
    <ul>
        <li> <a href="https://getstisla.com/">STISLA</a></li>
        <li> <a href="https://coreui.io/">Core UI</a></li>
        <li> <a href="https://adminlte.io/">Admin LTE</a></li>
    <ul>
