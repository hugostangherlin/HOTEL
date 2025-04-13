<?php
session_start();
require 'config/config.php';
// Cadastro de Gestor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome      = $_POST['name'];
    $email     = $_POST['email'];
    $senha     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefone  = $_POST['telefone'];
    $cpf       = $_POST['cpf'];
    $endereco  = $_POST['endereco'];
    $nascimento = $_POST['birthdate'];
    $perfil    = $_POST['perfil']; // aqui sempre vai vir '1'

    // Inserir no banco
    $stmt = $pdo->prepare("INSERT INTO usuarios (Nome, Email, Senha, Telefone, CPF, Endereco, Data_Nascimento, Perfil_ID_Perfil)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $executou = $stmt->execute([$nome, $email, $senha, $telefone, $cpf, $endereco, $nascimento, $perfil]);
    if ($executou) {
        echo "<script>alert('Gestor cadastrado com sucesso!'); window.location.href='entrar.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar!');</script>";
    }
    
}
?>
