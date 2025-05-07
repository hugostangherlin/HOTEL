<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado e se é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$nomeUsuario = $_SESSION['usuario']['nome'];
$usuarioId = $_SESSION['usuario']['id'];

// Consulta para buscar as reservas do hóspede logado
$sql = "SELECT 
            r.ID_Reserva,
            r.Checkin,
            r.Checkout,
            q.ID_Quarto,
            q.Capacidade,
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
            <th>Quarto</th>
            <th>Capacidade</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Preço Diária</th>
            <th>Status Pagamento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservas as $reserva): ?>
            <tr>
                <td><?= htmlspecialchars($reserva['Categoria']); ?></td>
                <td><?= htmlspecialchars($reserva['Capacidade']); ?> pessoas</td>
                <td><?= date('d/m/Y', strtotime($reserva['Checkin'])); ?></td>
<td><?= date('d/m/Y', strtotime($reserva['Checkout'])); ?></td>
                <td>R$ <?= number_format($reserva['Preco_diaria'], 2, ',', '.'); ?></td>
                <td><?= htmlspecialchars($reserva['Status_Pagamento'] ?? 'Não registrado'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- DataTables Script -->
<script type="text/javascript">
    var table = new DataTable('#myTable', {
        language: {
            url: './DataTable/pt-BR.json'
        },
    });
</script>
