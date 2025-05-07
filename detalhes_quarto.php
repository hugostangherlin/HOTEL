<?php
require '../../config/config.php';
session_start();

// Inclui o cabeçalho
include '../../includes/header_hospede.php';

// Verifica se o ID foi passado corretamente
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}

// Consulta dados do quarto
$sql = $pdo->prepare("
SELECT q.*, c.Nome AS nome_categoria
FROM quarto q
JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
WHERE q.ID_Quarto = :id
");
$sql->bindValue(':id', $id);
$sql->execute();
$quarto = $sql->fetch(PDO::FETCH_ASSOC);

if (!$quarto) {
    echo "Quarto não encontrado.";
    exit;
}

// Consultar outros quartos na mesma categoria
$quartos_categoria = $pdo->prepare("
SELECT * FROM quarto WHERE Categoria_ID_Categoria = :categoria_id AND ID_Quarto != :id
");
$quartos_categoria->bindValue(':categoria_id', $quarto['Categoria_ID_Categoria']);
$quartos_categoria->bindValue(':id', $id);
$quartos_categoria->execute();
$quartos_recomendados = $quartos_categoria->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Quarto</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .quarto-img {
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
        }
        .quarto-conteudo {
            padding: 30px;
        }
        .quarto-conteudo h2 {
            margin: 0 0 10px;
            color: #333;
        }
        .quarto-conteudo p {
            margin: 8px 0;
            color: #555;
        }
        .descricao {
            margin-top: 15px;
            font-size: 14px;
            color: #777;
            font-style: italic;
        }
        .icons {
            margin-top: 15px;
        }
        .icons i {
            color: #e63946;
            margin-right: 10px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            background-color: #e63946;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #c72d39;
        }
        .btn-secundario {
            background-color: #999;
        }
        .btn-secundario:hover {
            background-color: #777;
        }
        .outros-quartos {
            margin-top: 50px;
        }
        .outros-quartos h3 {
            color: #333;
        }
        .outros-quartos .quarto-card {
            display: inline-block;
            width: 48%;
            margin-right: 4%;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .outros-quartos .quarto-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .outros-quartos .quarto-card p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="quarto-img" style="background-image: url('../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>');"></div>
    
    <div class="quarto-conteudo">
        <h2>Quarto - <?= htmlspecialchars($quarto['nome_categoria']) ?> </h2>
        <p><strong>Status:</strong> <?= htmlspecialchars($quarto['Status']) ?></p>
        <p><strong>Capacidade:</strong> <?= htmlspecialchars($quarto['Capacidade']) ?> pessoa(s)</p>
        <p><strong>Preço da Diária:</strong> R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></p>
        
        <div class="descricao">
            <p>Este quarto é ideal para quem busca conforto e praticidade. Acomoda até <?= htmlspecialchars($quarto['Capacidade']) ?> pessoas e oferece todas as comodidades necessárias para uma estadia agradável.</p>
        </div>

        <div class="icons">
            <i class="fas fa-bed"></i><span>2 camas</span>
            <i class="fas fa-bath"></i><span>2 banheiros</span>
            <i class="fas fa-wifi"></i><span>Wi-Fi grátis</span>
        </div>

        <a href="index.php" class="btn btn-secundario">Voltar</a>
        <button class="btn" onclick="reservar()">Reservar este quarto</button>
    </div>
</div>

<div class="outros-quartos">
    <h3>Outros quartos disponíveis na categoria <?= htmlspecialchars($quarto['nome_categoria']) ?></h3>
    <div class="quartos">
        <?php foreach ($quartos_recomendados as $recomendado): ?>
            <div class="quarto-card">
                <img src="../../uploads/<?= htmlspecialchars($recomendado['Foto']) ?>" alt="Foto do quarto">
                <p><strong>Quarto:</strong> <?= htmlspecialchars($recomendado['ID_Quarto']) ?></p>
                <p><strong>Preço da diária:</strong> R$ <?= number_format($recomendado['Preco_diaria'], 2, ',', '.') ?></p>
                <a href="detalhes-quartos.php?id=<?= $recomendado['ID_Quarto'] ?>" class="btn btn-secundario">Ver detalhes</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function reservar() {
    const confirmado = confirm("Você deseja fazer uma reserva para este quarto?");
    if (confirmado) {
        window.location.href = "../reserva/form_reserva.php?id_quarto=<?= $quarto['ID_Quarto'] ?>";
    }
}
</script>

</body>
</html>

