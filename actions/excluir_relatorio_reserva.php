<?php
require '../config/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Primeiro, pegar o nome do arquivo do relatório para excluir o arquivo físico também
    $sql = "SELECT Arquivo FROM relatorio WHERE ID_Relatorio = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $relatorio = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($relatorio) {
        // Caminho completo do arquivo PDF para exclusão
        $arquivoPath = __DIR__ . "/../../relatorios/reserva/" . $relatorio['Arquivo'];

        // Excluir registro do banco
        $deleteSql = "DELETE FROM relatorio WHERE ID_Relatorio = :id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleted = $deleteStmt->execute([':id' => $id]);

        if ($deleted) {
            // Apaga o arquivo físico se existir
            if (file_exists($arquivoPath)) {
                unlink($arquivoPath);
            }
            // Redireciona de volta para a página do filtro com sucesso
            header("Location: /HOTEL/gestor/relatorio/reserva/filtro_reservas.php");
            exit;
        } else {
            echo "Erro ao excluir o relatório do banco.";
        }
    } else {
        echo "Relatório não encontrado.";
    }
} else {
    echo "ID inválido.";
}
?>
