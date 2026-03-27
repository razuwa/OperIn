<?php
session_start();

if (!isset($_SESSION['products'])) {
    require 'dataProduk.php';
    $_SESSION['products'] = $products;
}

$_SESSION['products'][] = [
    "name"     => $_POST['name'],
    "price"    => $_POST['price'],
    "kondisi"  => $_POST['kondisi'],
    "fakultas" => $_POST['fakultas'],
    "image"    => "assets/default.jpg"
];

header('Location: index.php');