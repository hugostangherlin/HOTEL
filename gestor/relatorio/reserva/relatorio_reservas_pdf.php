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

$html = "<h2 style='text-align: center;'>Relatório de Reservas</h2>";
$html .= "<table border='1' width='100%' style='border-collapse: collapse; font-size: 12px;'>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Hóspede</th>
                    <th>Quarto</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                </tr>
            </thead>
            <tbody>";

foreach ($reservas as $reserva) {
    $html .= "<tr>
                <td>{$reserva['ID_Reserva']}</td>
                <td>{$reserva['NomeHospede']}</td>
                <td>{$reserva['Quarto_ID_Quarto']}</td>
                <td>{$reserva['Checkin']}</td>
                <td>{$reserva['Checkout']}</td>
              </tr>";
}

$html .= "</tbody></table>";

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
