<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Hotel</title>
</head>
<body>
    <h2>Entrar</h2>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($erro)): ?>
        <div style="color: red;"><?php echo $erro; ?></div>
    <?php endif; ?>
    <a href="formcadastro.php">Cadastrar</a>
</body>
</html>
