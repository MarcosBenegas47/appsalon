<?php

$db = mysqli_connect('127.0.0.1', 'root', 'root', 'appsalon_mvc');

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "error de depuración: " . mysqli_connect_error();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
