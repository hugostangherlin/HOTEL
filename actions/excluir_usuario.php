<?php
require '../config/config.php';

if (!isset($_GET['id'])) {
    die("ID não fornecido.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE ID = :id AND Perfil_ID_Perfil = 2");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: /HOTEL/gestor/telas/solicitacao_reserva.php");
exit;
