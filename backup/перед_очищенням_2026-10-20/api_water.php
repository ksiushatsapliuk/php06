<?php
// Сумісна адреса роботи 05; усю логіку виконує api.php.
define('LEGACY_API_METHOD', 'POST');
$_GET['resource'] = 'plants';
$_GET['action'] = 'water';
require __DIR__ . '/api.php';
