<?php
require_once '../../../vendor/autoload.php';
require_once '../../../config/config.php';

// Filtro recebido via GET
$formaPagamento = $_GET['forma_pagamento'] ?? '';

// SQL: agrupando por mês/ano
$sql = "SELECT 
            DATE_FORMAT(p.Data_Pagamento, '%m/%Y') AS mes_ano,
            SUM(p.Valor) AS total
        FROM pagamentos p
        WHERE 1=1";

$params = [];

if (!empty($formaPagamento)) {
    $sql .= " AND p.Forma_Pagamento = :forma";
    $params[':forma'] = $formaPagamento;
}

$sql .= " GROUP BY mes_ano ORDER BY STR_TO_DATE(mes_ano, '%m/%Y') ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicia o mPDF
$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'dejavusans'
]);

$html = '<style>
    body {
        font-family: "Helvetica", Arial, sans-serif;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
    }
    
    .header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #FB4D46;
    }
    
    .header h1 {
        color: #1A1A2E;
        font-size: 24px;
        margin-bottom: 5px;
        font-weight: 700;
    }
    
    .filter-info {
        background-color: #FFE9E9;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .filter-info strong {
        color: #1A1A2E;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    table thead th {
        background-color: #1A1A2E;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    table tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    
    table tbody tr:nth-child(even) {
        background-color: #FFF5F5;
    }
    
    .total-geral {
        font-size: 16px;
        font-weight: 600;
        color: #1A1A2E;
        margin: 25px 0;
        padding: 10px;
        background-color: #FFE9E9;
        border-left: 4px solid #FB4D46;
    }
    
    .footer {
        text-align: center;
        font-size: 10px;
        color: #666;
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
    }
    
    .money {
        font-family: "Courier New", monospace;
        font-weight: bold;
    }
</style>';

$html .= '<div class="header">
            <h1>RELATÓRIO DE FATURAMENTO MENSAL</h1>
          </div>';

// Exibir filtro aplicado
$html .= '<div class="filter-info">
            <strong>Forma de Pagamento:</strong> ' . 
            (!empty($formaPagamento) ? htmlspecialchars($formaPagamento) : 'Todas') . '<br>
            <strong>Emitido em:</strong> ' . date('d/m/Y H:i') . '
          </div>';

$html .= '<table>
            <thead>
                <tr>
                    <th>Mês/Ano</th>
                    <th>Total Faturado</th>
                </tr>
            </thead>
            <tbody>';

$totalGeral = 0;

foreach ($resultados as $linha) {
    // Formata mês/ano
    $mesAno = $linha['mes_ano'];
    $total = number_format($linha['total'], 2, ',', '.');
    $totalGeral += $linha['total'];

    // Converte mês numérico para nome
    $partes = explode('/', $mesAno);
    $mesNome = ucfirst(strftime('%B', mktime(0, 0, 0, $partes[0], 1))) . '/' . $partes[1];

    $html .= "<tr>
                <td>$mesNome</td>
                <td class='money'>R$ $total</td>
              </tr>";
}

$html .= '</tbody></table>';

$html .= '<div class="total-geral">Total Geral: R$ ' . number_format($totalGeral, 2, ',', '.') . '</div>';

$html .= '<div class="footer">
            Sistema de Gestão Hoteleira | Relatório gerado automaticamente
          </div>';


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