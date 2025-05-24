<?php
require_once '../../config/config.php';
session_start();

if (!isset($_POST['id'])) {
    echo "ID da avaliação não fornecido.";
    exit;
}

$id = $_POST['id'];

$sql = "DELETE FROM avaliacao WHERE ID_Avaliacao = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: minhas_reservas.php?sucesso=avaliacao_excluida");
    exit;
} else {
    echo "Erro ao excluir avaliação.";
}
