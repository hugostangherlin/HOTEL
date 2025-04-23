<?php
session_start();
require '../config/config.php';

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
    <title>Meu Perfil</title>

</head>
<body>

    <h1">Meu Perfil</h1>

    <div class="perfil">
        <p><strong>Nome:</strong> <?= $usuario['Nome'] ?></p>
        <p><strong>Email:</strong> <?= $usuario['Email'] ?></p>
        <p><strong>Telefone:</strong> <?= $usuario['Telefone'] ?></p>
        <p><strong>CPF:</strong> <?= $usuario['CPF'] ?></p>
        <p><strong>Endereço:</strong> <?= $usuario['Endereco'] ?></p>
        <p><strong>Data de Nascimento:</strong> <?= $usuario['Data_Nascimento'] ?></p>
    </div>

    <div class="btn-group">
        <a href="editar_gestor.php">Editar Perfil</a>
        <br><br>
        <a href="deletar_gestor.php">Excluir Conta</a>
        <br><br>
        <a href="pag_gestor.php">Voltar</a>
        <br><br>
        <form action="/HOTEL/logout.php" method="post">
            <button type="submit">Sair</button>
        </form>
    </div>

</body>
</html>
