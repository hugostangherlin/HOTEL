<?php
// Conexão com o banco de dados
require '../config/config.php';
// Obtém o ID do quarto a ser excluído, passado pela URL (método GET), e valida se é um número inteiro
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


if ($id) {
    $sql = $pdo->prepare("DELETE FROM reserva WHERE ID_Reserva = :id");
    // Associar o valor do ID ao parâmetro da query
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();
}

// Voltar para o painel de gerenciar quartos
header("Location: /HOTEL/gestor/reservas/index.php");
exit;