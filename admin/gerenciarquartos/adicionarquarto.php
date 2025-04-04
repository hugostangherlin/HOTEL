<?php
require 'conexao.php';


$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity');
$category = filter_input(INPUT_POST, 'category');

if ( $status && $capacity) {

        $sql = $pdo->prepare("INSERT INTO quartos (status, capacidade, ID_Categoria) VALUES (:status, :capacity, :category)");

        $sql->bindValue(':status', $status); 
        $sql->bindValue(':capacity', $capacity);  
        $sql->bindValue(':category', $category);  
        $sql->execute();

        header("Location: gerenciarquartos.php");
        exit;
} else {
    header("Location: gerenciarquartos.php?error=dados_invalidos");
    exit;
}
?>
