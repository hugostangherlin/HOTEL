<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de gestor (ID 1)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
?>
<h1>Bem-vindo, Gestor!</h1>
<a href="logout.php">Sair</a>


