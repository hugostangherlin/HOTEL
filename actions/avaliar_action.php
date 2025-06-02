<?php
session_start();
require '../config/config.php';

// Verifica se o usuário está logado e se tem perfil de hóspede (2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: /HOTEL/entrar.php"); // Ajuste o caminho se necessário
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario']['id'];
    $id_reserva = $_POST['id_reserva'] ?? null;
    $nota = isset($_POST['nota']) ? intval($_POST['nota']) : 0;
    $comentario = trim($_POST['comentario'] ?? '');

    // Validações básicas
    if (!$id_reserva || $nota < 1 || $nota > 5) {
        echo "Dados inválidos para avaliação.";
        exit();
    }

    // Verifica se a reserva pertence ao usuário
    $stmt = $pdo->prepare("SELECT * FROM reserva WHERE ID_Reserva = ? AND usuarios_ID = ?");
    $stmt->execute([$id_reserva, $id_usuario]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        echo "Reserva inválida.";
        exit();
    }

    // Verifica se já avaliou essa reserva
    $verifica = $pdo->prepare("SELECT * FROM avaliacao WHERE ID_Reserva = ?");
    $verifica->execute([$id_reserva]);

    if ($verifica->rowCount() > 0) {
        echo "Você já avaliou essa reserva.";
        exit();
    }

    // Insere avaliação
    $stmt = $pdo->prepare("INSERT INTO avaliacao (ID_Reserva, ID_Usuario, Nota, Comentario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_reserva, $id_usuario, $nota, $comentario]);

  header("Location: ../hospede/pages/pag_hospede.php");
    exit();
}