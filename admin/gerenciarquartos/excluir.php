<?php

require 'conexao.php';

// Obtém o ID do quarto a ser excluído, passado pela URL (método GET), e valida se é um número inteiro
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


if ($id) {
    $sql = $pdo->prepare("DELETE FROM quarto WHERE ID_Quarto = :id");
    // Associar o valor do ID ao parâmetro da query
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();
}

// Voltar para o painel de gerenciar quartos
header("Location: index.php");
exit;

