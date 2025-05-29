<?php
// Conexão com o banco de dados
require '../config/config.php';

// Obtém o ID do quarto a ser excluído
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Verifica se há reservas associadas a esse quarto
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reserva WHERE Quarto_ID_Quarto = ?");
    $stmt->execute([$id]);
    $total = $stmt->fetchColumn();

    if ($total > 0) {
        echo "Erro: Não é possível excluir um quarto que já possui reservas.";
        exit;
    }

    // Executa a exclusão se não houver reservas
    $sql = $pdo->prepare("DELETE FROM quarto WHERE ID_Quarto = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();
}

// Redireciona de volta para a página de quartos
header("Location: /HOTEL/gestor/quartos/index.php");
exit;
