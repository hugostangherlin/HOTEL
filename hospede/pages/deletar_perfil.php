<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

// Verifica se já foi solicitada
$sql = $pdo->prepare("SELECT solicitou_exclusao FROM usuarios WHERE ID = :id");
$sql->bindValue(':id', $usuario_id);
$sql->execute();
$usuario = $sql->fetch();

if ($usuario && $usuario['solicitou_exclusao'] == 1) {
    echo "<script>alert('Você já solicitou a exclusão. Aguarde o gestor.'); history.back();</script>";
    exit();
}

// Atualiza o campo
$sql = $pdo->prepare("UPDATE usuarios SET solicitou_exclusao = 1 WHERE ID = :id");
$sql->bindValue(':id', $usuario_id);
$sql->execute();

echo "<script>alert('Solicitação enviada. Aguarde a análise do gestor.'); location.href='pag_hospede.php';</script>";
