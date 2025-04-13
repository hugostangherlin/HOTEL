<?php
require 'conexao.php';


$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity');
$category = filter_input(INPUT_POST, 'category');

if ( $status && $capacity) {

        $sql = $pdo->prepare("INSERT INTO quarto (status, capacidade, Categoria_ID_Categoria) VALUES (:status, :capacity, :category)");

        $sql->bindValue(':status', $status); 
        $sql->bindValue(':capacity', $capacity);  
        $sql->bindValue(':category', $category);  
        $sql->execute();

        header("Location: index.php");
        exit;
} else {
    header("Location: criarquarto.php?error=dados_invalidos");
    exit;
}
?>
