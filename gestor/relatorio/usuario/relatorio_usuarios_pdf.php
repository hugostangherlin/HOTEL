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

$html = "<h2 style='text-align: center;'>Relatório de Usuários</h2>";
$html .= "<table border='1' width='100%' style='border-collapse: collapse; font-size: 12px;'>";
$html .= "<thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Data de Nascimento</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Perfil</th>
            </tr>
          </thead><tbody>";

foreach ($usuarios as $usuario) {
    $nomeUsuario = $usuario['Nome'] ?? '';
    $email = $usuario['Email'] ?? '';
    $dataNascimento = $usuario['Data_Nascimento'] ?? '';
    $telefone = $usuario['Telefone'] ?? '';
    $endereco = $usuario['Endereco'] ?? '';
    $perfilId = $usuario['Perfil_ID_Perfil'] ?? '';

    $perfilNome = ($perfilId == 1) ? 'Gestor' : 'Hóspede';

    $html .= "<tr>
                <td>{$nomeUsuario}</td>
                <td>{$email}</td>
                <td>{$dataNascimento}</td>
                <td>{$telefone}</td>
                <td>{$endereco}</td>
                <td>{$perfilNome}</td>
              </tr>";
}

$html .= "</tbody></table>";

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
