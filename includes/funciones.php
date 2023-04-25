<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html ?? '');
    return $s;
}
function esUltimo(string $actual, string $porximo): bool{
    if($actual !== $porximo){
        return true;
    }
    return false;
}
//funciones que revisa si el ususario esta autenticado
function isAuth():void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}
function isAdmin():void{
    if (!isset($_SESSION['admin'])){
        header('Location: /');
    }
}