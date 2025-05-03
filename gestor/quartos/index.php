<?php
require_once '../../config/config.php';

// Array vazio para armazenar os dados
$lista = [];

// Consulta SQL com JOIN para buscar dados da tabela quartos e consultar tabela categoria
$sql = $pdo->query("
    SELECT q.*, c.Nome as CategoriaNome,
    (
        SELECT COUNT(*) FROM reserva r
        WHERE r.Quarto_ID_Quarto = q.ID_QUARTO
        AND CURDATE() BETWEEN r.Checkin AND r.Checkout
    ) as Ocupado
    FROM Quarto q
    INNER JOIN Categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
");
// Retorno da consulta
if ($sql->rowCount() > 0) {
    // Armazena os dados em um array associativo
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gerenciamento de Quartos</title>
    <link rel="stylesheet" href="quartos.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
</head>
<body>
    <h2>Painel de Gerenciamento de Quartos</h2>
    <a href="adicionar_quarto.php" class="btn-add">Adicionar quarto</a>

    <table border="1" id="myTable" class="cell-border stripe hover" width="100%">
        <thead>
            <tr>
                <th><strong>Número do Quarto</strong></th>
                <th>Categoria</th>
                <th>Status</th>
                <th>Capacidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista as $quarto): ?>
                <tr>
                    <td><?= $quarto['ID_Quarto']; ?></td>
                    <td><?= $quarto['CategoriaNome']; ?></td>
                    <td>
    <?php
    if ($quarto['Status'] === 'Manutencao') {
        echo 'Manutenção';
    } elseif ($quarto['Ocupado'] > 0) {
        echo 'Ocupado';
    } else {
        echo 'Disponível';
    }
    ?>
</td>

                    <td><?= $quarto['Capacidade']; ?></td>
                    <td>
                        <a href="editar_quarto.php?id=<?= $quarto['ID_Quarto']; ?>" class="btn-action btn-edit">Editar Quarto</a>
                        <a href="../../actions/excluir.php?id=<?= $quarto['ID_Quarto']; ?>" class="btn-action btn-delete" onclick="return confirm('Você tem certeza que deseja excluir esse quarto?')">Excluir Quarto</a>
                        <a href="maisdetalhes.php?id=<?= $quarto['ID_Quarto']; ?>" class="btn-action btn-more">Mais informações</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script type="text/javascript">
        var table = new DataTable('#myTable', {
            language: {
                url: './DataTable/pt-BR.json',
            },
        });
    </script>
</body>
</html>