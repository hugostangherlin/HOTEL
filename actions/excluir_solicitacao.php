<?php
require '../config/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idReserva = (int)$_GET['id'];

    try {
        // Inicia transação para segurança
        $pdo->beginTransaction();

        // 1. Pega o ID do quarto da reserva
        $stmtQuarto = $pdo->prepare("SELECT Quarto_ID_Quarto FROM reserva WHERE ID_Reserva = :id");
        $stmtQuarto->bindParam(':id', $idReserva, PDO::PARAM_INT);
        $stmtQuarto->execute();
        $quarto = $stmtQuarto->fetch(PDO::FETCH_ASSOC);

        if ($quarto) {
            $idQuarto = $quarto['Quarto_ID_Quarto'];

            // 2. Deleta o pagamento vinculado, se existir
            $stmtPag = $pdo->prepare("DELETE FROM pagamentos WHERE ID_Reserva = :id");
            $stmtPag->bindParam(':id', $idReserva, PDO::PARAM_INT);
            $stmtPag->execute();

            // 3. Deleta a reserva
            $stmtReserva = $pdo->prepare("DELETE FROM reserva WHERE ID_Reserva = :id");
            $stmtReserva->bindParam(':id', $idReserva, PDO::PARAM_INT);
            $stmtReserva->execute();

            // 4. Atualiza o status do quarto para 'Disponível'
            $stmtUpdate = $pdo->prepare("UPDATE quarto SET Status = 'Disponível' WHERE ID_Quarto = :id_quarto");
            $stmtUpdate->bindParam(':id_quarto', $idQuarto, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Confirma transação
            $pdo->commit();

            // Redireciona após excluir
            header("Location: /HOTEL/gestor/telas/solicitacao_reserva.php");
            exit();
        } else {
            // Se não encontrou a reserva
            $pdo->rollBack();
            echo "Reserva não encontrada.";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erro ao excluir: " . $e->getMessage();
    }
} else {
    echo "ID inválido.";
}
