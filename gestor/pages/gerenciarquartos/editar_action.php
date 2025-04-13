<?php
require 'conexao.php';

// Recupera os dados do formulário
$id = filter_input(INPUT_POST, 'id');
$status = filter_input(INPUT_POST, 'status');
$capacidade = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$categoria = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);

// Verifica se os dados foram recebidos corretamente
if ($id && $status && $capacidade && $categoria) {
    // Prepara a consulta de atualização
    $sql = $pdo->prepare("UPDATE quarto SET Status = :status, Capacidade = :capacidade, Categoria_ID_Categoria = :categoria WHERE ID_Quarto = :id");

    // Bind dos parâmetros
    $sql->bindValue(':status', $status);
    $sql->bindValue(':capacidade', $capacidade);
    $sql->bindValue(':categoria', $categoria);
    $sql->bindValue(':id', $id, PDO::PARAM_INT);

    // Executa a consulta
    if ($sql->execute()) {
        header("Location: index.php"); // Redireciona para o painel após sucesso
        exit;
    } else {
        echo "Erro ao salvar os dados.";
    }
} else {
    echo "Dados inválidos ou ausentes.";
}
?>
