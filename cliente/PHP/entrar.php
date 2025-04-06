<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
  header("Location: home.php");
  exit;
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/entrar.css">
  <title>Login para Hóspedes</title>
</head>
<body>
<section class="container">
    <div class="login">
      <h2>Entrar</h2>
      <form class="box-input" action="login.php" method="post">
        <input type="email" name="email" placeholder="Email ou número de telefone" required>
        <input type="password" name="senha" placeholder="Senha">
        <button type="submit" class="btn">Entrar</button>
      </form>
      <br>

      <div class="footer">
        <a href="/cliente/PHP/cadastro.php">Crie sua conta</a>
  </section>
</body>
</html>