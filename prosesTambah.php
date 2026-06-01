<?php
session_start();
require 'needLogin.php'; 
require 'require/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $price = (int)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $faculty_id = (int)$_POST['faculty_id'];
    $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    
    $user_id = $_SESSION['user_id'];

    $file_name = $_FILES['image']['name'];
    $tmp_name  = $_FILES['image']['tmp_name'];
    
    // ekstensi berkas
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // validasi ekstensi
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($file_extension, $allowed_extensions)) {
        die("Error: Format file tidak diizinkan! Hanya menerima JPG, JPEG, PNG, atau WEBP.");
    }

    // randomize nama file biar tidak sama
    $new_file_name = time() . '_' . uniqid() . '.' . $file_extension;
    $folder_destination = "assets/" . $new_file_name;

    // pindahkan dari temp ke folder
    if (move_uploaded_file($tmp_name, $folder_destination)) {
        
        $query_insert = "INSERT INTO products (user_id, name, price, description, image, kondisi, category_id, faculty_id) 
                         VALUES ($user_id, '$name', $price, '$description', '$folder_destination', '$kondisi', $category_id, $faculty_id)";
        
        if (mysqli_query($koneksi, $query_insert)) {
            //redirect ke halaman 
            header('Location: produk.php?success=Barang Berhasil Ditambahkan');
            exit();
        } else {
            echo "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
        }
    }
} else {
    header('Location: tambahBarang.php');
    exit();
}
?>