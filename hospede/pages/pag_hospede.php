<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de hóspede (ID 2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
$nome = $_SESSION['usuario']['nome'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodeo Hotel</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <link rel="icon" href="../assets/favicon.ico?v=1" type="image/x-icon">
</head>
<?php include '../includes/header.php'; ?>

<div class="conteudo">
    <h3><?php echo "$saudacao, $nome!"; ?></h3>
<div class="exibirperfil">
    <a href="exibir_hospede.php">Meu Perfil</a>
</div>

<div class="btn_logout">
<form action="/HOTEL/logout.php" method="post" class="btn_logout">
<button type="submit">Sair</button>
</div>

