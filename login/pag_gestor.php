<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] != 2) {
    header("Location: entrar.php");
    exit;
}
?>
<h1>Bem-vindo, Gestor!</h1>
<a href="logout.php">Sair</a>

