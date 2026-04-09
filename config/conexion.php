<?php
$host = "sql206.infinityfree.com";
$db = "if0_41614516_libreria";
$user = "if0_41614516";
$pass = "Jason12252005";
$port = "3306"; 

try {
    $conexion = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos. Por favor, contacta al administrador."); 
}
?>