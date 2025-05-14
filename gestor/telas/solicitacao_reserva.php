<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: ../entrar.php");
    exit();
}
?>

<div class="container mt-4">
    <h2>Solicitações de Exclusão de Reservas</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reserva</th>
                <th>Hóspede</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Quarto</th>
                <th>Data da Solicitação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = $pdo->query("
                SELECT r.ID_Reserva, r.Checkin, r.Checkout, r.Data_Solicitacao_Exclusao,
                       u.nome AS hospede, q.ID_Quarto, c.Nome AS categoria
                FROM reserva r
                JOIN usuarios u ON r.usuarios_ID = u.ID
                JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
                JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
                WHERE r.solicitou_exclusao = 1
            ");

            if ($sql->rowCount() > 0) {
                while ($reserva = $sql->fetch()) {
                    echo "<tr>";
                    echo "<td>#{$reserva['ID_Reserva']}</td>";
                    echo "<td>{$reserva['hospede']}</td>";
                    echo "<td>{$reserva['Checkin']}</td>";
                    echo "<td>{$reserva['Checkout']}</td>";
                    echo "<td>{$reserva['categoria']} (ID {$reserva['ID_Quarto']})</td>";
                    echo "<td>{$reserva['Data_Solicitacao_Exclusao']}</td>";
                    echo "<td>
                        <a href='/HOTEL/actions/excluir_reserva.php?id={$reserva['ID_Reserva']}' class='btn btn-danger btn-sm'>Aprovar</a>
                        <a href='negar_exclusao.php?id={$reserva['ID_Reserva']}' class='btn btn-secondary btn-sm'>Negar</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhuma solicitação encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


