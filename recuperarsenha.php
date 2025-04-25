<?php
require 'config.php';

$mensagem = "";
$etapa = 1;

if (isset($_POST['validar-email'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $etapa = 2;
        } else {
            $mensagem = "E-mail não encontrado.";
        }
    }
}

if (isset($_POST['definir-senha'])) {
    $email = $_POST['email'];
    $novaSenha = $_POST['nova_senha'];

    if (strlen($novaSenha) < 6) {
        $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        $etapa = 2;
    } else {
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([
            ":senha" => $senhaCriptografada,
            ":email" => $email
        ]);

        $mensagem = "Senha atualizada com sucesso! <a href='entrar.php'>Clique aqui para fazer login</a>.";
        $etapa = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
</head>
<body>
    <div class="form-container">
        <h2>Recuperação de Senha</h2>

        <?php if ($etapa == 1): ?>
            <p>Digite seu e-mail cadastrado:</p>
            <form method="post">
                <input type="email" name="email" required placeholder="Seu e-mail"><br><br>
                <input type="submit" name="validar-email" value="Continuar">
            </form>
        <?php elseif ($etapa == 2): ?>
            <p>Digite a nova senha para o e-mail <strong><?php echo htmlspecialchars($email); ?></strong>:</p>
            <form method="post">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="password" name="nova_senha" required placeholder="Nova senha"><br><br>
                <input type="submit" name="definir-senha" value="Salvar Nova Senha">
            </form>
        <?php endif; ?>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <p><a href="entrar.php">Voltar ao login</a></p>
    </div>
</body>
</html>
