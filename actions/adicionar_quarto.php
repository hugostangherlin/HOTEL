<?php
require '../config/config.php';

$status = filter_input(INPUT_POST, 'status');
$capacity = filter_input(INPUT_POST, 'capacity');
$category = filter_input(INPUT_POST, 'category');
$preco_diaria = filter_input(INPUT_POST, 'preco_diaria', FILTER_VALIDATE_FLOAT);

$foto_nome = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_nome = basename($_FILES['foto']['name']);

    // Caminho da pasta uploads (diretamente, sem subpasta img)
    $pasta_upload = '../uploads'; 
    if (!is_dir($pasta_upload)) {
        mkdir($pasta_upload, 0755, true); // Cria a pasta uploads se não existir
    }

    $extensao = strtolower(pathinfo($foto_nome, PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extensao, $permitidas)) {
        $novo_nome = uniqid('quarto_') . '.' . $extensao;
        $destino = $pasta_upload . '/' . $novo_nome; // Caminho correto para a pasta uploads

        // Tentativa de mover a imagem para a pasta uploads
        if (move_uploaded_file($foto_tmp, $destino)) {
            $foto_nome = $novo_nome;
        } else {
            die('Erro ao mover a imagem para a pasta uploads. Verifique as permissões.');
        }
    } else {
        die('Formato de imagem não permitido.');
    }
}

if ($status && $capacity && $category && $preco_diaria !== false) {
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

