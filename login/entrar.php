<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login para Hóspedes</title>
</head>
<body>
  <h2>Entrar</h2>
  <form action="login.php" method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
  </form>
  <a href="cadastro.php">Cadastrar</a>
</body>
</html>
