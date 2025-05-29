<?php 
session_start();
$host = "localhost";
$usuario = "root";
$senha = "";
$nome_banco = "rodeo_hotel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nome_banco;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

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

    // Verifica se o quarto está disponível nas datas
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM reserva WHERE Quarto_ID_Quarto = ? AND (
        (Checkin <= ? AND Checkout > ?) OR
        (Checkin < ? AND Checkout >= ?) OR
        (Checkin >= ? AND Checkout <= ?)
    )");

    $stmt->execute([$quarto_id, $checkin, $checkin, $checkout, $checkout, $checkin, $checkout]);
    $reserva_existente = $stmt->fetch();

    if ($reserva_existente['total'] > 0) {
        echo "Este quarto já está reservado para o período selecionado.";
        exit();
    }

    // Inserir reserva
    $stmt = $pdo->prepare("INSERT INTO reserva (Checkin, Checkout, Quarto_ID_Quarto, usuarios_ID) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$checkin, $checkout, $quarto_id, $usuario_id]);

    $reserva_id = $pdo->lastInsertId();

    // Inserir pagamento
    $stmt = $pdo->prepare("INSERT INTO pagamentos (ID_Reserva, ID_Usuarios, Valor, Forma_Pagamento, Status, Data_Pagamento) 
                           VALUES (?, ?, ?, ?, 'Pago', NOW())");
    $stmt->execute([$reserva_id, $usuario_id, $valor_total, $forma_pagamento]);

    // Atualizar status do quarto
    $stmt = $pdo->prepare("UPDATE quarto SET Status = 'Ocupado' WHERE ID_Quarto = :id");
    $stmt->execute([':id' => $quarto_id]);

    header("Location: ../hospede/pages/minhas_reservas.php");
    exit();
}
?>
