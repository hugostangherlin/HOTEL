<?php 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $quarto_id = $_GET['id'];
    $checkin = $_GET['checkin'];
    $checkout = $_GET['checkout'];
    $preco_diaria = $_GET['preco_diaria'];

    // Verificar se o quarto está disponível
    $stmt = $pdo->prepare("SELECT * FROM quarto WHERE ID_Quarto = :id AND Status = 'Disponível'");
    $stmt->bindParam(':id', $quarto_id, PDO::PARAM_INT);
    $stmt->execute();
    $quarto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quarto) {
        // Inserir dados da reserva na tabela reserva
        $stmt = $pdo->prepare("INSERT INTO reserva (Checkin, Checkout, Quarto_ID_Quarto, usuarios_ID) VALUES (:checkin, :checkout, :quarto_id, :usuario_id)");
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
        $stmt->bindParam(':quarto_id', $quarto_id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuario']['id']); // Usuário logado
        $stmt->execute();

        // Registrar pagamento
        $reserva_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO pagamentos (ID_Reserva, ID_Usuarios, Valor, Forma_Pagamento, Status, Data_Pagamento) VALUES (:reserva_id, :usuario_id, :valor, 'Cartão', 'Pendente', NOW())");
        $stmt->bindParam(':reserva_id', $reserva_id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuario']['id']);
        $stmt->bindParam(':valor', $preco_diaria);
        $stmt->execute();

        // Redirecionar para página de pagamento
        header("Location: /HOTEL/hospede/pages/pagamento.php?id=$reserva_id");
        exit;
    } else {
        echo "Quarto não disponível.";
        exit;
    }
}

?>
