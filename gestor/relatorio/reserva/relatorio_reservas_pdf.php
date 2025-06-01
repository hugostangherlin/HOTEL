<?php
require_once '../../../vendor/autoload.php';
require_once '../../../config/config.php';

$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

// Montar consulta
$sql = "SELECT r.*, u.Nome AS NomeHospede, q.ID_Quarto
        FROM reserva r
        INNER JOIN usuarios u ON r.usuarios_ID = u.ID
        INNER JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
        WHERE 1";

$params = [];

if (!empty($dataInicio)) {
    $sql .= " AND r.Checkin >= :data_inicio";
    $params[':data_inicio'] = $dataInicio;
}
if (!empty($dataFim)) {
    $sql .= " AND r.Checkout <= :data_fim";
    $params[':data_fim'] = $dataFim;
}

$sql .= " ORDER BY r.Checkin DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mpdf = new \Mpdf\Mpdf();

$html = '
<style>
    body {
        font-family: "Helvetica", Arial, sans-serif;
        color: #333;
        line-height: 1.5;
    }
    
    .header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #FB4D46;
    }
    
    .header h1 {
        color: #1A1A2E;
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    .header .periodo {
        color: #666;
        font-size: 14px;
        margin-top: 10px;
    }
    
    .header .data-emissao {
        color: #666;
        font-size: 12px;
        margin-top: 5px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 12px;
    }
    
    table thead th {
        background-color: #1A1A2E;
        color: white;
        padding: 10px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    table tbody td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    
    table tbody tr:nth-child(even) {
        background-color: #FFE9E9;
    }
    
    .footer {
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
        font-size: 10px;
        color: #666;
        text-align: center;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-right {
        text-align: right;
    }
    
    .badge {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: bold;
    }
    
    .badge-primary {
        background-color: #FB4D46;
        color: white;
    }
</style>

<div class="header">
    <h1>Relatório de Reservas</h1>
    <div class="periodo">';
    
// Adiciona período do relatório se filtrado por data
if (!empty($dataInicio) || !empty($dataFim)) {
    $html .= 'Período: ';
    if (!empty($dataInicio)) {
        $html .= 'De ' . date('d/m/Y', strtotime($dataInicio)) . ' ';
    }
    if (!empty($dataFim)) {
        $html .= 'Até ' . date('d/m/Y', strtotime($dataFim));
    }
}

$html .= '</div>
    <div class="data-emissao">Emitido em: ' . date('d/m/Y H:i') . '</div>
</div>

<table>
    <thead>
        <tr>
            <th>ID Reserva</th>
            <th>Hóspede</th>
            <th>Quarto</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

foreach ($reservas as $reserva) {
    // Formata datas para padrão brasileiro
    $checkin = date('d/m/Y H:i', strtotime($reserva['Checkin']));
    $checkout = date('d/m/Y H:i', strtotime($reserva['Checkout']));
    
    // Determina status (exemplo básico)
    $status = 'Ativa';
    $statusClass = 'badge-primary';
    
    $html .= "<tr>
                <td>{$reserva['ID_Reserva']}</td>
                <td>{$reserva['NomeHospede']}</td>
                <td>{$reserva['ID_Quarto']}</td>
                <td>{$checkin}</td>
                <td>{$checkout}</td>
                <td><span class='badge {$statusClass}'>{$status}</span></td>
              </tr>";
}

$html .= '</tbody>
</table>

<div class="footer">
    Sistema de Gestão Hoteleira | Relatório gerado automaticamente
</div>';

$mpdf->WriteHTML($html);

$filename = 'relatorio_reservas_' . date('Ymd_His') . '.pdf';
$folder = '../../../relatorios/reservas/';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
$filepath = $folder . $filename;

// Salvar no servidor
$mpdf->Output($filepath, 'F');

// Registrar no banco
$tipoRelatorio = 'Reservas';
$dataRelatorio = date('Y-m-d H:i:s');

$descricao = "Relatório de reservas";
if (!empty($dataInicio)) {
    $descricao .= " a partir de {$dataInicio}";
}
if (!empty($dataFim)) {
    $descricao .= " até {$dataFim}";
}

$insertSql = "INSERT INTO relatorio (Data_Relatorio, Tipo_Relatorio, Descricao, Arquivo) VALUES (:data, :tipo, :descricao, :arquivo)";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    ':data' => $dataRelatorio,
    ':tipo' => $tipoRelatorio,
    ':descricao' => $descricao,
    ':arquivo' => $filename
]);

// Enviar PDF para o navegador abrir
$mpdf->Output($filename, 'I');
exit;