<?php
require 'conexao.php';

$category = filter_input(INPUT_POST, 'category');
$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

if ($category && $status && $capacity && $price) {

    $sql = $pdo->prepare("SELECT * FROM quartos WHERE id_quarto = :id");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->execute();

    if ($sql->rowCount() === 0) {
        $sql = $pdo->prepare("INSERT INTO quartos (categoria, status, capacidade, preco) VALUES (:category, :status, :capacity, :price)");

        $sql->bindValue(':category', $category); 
        $sql->bindValue(':status', $status); 
        $sql->bindValue(':capacity', $capacity);  
        $sql->bindValue(':price', $price); 

        $sql->execute();

        header("Location: gerenciarquartos.php");
        exit;
    } else {
        
        header("Location: gerenciarquartos.php?error=quarto_existente");
        exit;
    }
} else {
    header("Location: gerenciarquartos.php?error=dados_invalidos");
    exit;
}
?>
