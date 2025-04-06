<?php
session_start();
include 'config.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

try {
    $sql = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
    $sql->bindParam(':email', $email);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $cliente = $sql->fetch(PDO::FETCH_ASSOC);

        
        if (password_verify($senha, $cliente['Senha'])) {
            $_SESSION['cliente_id'] = $cliente['ID_Cliente'];
            $_SESSION['cliente_email'] = $cliente['Email'];
            header("Location: pagina.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
} catch (PDOException $e) {
    echo "Erro ao fazer login: " . $e->getMessage();
}
?>