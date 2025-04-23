<?php
// Configurações e includes
include './config/config.php';
include __DIR__ . '/vendor/autoload.php';

session_start();
date_default_timezone_set('America/Sao_Paulo'); 

// Verificação de classe MPDF
if (!class_exists('\\Mpdf\\Mpdf')) {
    die("Erro: Bibliotecas do mPDF não carregadas! Verifique o caminho do autoload.php");
}

// Verifica autenticação
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Tipos permitidos
$tipos_permitidos = ['usuarios', 'clientes', 'produtos'];
$tipo = $_GET['tipo'] ?? '';

if (!in_array($tipo, $tipos_permitidos)) {
    die('Tipo de relatório inválido!');
}

// Cria diretório temporário se não existir
if (!is_dir(__DIR__ . '/tmp')) {
    if (!mkdir(__DIR__ . '/tmp', 0777, true)) {
        die('Não foi possível criar diretório temporário');
    }
}

// Configuração do mPDF
try {
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 25,
        'tempDir' => __DIR__ . '/tmp',
        'default_font' => 'arial' // Adicionando fonte padrão
    ]);

    global $pdo;
    
    switch ($tipo) {
        case 'usuarios':
            $sql = "SELECT id, nome, email, perfil, 
                    DATE_FORMAT(data_cadastro, '%d/%m/%Y %H:%i') as cadastro 
                    FROM usuarios";
            break;
            
        case 'clientes':
            $sql = "SELECT id, nome, email, telefone, endereco, 
                    DATE_FORMAT(data_cadastro, '%d/%m/%Y %H:%i') as cadastro 
                    FROM clientes";
            break;
            
        case 'produtos':
            $sql = "SELECT id, nome, 
                    FORMAT(preco, 2, 'pt_BR') as preco, 
                    estoque, 
                    DATE_FORMAT(data_cadastro, '%d/%m/%Y %H:%i') as cadastro 
                    FROM produtos";
            break;
    }

    $stmt = $pdo->query($sql);
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($dados)) {
        die('Nenhum dado encontrado para gerar o relatório');
    }

    $html = '
    <style>
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0066cc; padding-bottom: 15px; }
        .logo { width: 150px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th { background-color: #0066cc; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; }
        .titulo { color: #0066cc; margin-bottom: 5px; }
        .info-geracao { color: #666; margin-top: 10px; }
    </style>
    
    <div class="header">
        <img src="'.__DIR__.'/assets/img/logo.png" class="logo">
        <h1 class="titulo">Relatório de '.ucfirst($tipo).'</h1>
        <div class="info-geracao">
            <small>Gerado em: '.date('d/m/Y H:i:s').'</small><br>
            <small>Por: '.htmlspecialchars($_SESSION['usuario']['nome']).'</small>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>';
    
    foreach (array_keys($dados[0]) as $chave) {
        $html .= '<th>'.ucwords(str_replace('_', ' ', $chave)).'</th>';
    }
    $html .= '</tr>
        </thead>
        <tbody>';
    
    foreach ($dados as $linha) {
        $html .= '<tr>';
        foreach ($linha as $valor) {
            $html .= '<td>'.htmlspecialchars($valor).'</td>';
        }
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    
    $mpdf->WriteHTML($html);
    
    // Força download com nome personalizado
    $mpdf->Output('Relatorio_'.$tipo.'_'.date('Ymd').'.pdf', 'D');
    
} catch (Exception $e) {
    die("Erro ao gerar PDF: ".$e->getMessage());
}