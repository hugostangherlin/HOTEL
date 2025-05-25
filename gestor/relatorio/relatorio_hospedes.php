<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';


use Mpdf\Mpdf;

try {
    $stmt = $pdo->prepare("SELECT ID, Nome, Email FROM usuarios WHERE Perfil_ID_Perfil = 2");
    $stmt->execute();
    $hospedes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$hospedes) {
        die("Nenhum hóspede encontrado.");
    }

    // HTML do relatório
    $html = '<h1>Relatório de Hóspedes</h1>';
    $html .= '<table border="1" width="100%" style="border-collapse: collapse;">';
    $html .= '<tr><th>ID</th><th>Nome</th><th>Email</th></tr>';

    foreach ($hospedes as $hospede) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($hospede['ID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($hospede['Nome']) . '</td>';
        $html .= '<td>' . htmlspecialchars($hospede['Email']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Gera o PDF
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('relatorio_hospedes.pdf', 'I'); // 'I' exibe no navegador

} catch (Exception $e) {
    echo "Erro ao gerar relatório: " . $e->getMessage();
}
