<?php 
require '../Config/config.php';
session_start();

// Verifica se o usuário está logado e se tem o perfil de gestor (ID 1)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: ../entrar.php");
    exit();
}
// Define o timezone
date_default_timezone_set('America/Sao_Paulo');

// Dados para saudação
$nome = $_SESSION['usuario']['nome'];
$dataAtual = date('d-m-Y');
$horaAtual = date('H:i');
$dataComHora = date('d-m-Y H:i:s');

// Define saudação conforme o horário
if ($horaAtual >= '00:00' && $horaAtual < '12:00') {
    $saudacao = "Bom dia";
} elseif ($horaAtual >= '12:00' && $horaAtual < '18:00') {
    $saudacao = "Boa tarde";
} else {
    $saudacao = "Boa noite";
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodeo Hotel - Painel de Gestor</title>

    <link rel="stylesheet" href="#">

    <!-- CDN bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- AdminLTE CDN-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- jQuery e Bootstrap (necessários para AdminLTE) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
