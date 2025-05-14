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
    $usuario_id = $_SESSION['usuario']['ID'];
    $quarto_id = $_POST['quarto_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $forma_pagamento = $_POST['forma_pagamento'];

    if (empty($quarto_id) || empty($checkin) || empty($checkout) || empty($forma_pagamento)) {
        die("Todos os campos são obrigatórios.");
    }

    // Verificar preço da diária e calcular valor total
    $stmt = $pdo->prepare("SELECT Preco_diaria FROM quarto WHERE ID_Quarto = :id");
    $stmt->execute([':id' => $quarto_id]);
    $quarto = $stmt->fetch();

    if (!$quarto) {
        die("Quarto não encontrado.");
    }

    $inicio = new DateTime($checkin);
    $fim = new DateTime($checkout);
    $intervalo = $inicio->diff($fim)->days;

    if ($intervalo <= 0) {
        die("Datas inválidas.");
    }

    $valor_total = $quarto['Preco_diaria'] * $intervalo;

    // Verificar se o quarto está disponível no período
    $stmt = $pdo->prepare("SELECT * FROM reserva 
        WHERE Quarto_ID_Quarto = :quarto_id 
        AND (Checkin < :checkout AND Checkout > :checkin)");

    $stmt->execute([
        ':quarto_id' => $quarto_id,
        ':checkin' => $checkin,
        ':checkout' => $checkout
    ]);

    if ($stmt->rowCount() > 0) {
        die("O quarto já está reservado nesse período.");
    }

    // Inserir reserva
    $stmt = $pdo->prepare("INSERT INTO reserva (Usuarios_ID, Quarto_ID_Quarto, Checkin, Checkout) 
                           VALUES (:usuario_id, :quarto_id, :checkin, :checkout)");
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':quarto_id' => $quarto_id,
        ':checkin' => $checkin,
        ':checkout' => $checkout
    ]);

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

    // Atualizar status do quarto
    $stmt = $pdo->prepare("UPDATE quarto SET Status = 'Ocupado' WHERE ID_Quarto = :id");
    $stmt->execute([':id' => $quarto_id]);

    header("Location: ../hospede/pages/minhas_reservas.php");
    exit();
}
?>
