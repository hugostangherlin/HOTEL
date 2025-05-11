<?php
session_start();

// Verifica se o usuário é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$nomeUsuario = $_SESSION['usuario']['nome'];

require_once '../../config/config.php';

include '../../includes/seachbar.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rodeo Hotel</title>
  <link rel="stylesheet" href="pag_hospede.css">
  <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
</head>
<body>
  <br>
  <main class="main-content">
    <div class="container">
      <h3 class="mb-0">Olá, <?= $nomeUsuario ?></h3>

      <!-- Exibe os resultados da busca -->
      <?php include '../../includes/resultado_busca.php'; ?>
    </div>
  </main>

  <?php include '../../includes/rodape.php'; ?>
</body>
</html>
