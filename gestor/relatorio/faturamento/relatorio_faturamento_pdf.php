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
/* public/css/report_styles.css */

body {
    font-family: "Helvetica", Arial, sans-serif;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 20px;
}

.header {
    text-align: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 3px solid #FB4D46;
}

.header h1 {
    color: #1A1A2E;
    font-size: 26px;
    margin-bottom: 8px;
    font-weight: 700;
}

.header .filters,
.header .periodo { /* Adicionado .periodo para o relatório de reservas */
    color: #555;
    font-size: 14px;
    margin-top: 10px;
    background-color: #FFE9E9;
    padding: 8px 15px;
    border-radius: 5px;
    display: inline-block;
}

.header .date,
.header .data-emissao { /* Adicionado .data-emissao para o relatório de reservas */
    color: #666;
    font-size: 13px;
    margin-top: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    font-size: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

table thead th {
    background-color: #1A1A2E;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
}

table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

table tbody tr:nth-child(even) {
    background-color: #FFF5F5;
}

table tbody tr:hover {
    background-color: #FFE9E9;
}

.profile {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.profile.gestor {
    background-color: #E6F7E6;
    color: #28A745;
}

.profile.hospede {
    background-color: #E6F0F7;
    color: #1A73E8;
}

.footer {
    margin-top: 30px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    font-size: 11px;
    color: #777;
    text-align: center;
}

.text-center {
    text-align: center;
}

.nowrap {
    white-space: nowrap;
}

/* Estilos específicos para o relatório de Faturamento, se necessário */
.filter-info {
    font-size: 14px;
    margin-bottom: 15px;
    text-align: center;
    color: #444;
}

.total-geral {
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #F8F8F8;
    border: 1px solid #eee;
    text-align: right;
    font-size: 14px;
    font-weight: 700;
    color: #1A1A2E;
}

.money {
    text-align: right;
    font-weight: 600;
}

.badge { /* Para o status no relatório de Reservas */
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
    background-color: #6c757d; /* Cinza padrão para status desconhecido */
}

.badge-primary {
    background-color: #007bff; /* Cor de exemplo para status primário */
}
/* Adicione outras cores de badge conforme necessário, por exemplo, para sucesso, perigo */
</style>';

$html .= '<div class="header">
            <h1>RELATÓRIO DE FATURAMENTO MENSAL</h1>
          </div>';

// Exibir filtro aplicado
$html .= '<div class="filter-info">
            <strong>Forma de Pagamento:</strong> ' . 
            (!empty($formaPagamento) ? htmlspecialchars($formaPagamento) : 'Todas') . '<br>
            <strong>Emitido em:</strong> ' . date('d/m/Y') . '
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
           Rodeo Hotel
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