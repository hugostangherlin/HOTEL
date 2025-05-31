<?php
require_once '../../config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM relatorio WHERE ID_Relatorio = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: ../gestor/relatorio/filtro_usuarios.php');
        exit;
    } else {
        echo "Erro ao excluir relatório.";
    }
} else {
    echo "ID não fornecido.";
}
