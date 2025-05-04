<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario']['id'];
    $quarto_id = $_POST['quarto_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $valor_total = $_POST['valor_total'];
    $forma_pagamento = $_POST['forma_pagamento'];

    // Inserir reserva
    $stmt = $pdo->prepare("INSERT INTO reserva (Usuarios_ID, Quarto_ID_Quarto, Checkin, Checkout) 
                           VALUES (:usuario_id, :quarto_id, :checkin, :checkout)");
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':quarto_id' => $quarto_id,
        ':checkin' => $checkin,
        ':checkout' => $checkout
    ]);

    // Obter ID da reserva recém inserida
    $reserva_id = $pdo->lastInsertId();

    // Inserir pagamento
    $stmt = $pdo->prepare("INSERT INTO pagamentos (ID_Reserva, ID_Usuarios, Valor, Forma_Pagamento, Status, Data_Pagamento)
                           VALUES (:reserva_id, :usuario_id, :valor_total, :forma_pagamento, 'Pago', NOW())");
    $stmt->execute([
        ':reserva_id' => $reserva_id,
        ':usuario_id' => $usuario_id,
        ':valor_total' => $valor_total,
        ':forma_pagamento' => $forma_pagamento
    ]);

    echo "<h3>Pagamento confirmado com sucesso!</h3>";
    echo "<p>Reserva Concluída</p>";
    echo "<a href='/HOTEL/hospede/pag_hospede'>Voltar à página inicial</a>";
}
?>
