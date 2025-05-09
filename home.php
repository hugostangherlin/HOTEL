<?php 
session_start();
$host = "localhost";
$usuario = "root";
$senha = "";
$nome_banco = "rodeo_hotel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nome_banco;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
require 'includes/navebar.php';
require 'includes/resultado_busca.php' 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Hotel</title>
  <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">

</head>
