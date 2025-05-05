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
    <title>Solicitações de Exclusão</title>
</head>
<body>
    <h2>Solicitações de Exclusão</h2>

    <?php if (empty($usuarios)): ?>
        <p>Nenhuma solicitação.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr><th>ID</th><th>Nome</th><th>Email</th><th>Data da Solicitação</th><th>Ações</th></tr>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['ID'] ?></td>
                    <td><?= htmlspecialchars($u['Nome']) ?></td>
                    <td><?= htmlspecialchars($u['Email']) ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($u['Data_Solicitacao_Exclusao'])) ?></td> <!-- Exibe a data da solicitação -->
                    <td>
                        <a href="/HOTEL/actions/excluir_usuario.php?id=<?= $u['ID'] ?>&action=confirmar" onclick="return confirm('Excluir permanentemente?')">Confirmar</a> |
                        <a href="?id=<?= $u['ID'] ?>&action=rejeitar" onclick="return confirm('Rejeitar solicitação?')">Rejeitar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
