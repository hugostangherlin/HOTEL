<?php
require 'conexao.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);


if ($id && $status && $capacity && $category) {
    $sql = $pdo->prepare("UPDATE quartos SET status = :status, capacidade = :capacity, ID_Categoria = :category WHERE ID_Quarto = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->bindValue(':status', $status); 
    $sql->bindValue(':capacity', $capacity, PDO::PARAM_INT);  
    $sql->bindValue(':category', $category, PDO::PARAM_INT);  
    $sql->execute();
    
    header("Location: index.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
