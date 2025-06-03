<?php
require '../config/config.php';

if (!isset($_GET['id'])) {
    die("ID não fornecido.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $pdo->beginTransaction();

        // 1. Buscar os quartos reservados pelo usuário para atualizar status
        $sqlReservas = "SELECT Quarto_ID_Quarto FROM reserva WHERE usuarios_ID = :id_usuario";
        $stmtReservas = $pdo->prepare($sqlReservas);
        $stmtReservas->bindValue(':id_usuario', $id, PDO::PARAM_INT);
        $stmtReservas->execute();
        $reservas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

        // 2. Atualizar status dos quartos para 'Disponível'
        $updateQuarto = $pdo->prepare("UPDATE quarto SET Status = 'Disponível' WHERE ID_Quarto = :id_quarto");
        foreach ($reservas as $reserva) {
            $id_quarto = $reserva['Quarto_ID_Quarto'];
            $updateQuarto->bindValue(':id_quarto', $id_quarto, PDO::PARAM_INT);
            $updateQuarto->execute();
        }

        // 3. Deletar avaliações do usuário
        $stmtDelAval = $pdo->prepare("DELETE FROM avaliacao WHERE usuarios_ID = :id_usuario");
        $stmtDelAval->bindValue(':id_usuario', $id, PDO::PARAM_INT);
        $stmtDelAval->execute();

        // 4. Deletar pagamentos do usuário
        $stmtDelPag = $pdo->prepare("DELETE FROM pagamentos WHERE ID_Usuarios = :id_usuario");
        $stmtDelPag->bindValue(':id_usuario', $id, PDO::PARAM_INT);
        $stmtDelPag->execute();

        // 5. Deletar reservas do usuário
        $stmtDelRes = $pdo->prepare("DELETE FROM reserva WHERE usuarios_ID = :id_usuario");
        $stmtDelRes->bindValue(':id_usuario', $id, PDO::PARAM_INT);
        $stmtDelRes->execute();

        // 6. Deletar o usuário (perfil hóspede = 2)
        $stmtDelUser = $pdo->prepare("DELETE FROM usuarios WHERE ID = :id AND Perfil_ID_Perfil = 2");
        $stmtDelUser->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtDelUser->execute();

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao excluir usuário: " . $e->getMessage());
    }
}

header("Location: /HOTEL/gestor/telas/solicitacao_reserva.php");
exit;
