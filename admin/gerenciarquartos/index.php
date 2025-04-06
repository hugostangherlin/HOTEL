<?php
require 'conexao.php';

// Array vazio para aramzenar os dados
$lista = [];

//Consulta SQL com JOIN para buscar dados da tabela quartos e consultar tabela categoria
$sql = $pdo->query("
    SELECT q.ID_Quarto, q.Status, q.Capacidade, c.Nome AS Categoria_Nome
    FROM quartos q
    JOIN categoria c ON q.ID_Categoria = c.ID_Categoria
");
// Retorno da consulta
if ($sql->rowCount() > 0) {
    // Armazena os daados em um array assocativo 
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gereciamento de Quartos</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h2>Painel de Gereciamento de Quartos</h2>
    <a href="criarquarto.php" class="btn-add">Adicionar quarto</a>

    <table border="1" width="100%">
        <tr>
            <th><strong>Número do Quarto</strong></th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Capacidade</th>
            <th>Ações</th>
        </tr>
        <!-- Exibe os dados amarzenados no array -->
        <?php foreach ($lista as $quartos): ?>
            <tr>
                <td><?= $quartos['ID_Quarto']; ?></td>
                <td><?= $quartos['Categoria_Nome']; ?></td>
                <td><?= $quartos['Status']; ?></td>
                <td><?php echo $quartos['Capacidade']; ?></td>
                <td>
                    <a href="editar.php?id=<?= $quartos['ID_Quarto']; ?>" class="btn-action btn-edit">Editar</a>
                    <a href="excluir.php?id=<?= $quartos['ID_Quarto']; ?>" class="btn-action btn-delete" onclick="return confirm('Você tem certeza que deseja excluir esse quarto?')">Excluir</a>
                </td>

            </tr>
        <?php endforeach; ?>

    </table>


</body>

</html>