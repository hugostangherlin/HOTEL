<?php
require_once '../../config/config.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);

header("Location: /HOTEL/entrar.php");
exit();
?>