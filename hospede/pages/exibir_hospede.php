<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

// Busca os dados do usuário
$sql = "SELECT * FROM usuarios WHERE ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sua Conta</title>

</head>
<body>

    <h1">Sua Conta</h1>

    <div class="perfil">
        <p><strong>Nome:</strong> <?= $usuario['Nome'] ?></p>
        <p><strong>Email:</strong> <?= $usuario['Email'] ?></p>
        <p><strong>Telefone:</strong> <?= $usuario['Telefone'] ?></p>
        <p><strong>CPF:</strong> <?= $usuario['CPF'] ?></p>
        <p><strong>Endereço:</strong> <?= $usuario['Endereco'] ?></p>
        <p><strong>Data de Nascimento:</strong> <?= $usuario['Data_Nascimento'] ?></p>
    </div>

    <div class="btn-group">
    <form action="editar_perfil.php">
    <button >Editar Conta</button>
</form>
<br><br>
        <form action="deletar_perfil.php" method="post">
    <button type="submit" style="color: red;">Solicitar exclusão da conta</button>
</form>
<br><br>
<form action="pag_hospede.php">
    <button >Voltar</button>
</form>
</body>
</html>
