<?php
// Conexão com o banco de dados (ajuste conforme necessário)
require_once '../../config/config.php';

// Consulta para pegar os detalhes do quarto
$query = "SELECT q.ID_Quarto, q.Status, q.Capacidade, q.Foto, q.Preco_diaria, c.Nome AS Categoria,
                 r.ID_Reserva, r.checkin, r.checkout, u.Nome AS Usuario_Nome, u.Email AS Usuario_Email
          FROM quarto q
          JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
          LEFT JOIN reserva r ON r.Quarto_ID_Quarto = q.ID_Quarto
          LEFT JOIN usuarios u ON r.usuarios_ID = u.ID
          WHERE q.ID_Quarto = :id_quarto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_quarto', $_GET['id']);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificando se há resultados
if ($row) {
    // Exibindo informações do quarto
    echo "<h1>Detalhes do Quarto</h1>";
    echo "<p>Código do Quarto: " . $row['ID_Quarto'] . "</p>";
    
    // Verificando o status do quarto
    $status = "Disponível"; // Supondo que o quarto esteja disponível por padrão
    if ($row['ID_Reserva'] !== null) { 
        $status = "Ocupado"; // Se houver uma reserva associada, o quarto estará ocupado
    }

    echo "<p>Status: " . $status . "</p>";
    echo "<p>Capacidade: " . $row['Capacidade'] . " pessoas</p>";
    echo "<p>Categoria: " . $row['Categoria'] . "</p>";
    echo "<p>Preço por Diária: R$ " . number_format($row['Preco_diaria'], 2, ',', '.') . "</p>";
    
    // Exibindo informações de reserva
    echo "<h2>Informações de Reserva</h2>";
    
    // Formatando as datas de check-in e check-out
    $checkin = isset($row['checkin']) ? date('d \d\e F \d\e Y', strtotime($row['checkin'])) : 'Não definido';
    $checkout = isset($row['checkout']) ? date('d \d\e F \d\e Y', strtotime($row['checkout'])) : 'Não definido';
    
    echo "<p>ID da Reserva: " . $row['ID_Reserva'] . "</p>";
    echo "<p>Data de Check-in: " . $checkin . "</p>";
    echo "<p>Data de Check-out: " . $checkout . "</p>";
    
    // Exibindo informações do usuário
    echo "<h2>Informações do Usuário</h2>";
    echo "<p>Nome do Usuário: " . $row['Usuario_Nome'] . "</p>";
    echo "<p>E-mail do Usuário: " . $row['Usuario_Email'] . "</p>";
} else {
    echo "Nenhum detalhe encontrado para este quarto.";
}
?>
