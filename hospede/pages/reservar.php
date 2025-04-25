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
$usuario_id = $_SESSION['usuario']['id']; // ou ID_Usuario, conforme o nome da coluna na sua sessão

// Verifica se o quarto ainda está disponível nas datas
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

// Inserir na tabela de reservas
$stmt = $pdo->prepare("
    INSERT INTO reserva (Checkin, Checkout, usuarios_ID, Quarto_ID_Quarto)
    VALUES (:checkin, :checkout, :usuario_id, :quarto_id)
");
$stmt->execute([
    ':checkin' => $checkin,
    ':checkout' => $checkout,
    ':usuario_id' => $usuario_id,
    ':quarto_id' => $quarto_id
]);


// Atualizar status do quarto para Ocupado
$stmt = $pdo->prepare("UPDATE quarto SET Status = 'Ocupado' WHERE ID_Quarto = :quarto_id");
$stmt->execute([':quarto_id' => $quarto_id]);

echo "<h3>Reserva realizada com sucesso!</h3>";
echo "<p>Check-in: $checkin<br>Check-out: $checkout</p>";
echo "<a href='pag_hospede.php'>Voltar para a página inicial</a>";
?>
