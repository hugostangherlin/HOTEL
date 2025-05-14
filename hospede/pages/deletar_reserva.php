<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}


$usuario_id = $_SESSION['usuario']['ID'];
$reserva_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($reserva_id <= 0) {
    echo "<script>alert('ID da reserva inválido.'); history.back();</script>";
    exit();
}

// Verifica se a reserva pertence ao usuário e se já foi solicitada
$sql = $pdo->prepare("SELECT solicitou_exclusao FROM reserva WHERE ID_Reserva = :id AND usuarios_ID = :usuario_id");
$sql->bindValue(':id', $reserva_id);
$sql->bindValue(':usuario_id', $usuario_id);
$sql->execute();
$reserva = $sql->fetch();

if (!$reserva) {
    echo "<script>alert('Reserva não encontrada ou não pertence a você.'); history.back();</script>";
    exit();
}

if ($reserva['solicitou_exclusao'] == 1) {
    echo "<script>alert('Você já solicitou a exclusão dessa reserva. Aguarde o gestor.'); history.back();</script>";
    exit();
}

// Atualiza o campo na tabela de reservas
$sql = $pdo->prepare("UPDATE reserva SET solicitou_exclusao = 1, Data_Solicitacao_Exclusao = NOW() WHERE ID_Reserva = :id AND usuarios_ID = :usuario_id");
$sql->bindValue(':id', $reserva_id);
$sql->bindValue(':usuario_id', $usuario_id);
$sql->execute();

echo "<script>alert('Solicitação enviada. Aguarde a análise do gestor.'); location.href='minhas_reservas.php';</script>";
