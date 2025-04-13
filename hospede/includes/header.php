<?php 
require '../config/config.php';
session_start();

// Verifica se o usuário está logado e se tem o perfil de gestor (ID 1)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: entrar.php");
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
