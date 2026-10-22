<?php
// Сумісна адреса роботи 05; усю логіку виконує api.php.
define('LEGACY_API_METHOD', 'POST');
$_GET['resource'] = 'plants';
unset($_GET['action'], $_GET['id']);
require __DIR__ . '/api.php';
