<?php
require_once '../../config/config.php';

// Array vazio para armazenar os dados
$listaReservas = [];

// Consulta SQL com JOIN para buscar dados da tabela reservas, quartos, categoria e usuários
$sql = $pdo->query("
    SELECT r.ID_Reserva, r.checkin, r.checkout, q.ID_Quarto, q.Status AS Quarto_Status, 
    q.Capacidade, q.Preco_diaria, c.Nome AS Categoria, u.Nome AS Hospede_Nome
    FROM reserva r
    INNER JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
    INNER JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
    LEFT JOIN usuarios u ON r.usuarios_ID = u.ID
");

// Retorno da consulta
if ($sql->rowCount() > 0) {
    // Armazena os dados em um array associativo
    $listaReservas = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gerenciamento de Reservas</title>
    <link rel="stylesheet" href="reservas.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
</head>
<body>
    <h2>Painel de Gerenciamento de Reservas</h2>

    <a href="adicionar_reserva.php" class="btn-add">Nova Reserva</a>

    <table border="1" id="myTable" class="cell-border stripe hover" width="100%">
        <thead>
            <tr>
                <th><strong>Número da 
                    Reserva</strong></th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Hóspede</th>
                <th>Quarto</th>
                <th>Status</th>
                <th>Capacidade</th>
                <th>Categoria</th>
                <th>Preço Diária</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaReservas as $reserva): ?>
                <tr>
                    <td><?= $reserva['ID_Reserva']; ?></td>
                    <td><?= date('d/m/Y', strtotime($reserva['checkin'])); ?></td>
                    <td><?= date('d/m/Y', strtotime($reserva['checkout'])); ?></td>
                    <td><?= $reserva['Hospede_Nome']; ?></td>
                    <td><?= $reserva['ID_Quarto']; ?></td>
                    <td>
                        <?php
                        $hoje = date('Y-m-d');
                        $checkin = $reserva['checkin'];
                        $checkout = $reserva['checkout'];

                        if ($reserva['Quarto_Status'] === 'Manutencao') {
                            echo 'Manutenção';
                        } elseif ($checkin <= $hoje && $checkout >= $hoje) {
                            echo 'Ocupado';
                        } else {
                            echo 'Disponível';
                        }
                        ?>
                    </td>
                    <td><?= $reserva['Capacidade']; ?> pessoas</td>
                    <td><?= $reserva['Categoria']; ?></td>
                    <td>R$ <?= number_format($reserva['Preco_diaria'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="#" class="btn-action btn-edit">Editar Reserva</a>
                        <a href="../../actions/excluir_reserva.php?id=<?= $reserva['ID_Reserva']; ?>" class="btn-action btn-delete" onclick="return confirm('Você tem certeza que deseja excluir essa reserva?')">Excluir Reserva</a>
                        <a href="detalhes_reserva.php?id=<?= $reserva['ID_Reserva']; ?>" class="btn-action btn-more">Pagamento</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script type="text/javascript">
        var table = new DataTable('#myTable', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/Portuguese.json',
            },
        });
    </script>
</body>
</html>
