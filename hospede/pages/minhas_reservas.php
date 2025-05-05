<?php
session_start();
require_once '../../config/config.php';

$usuarioId = $_SESSION['usuario_id'];

// Consulta para buscar as reservas do hóspede logado
$sql = "SELECT 
            r.ID_Reserva,
            r.Checkin,
            r.Checkout,
            q.ID_Quarto,
            q.Preco_diaria,
            c.Nome AS Categoria,
            p.Status AS Status_Pagamento
        FROM reserva r
        INNER JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
        INNER JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
        LEFT JOIN pagamentos p ON r.ID_Reserva = p.ID_Reserva
        WHERE r.usuarios_ID = :usuario_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Minhas Reservas</h2>

<table border="1" id="myTable" class="cell-border stripe hover" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Categoria</th>
            <th>Quarto</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Preço Diária</th>
            <th>Status Pagamento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservas as $reserva): ?>
            <tr>
                <td><?= $reserva['ID_Reserva']; ?></td>
                <td><?= $reserva['Categoria']; ?></td>
                <td><?= $reserva['ID_Quarto']; ?></td>
                <td><?= $reserva['Checkin']; ?></td>
                <td><?= $reserva['Checkout']; ?></td>
                <td>R$ <?= number_format($reserva['Preco_diaria'], 2, ',', '.'); ?></td>
                <td><?= $reserva['Status_Pagamento'] ?? 'Não registrado'; ?></td>
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

