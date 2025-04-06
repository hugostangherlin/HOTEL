<?php
require 'conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $sql = $pdo->prepare("DELETE FROM quartos WHERE ID_Quarto = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();
}

header("Location: index.php");
exit;
