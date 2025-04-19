<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta usuário no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE LOWER(Email) = LOWER(?)");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $_SESSION['erro_login'] = "Usuário não encontrado!";
        header("Location: entrar.php");
        exit();
    }

    // Valida senha
    if ($usuario && password_verify($senha, $usuario['Senha'])) {
        // Armazena dados na sessão
        $_SESSION['usuario'] = [
            'id'     => $usuario['ID'],
            'nome'   => $usuario['Nome'],
            'email'  => $usuario['Email'],
            'perfil' => $usuario['Perfil_ID_Perfil']
        ];

        // Redireciona conforme o perfil
        switch ($usuario['Perfil_ID_Perfil']) {
            case 1:
                header("Location: gestor/pages/pag_gestor.php");
                break;
            case 2:
                header("Location: hospede/pages/pag_hospede.php");
                break;
            default:
                $_SESSION['erro_login'] = "Perfil não identificado!";
                header("Location: entrar.php");
                break;
        }
        exit();
    } else {
        $_SESSION['erro_login'] = "Credenciais inválidas!";
        header("Location: entrar.php");
        exit();
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Hotel</title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
</head>
<body>
    <h2>Entrar</h2>

    <!-- Exibe erro de login -->
    <?php if (isset($_SESSION['erro_login'])): ?>
        <div style="color: red; margin: 10px 0;">
            <?php
            echo $_SESSION['erro_login'];
            unset($_SESSION['erro_login']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form action="entrar.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <a href="hospede/form/formcadastro.php">Cadastrar</a>
</body>
</html>
