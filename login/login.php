<?php
session_start();
include 'config.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

try {
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE Email = :email");
    $sql->bindParam(':email', $email);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $usuario['Senha'])) {
            $_SESSION['usuario_id'] = $usuario['ID_Usuario'];
            $_SESSION['usuario_email'] = $usuario['Email'];
            $_SESSION['usuario_perfil'] = $usuario['Perfil_ID_Perfil'];

            if ($_SESSION['usuario_perfil'] == 1) {
                header("Location: pag_hospede.php");
            } elseif ($_SESSION['usuario_perfil'] == 2) {
                header("Location: pag_gestor.php");
            } else {
                echo "Perfil não reconhecido.";
            }
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

