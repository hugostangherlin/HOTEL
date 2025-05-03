<?php
session_start();

// Verifica se o usuário está logado e é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

require 'config.php';

// Validação dos dados recebidos
if (!isset($_GET['id'], $_GET['checkin'], $_GET['checkout'])) {
    echo "Dados incompletos para reserva.";
    exit();
}

$quarto_id = $_GET['id'];
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];

// Verifica se o quarto está disponível nas datas
$stmt = $pdo->prepare("
    SELECT * FROM reserva
    WHERE Quarto_ID_Quarto = :quarto_id
    AND (:checkin < Checkout AND :checkout > Checkin)
");
$stmt->execute([
    ':quarto_id' => $quarto_id,
    ':checkin' => $checkin,
    ':checkout' => $checkout
]);

if ($stmt->rowCount() > 0) {
    echo "Este quarto já está reservado nessas datas.";
    exit();
}

// Após verificar a disponibilidade e redirecionar para o pagamento, atualize o status
$stmt = $pdo->prepare("UPDATE Quarto SET Status = 'Ocupado' WHERE ID_QUARTO = :quarto_id");
$stmt->execute([ ':quarto_id' => $quarto_id ]);

// Se tudo certo, redireciona para o pagamento
header("Location: pagamento.php?id={$quarto_id}&checkin={$checkin}&checkout={$checkout}");
exit();
