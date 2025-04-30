<?php
include './config/config.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);

header("Location: clientes.php");
exit();
?>