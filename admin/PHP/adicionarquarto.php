<?php
require 'conexao.php';


$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity');

if ( $status && $capacity) {

    $sql = $pdo->prepare("SELECT * FROM quartos WHERE id_quarto = :id");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->execute();

    if ($sql->rowCount() === 0) {
        $sql = $pdo->prepare("INSERT INTO quartos (status, capacidade) VALUES (:status, :capacity)");

        $sql->bindValue(':status', $status); 
        $sql->bindValue(':capacity', $capacity);  

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
