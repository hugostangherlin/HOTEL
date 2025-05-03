<?php
// Conexão com o banco de dados
require_once '../../config/config.php';

// Verificar se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id_quarto = $_GET['id'];

    // Preparar a consulta para excluir o quarto
    $query = "DELETE FROM quarto WHERE ID_Quarto = :id_quarto";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_quarto', $id_quarto);

    if ($stmt->execute()) {
        echo "Quarto excluído com sucesso!";
        // Redirecionar de volta para a lista de quartos ou para outra página
        header('Location: ../quartos/listar.php');
        exit;
    } else {
        echo "Erro ao excluir o quarto.";
    }
} else {
    echo "ID do quarto não especificado.";
}
?>
