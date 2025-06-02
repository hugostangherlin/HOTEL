<?php
// Conexão com o banco de dados
require '../config/config.php';

// Obtém o ID da reserva a ser excluída
$id_reserva = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id_reserva) {
    // Primeiro, busca o ID do quarto associado à reserva
    $stmt = $pdo->prepare("SELECT Quarto_ID_Quarto FROM reserva WHERE ID_Reserva = :id_reserva");
    $stmt->bindValue(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt->execute();
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reserva) {
        $id_quarto = $reserva['Quarto_ID_Quarto'];

        // Atualiza o status do quarto para 'Disponível'
        $updateQuarto = $pdo->prepare("UPDATE quarto SET Status = 'Disponível' WHERE ID_Quarto = :id_quarto");
        $updateQuarto->bindValue(':id_quarto', $id_quarto, PDO::PARAM_INT);
        $updateQuarto->execute();

        // Agora deleta a reserva
        $deleteReserva = $pdo->prepare("DELETE FROM reserva WHERE ID_Reserva = :id_reserva");
        $deleteReserva->bindValue(':id_reserva', $id_reserva, PDO::PARAM_INT);
        $deleteReserva->execute();
    }
}

// Redireciona de volta para a página de reservas
header("Location: /HOTEL/gestor/telas/solicitacao_reserva.php");
exit;
