<?php
require '../config/config.php';

// Recupera os dados do formulário
$id = filter_input(INPUT_POST, 'id');
$status = filter_input(INPUT_POST, 'status');
$capacidade = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$categoria = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
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

// Verifica se os dados foram recebidos corretamente
if ($id && $status && $capacidade && $categoria) {
    // Prepara a consulta de atualização
    $sql = "UPDATE quarto SET 
            Status = :status, 
            Capacidade = :capacidade, 
            Categoria_ID_Categoria = :categoria";

    // Se a foto foi atualizada, adiciona o campo Foto na consulta
    if ($foto_nome) {
        $sql .= ", Foto = :foto";
    }

    $sql .= " WHERE ID_Quarto = :id";

    // Prepara a consulta
    $stmt = $pdo->prepare($sql);

    // Bind dos parâmetros
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':capacidade', $capacidade);
    $stmt->bindValue(':categoria', $categoria);
    if ($foto_nome) {
        $stmt->bindValue(':foto', $foto_nome);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Executa a consulta
    if ($stmt->execute()) {
        header("Location:/HOTEL/gestor/quartos/index.php"); // Redireciona para o painel após sucesso
        exit;
    } else {
        echo "Erro ao salvar os dados.";
    }
} else {
    echo "Dados inválidos ou ausentes.";
}
?>
