<?php
require_once '../../../vendor/autoload.php';
require_once '../../../config/config.php';

$nome = $_GET['nome'] ?? '';
$perfil = $_GET['perfil'] ?? '';

// Consulta com filtro
$sql = "SELECT * FROM usuarios WHERE 1";
$params = [];

if (!empty($nome)) {
    $sql .= " AND Nome LIKE :nome";
    $params[':nome'] = '%' . $nome . '%';
}
if (!empty($perfil)) {
    $sql .= " AND Perfil_ID_Perfil = :perfil";
    $params[':perfil'] = $perfil;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicia mPDF
$mpdf = new \Mpdf\Mpdf();

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
    
    .header .filters {
        color: #555;
        font-size: 14px;
        margin-top: 10px;
        background-color: #FFE9E9;
        padding: 8px 15px;
        border-radius: 5px;
        display: inline-block;
    }
    
    .header .date {
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
</style>

<div class="header">
    <h1>RELATÓRIO DE USUÁRIOS</h1>';
    
// Adiciona filtros aplicados
if (!empty($nome) || !empty($perfil)) {
    $html .= '<div class="filters">Filtros aplicados: ';
    $filters = [];
    if (!empty($nome)) {
        $filters[] = 'Nome: ' . htmlspecialchars($nome);
    }
    if (!empty($perfil)) {
        $filters[] = 'Perfil: ' . ($perfil == 1 ? 'Gestor' : 'Hóspede');
    }
    $html .= implode(' | ', $filters) . '</div>';
}

$html .= '<div class="date">Emitido em: ' . date('d/m/Y') . '</div>
</div>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data Nascimento</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Perfil</th>
        </tr>
    </thead>
    <tbody>';

foreach ($usuarios as $usuario) {
    $nomeUsuario = htmlspecialchars($usuario['Nome'] ?? '');
    $email = htmlspecialchars($usuario['Email'] ?? '');
    $dataNascimento = !empty($usuario['Data_Nascimento']) ? date('d/m/Y', strtotime($usuario['Data_Nascimento'])) : '';
    $telefone = htmlspecialchars($usuario['Telefone'] ?? '');
    $endereco = htmlspecialchars($usuario['Endereco'] ?? '');
    $perfilId = $usuario['Perfil_ID_Perfil'] ?? '';
    $perfilNome = ($perfilId == 1) ? 'Gestor' : 'Hóspede';
    $perfilClass = strtolower($perfilNome);

    $html .= "<tr>
                <td>{$nomeUsuario}</td>
                <td>{$email}</td>
                <td class='nowrap'>{$dataNascimento}</td>
                <td>{$telefone}</td>
                <td>{$endereco}</td>
                <td><span class='profile {$perfilClass}'>{$perfilNome}</span></td>
              </tr>";
}

$html .= '</tbody>
</table>

<div class="footer">
    Sistema de Gestão Hoteleira | Relatório gerado automaticamente
</div>';

// Caminho correto para salvar o arquivo PDF
$caminho_pasta = __DIR__ . '/../../../relatorios/usuario';

// Cria a pasta se não existir
if (!is_dir($caminho_pasta)) {
    mkdir($caminho_pasta, 0777, true);
}

$nome_arquivo = 'relatorio_usuarios_' . date('Ymd_His') . '.pdf';
$caminho_arquivo = $caminho_pasta . '/' . $nome_arquivo;

$mpdf->WriteHTML($html);

// Salva o PDF na pasta
$mpdf->Output($caminho_arquivo, \Mpdf\Output\Destination::FILE);

// Registra no banco de dados o relatório emitido
$tipoRelatorio = 'Usuários';
$dataRelatorio = date('Y-m-d H:i:s');

$descricao = "Relatório de usuários";
if (!empty($perfil)) {
    $descricao .= " Filtro por perfil: " . (($perfil == 1) ? 'Gestor' : 'Hóspede') . ".";
}

$insertSql = "INSERT INTO relatorio (Data_Relatorio, Tipo_Relatorio, Descricao, Arquivo) 
              VALUES (:data, :tipo, :descricao, :arquivo)";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    ':data' => $dataRelatorio,
    ':tipo' => $tipoRelatorio,
    ':descricao' => $descricao,
    ':arquivo' => $nome_arquivo
]);

// Exibe o PDF no navegador (inline)
$mpdf->Output('relatorio_usuario.pdf', \Mpdf\Output\Destination::INLINE);
