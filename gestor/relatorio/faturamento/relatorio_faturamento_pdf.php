<?php
require_once '../../../vendor/autoload.php';
require_once '../../../config/config.php';

// Filtros (exemplo: status do pagamento e forma de pagamento)
$status = $_GET['status'] ?? '';
$formaPagamento = $_GET['forma_pagamento'] ?? '';

// Monta consulta SQL com filtros opcionais
$sql = "SELECT p.ID_Pagamento, p.Valor, p.Forma_Pagamento, p.Status, p.Data_Pagamento, r.Checkin, r.Checkout, u.Nome 
        FROM pagamentos p
        JOIN reserva r ON p.ID_Reserva = r.ID_Reserva
        JOIN usuarios u ON p.ID_Usuarios = u.ID
        WHERE 1=1";
$params = [];

if (!empty($status)) {
    $sql .= " AND p.Status = :status";
    $params[':status'] = $status;
}

if (!empty($formaPagamento)) {
    $sql .= " AND p.Forma_Pagamento = :forma";
    $params[':forma'] = $formaPagamento;
}

$sql .= " ORDER BY p.Data_Pagamento DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$faturamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicia mPDF
$mpdf = new \Mpdf\Mpdf();

$html = "<h2 style='text-align: center;'>Relatório de Faturamento</h2>";
$html .= "<table border='1' width='100%' style='border-collapse: collapse; font-size: 12px;'>";
$html .= "<thead>
            <tr>
                <th>ID Pagamento</th>
                <th>Nome do Hóspede</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Valor</th>
                <th>Forma de Pagamento</th>
                <th>Status</th>
                <th>Data do Pagamento</th>
            </tr>
          </thead><tbody>";

foreach ($faturamentos as $fat) {
    $html .= "<tr>
                <td>" . htmlspecialchars($fat['ID_Pagamento']) . "</td>
                <td>" . htmlspecialchars($fat['Nome']) . "</td>
                <td>" . htmlspecialchars($fat['Checkin']) . "</td>
                <td>" . htmlspecialchars($fat['Checkout']) . "</td>
                <td>R$ " . number_format($fat['Valor'], 2, ',', '.') . "</td>
                <td>" . htmlspecialchars($fat['Forma_Pagamento']) . "</td>
                <td>" . htmlspecialchars($fat['Status']) . "</td>
                <td>" . htmlspecialchars($fat['Data_Pagamento']) . "</td>
              </tr>";
}

$html .= "</tbody></table>";

// Gera nome do arquivo e pasta para salvar o PDF
$filename = 'relatorio_faturamento_' . date('Ymd_His') . '.pdf';
$folder = '../../../relatorios/faturamento/';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
$filepath = $folder . $filename;

// Salva o PDF no servidor
$mpdf->WriteHTML($html);
$mpdf->Output($filepath, 'F');

// Salva no banco o registro do relatório
$tipoRelatorio = 'Faturamento';
$dataRelatorio = date('Y-m-d H:i:s');

// Monta descrição
$descricao = "Relatório de faturamento";
if (!empty($status)) {
    $descricao .= " - Filtro status: $status.";
}
if (!empty($formaPagamento)) {
    $descricao .= " - Filtro forma pagamento: $formaPagamento.";
}

$insertSql = "INSERT INTO relatorio (Data_Relatorio, Tipo_Relatorio, Descricao, Arquivo) VALUES (:data, :tipo, :descricao, :arquivo)";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    ':data' => $dataRelatorio,
    ':tipo' => $tipoRelatorio,
    ':descricao' => $descricao,
    ':arquivo' => $filename
]);

// Exibe o PDF no navegador (se quiser abrir direto, use 'I', caso contrário remova)
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
readfile($filepath);
exit;
?>
