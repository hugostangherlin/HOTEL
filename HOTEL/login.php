<?php
session_start();
require 'config/config.php';
 // Incluir a configuração de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta para verificar se o usuário existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE LOWER(Email) = LOWER(?)");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário foi encontrado
    if (!$usuario) {
        die("Usuário não encontrado!");
    }

    // Verifica se a senha está correta
    if ($usuario && password_verify($senha, $usuario['Senha'])) {
        // Salvar as informações do usuário na sessão
        $_SESSION['usuario'] = [
            'id' => $usuario['ID_Usuario'],
            'nome' => $usuario['Nome'],
            'email' => $usuario['Email'],
            'perfil' => $usuario['Perfil_ID_Perfil']
        ];

        // Redirecionamento baseado no perfil
        switch ($usuario['Perfil_ID_Perfil']) {
            case 1: // Gestor
                header("Location: pages/pag_gestor.php");

                break;
            case 2: // Hóspede
                header("Location:pages/pag_hospede.php");

                break;
            default:
                die("Perfil não identificado!");
        }
        exit();
    } else {
        $erro = "Credenciais inválidas!";
    }
}
?>


