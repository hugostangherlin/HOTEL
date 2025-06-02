<?php
require_once '../../config/config.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: /HOTEL/gestor/login.php");
    exit;
}

// Ações: confirmar (deletar) ou rejeitar (cancelar solicitação)
if (isset($_GET['id'], $_GET['action'])) {
    $id = (int) $_GET['id'];
    if ($_GET['action'] === 'confirmar') {
        $pdo->prepare("DELETE FROM usuarios WHERE ID = ?")->execute([$id]);
    } elseif ($_GET['action'] === 'rejeitar') {
        $pdo->prepare("UPDATE usuarios SET solicitou_exclusao = 0 WHERE ID = ?")->execute([$id]);
    }
    header("Location: solicitacoes_exclusao.php");
    exit;
}

// Busca usuários que pediram exclusão
$usuarios = $pdo->query("SELECT ID, Nome, Email, Data_Solicitacao_Exclusao FROM usuarios WHERE solicitou_exclusao = 1")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Exclusão - Painel Administrativo</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --text-color: #333;
            --text-light: #7f8c8d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: #f5f6fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
            font-weight: 600;
        }
        
        .no-requests {
            text-align: center;
            padding: 30px;
            color: var(--text-light);
            font-size: 18px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-confirm {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-confirm:hover {
            background-color: #c0392b;
        }
        
        .btn-reject {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #d35400;
        }
        
        .timestamp {
            color: var(--text-light);
            font-size: 13px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #f1c40f;
            color: #fff;
        }
        
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            
            .container {
                padding: 15px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-minus"></i> Solicitações de Exclusão de Conta</h2>

        <?php if (empty($usuarios)): ?>
            <div class="no-requests">
                <p>Nenhuma solicitação de exclusão pendente.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data da Solicitação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['ID'] ?></td>
                            <td><?= htmlspecialchars($u['Nome']) ?></td>
                            <td><?= htmlspecialchars($u['Email']) ?></td>
                            <td class="timestamp"><?= date('d/m/Y H:i:s', strtotime($u['Data_Solicitacao_Exclusao'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/HOTEL/actions/excluir_usuario.php?id=<?= $u['ID'] ?>&action=confirmar" 
                                       class="btn btn-confirm" 
                                       onclick="return confirm('Tem certeza que deseja excluir permanentemente este usuário?')">
                                        Confirmar Exclusão
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>