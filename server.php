<?php
/*
Router untuk PHP built-in server
Menangani static files dan route dynamic requests ke index.php
*/

$requested_file = __DIR__ . '/public' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jika file static ada, serve langsung
if (file_exists($requested_file)) {
    return false;
}

// Jika tidak ada, route ke index.php
require __DIR__ . '/public/index.php';
