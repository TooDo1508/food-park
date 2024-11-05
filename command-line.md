//remove table and history run throw this file
php artisan migrate:rollback --path=/database/migrations/2024_11_04_032908_create_why_choose_us_table.php

php artisan db:seed --class=WhyChooseUsSeeder
php artisan datatables:make Product
php artisan make:model Product -mf
