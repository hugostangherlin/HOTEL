
<?php
require_once '../../config/config.php';
session_start();

// Verifica se o gestor está logado (ajuste o tipo conforme seu sistema)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: /HOTEL/gestor/login.php");
    exit;
}

// Lida com ações de confirmação ou rejeição
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'confirmar') {
        // Deleta o usuário
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE ID = ?");
        $stmt->execute([$id]);

        $_SESSION['mensagem'] = "Usuário excluído com sucesso.";
    } elseif ($action === 'rejeitar') {
        // Atualiza para 0 (remoção rejeitada)
        $stmt = $pdo->prepare("UPDATE usuarios SET solicitou_exclusao = 0 WHERE ID = ?");
        $stmt->execute([$id]);

        $_SESSION['mensagem'] = "Solicitação rejeitada.";
    }

    header("Location: solicitacoes_exclusao.php");
    exit;
}

// Busca usuários que solicitaram exclusão (valor = 1)
$stmt = $pdo->query("SELECT ID, Nome, Email FROM usuarios WHERE solicitou_exclusao = 1");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitações de Exclusão</title>
    <link rel="stylesheet" href="../../assets/css/estilo.css"> <!-- se tiver um CSS -->
</head>
<body>
    <h2>Solicitações de Exclusão de Conta</h2>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <p><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></p>
    <?php endif; ?>

    <?php if (count($usuarios) === 0): ?>
        <p>Nenhum usuário solicitou exclusão de conta.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['ID']; ?></td>
                        <td><?= htmlspecialchars($usuario['Nome']); ?></td>
                        <td><?= htmlspecialchars($usuario['Email']); ?></td>
                        <td>
                     <a href="?id=<?= $usuario['ID']; ?>&action=confirmar" onclick="return confirm('Deseja realmente excluir este usuário?')">Confirmar</a> |
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

