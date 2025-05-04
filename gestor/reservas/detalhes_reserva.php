<?php
session_start();
require_once '../../config/config.php';

// Verifique se o ID da reserva foi passado corretamente na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID da reserva não informado.";
    exit();
}

$id_reserva = $_GET['id'];

// Consulta para pegar os dados de pagamento
$sql = "SELECT p.ID_Pagamento, p.Forma_Pagamento, p.Status, p.Valor, p.Data_Pagamento, u.Nome
        FROM pagamentos p
        JOIN usuarios u ON p.ID_Usuarios = u.ID
        WHERE p.ID_Reserva = :id_reserva";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_reserva', $id_reserva);
$stmt->execute();
$pagamento = $stmt->fetch();

if (!$pagamento) {
    echo "Pagamento não encontrado para a reserva.";
    exit();
}

// Exibindo os dados
echo "<h3>Detalhes do Pagamento</h3>";
echo "<p><strong>ID do Pagamento:</strong> " . $pagamento['ID_Pagamento'] . "</p>";
echo "<p><strong>Forma de Pagamento:</strong> " . $pagamento['Forma_Pagamento'] . "</p>";
echo "<p><strong>Status:</strong> " . $pagamento['Status'] . "</p>";
echo "<p><strong>Valor:</strong> R$ " . number_format($pagamento['Valor'], 2, ',', '.') . "</p>";
echo "<p><strong>Data do Pagamento:</strong> " . date('d/m/Y H:i:s', strtotime($pagamento['Data_Pagamento'])) . "</p>";
?>
