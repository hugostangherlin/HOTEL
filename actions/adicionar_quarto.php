<?php
require '../config/config.php';

$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity');
$category = filter_input(INPUT_POST, 'category');
$preco_diaria = filter_input(INPUT_POST, 'preco_diaria', FILTER_VALIDATE_FLOAT);

// Tratamento da imagem
$foto_nome = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_nome = basename($_FILES['foto']['name']);
    $destino = '../uploads/' . $foto_nome;
    move_uploaded_file($foto_tmp, $destino);
}

if ($status && $capacity) {

    $sql = $pdo->prepare("INSERT INTO quarto (status, capacidade, Categoria_ID_Categoria, preco_diaria, foto) VALUES (:status, :capacity, :category, :preco_diaria, :foto)");

    $sql->bindValue(':status', $status);
    $sql->bindValue(':capacity', $capacity);
    $sql->bindValue(':category', $category);
    $sql->bindValue(':preco_diaria', $preco_diaria);
    $sql->bindValue(':foto', $foto_nome);
    $sql->execute();

    header("Location: /HOTEL/gestor/quartos/index.php");
    exit;
} else {
    header("Location: criarquarto.php?error=dados_invalidos");
    exit;
}
