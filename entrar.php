<?php
session_start();
require __DIR__ . '/config/config.php';

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

    // Verifica se o usuário existe e se a senha bate
    if ($usuario && password_verify($senha, $usuario['Senha'])) {
        // Armazena os dados na sessão
        $_SESSION['usuario'] = [
            'id' => $usuario['ID'],
            'nome' => $usuario['Nome'],
            'email' => $usuario['Email'],
            'perfil' => $usuario['Perfil_ID_Perfil']
        ];

        // Redireciona conforme o perfil do usuário
        switch ($usuario['Perfil_ID_Perfil']) {
            case 1: // Gestor
                header("Location: /HOTEL/gestor/dashboard.php");
                break;
            case 2: // Hóspede
                // Verifica se há uma URL salva e redireciona para ela
                if (isset($_SESSION['retorno'])) {
                    $retornoUrl = $_SESSION['retorno'];
                    unset($_SESSION['retorno']); // Limpa a variável após o redirecionamento
                    header("Location: $retornoUrl");
                    exit();
                } else {
                    // Caso não tenha URL salva, redireciona para o painel do hóspede
                    header("Location: /HOTEL/hospede/pages/pag_hospede.php");
                }
                break;
            default: // Caso o perfil não esteja identificado
                $_SESSION['erro_login'] = "Perfil não identificado!";
                header("Location: entrar.php");
                exit();
        }
        exit();
    } else {
        $_SESSION['erro_login'] = "Credenciais inválidas!";
        header("Location: entrar.php");
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Hotel</title>
  <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="entrar.css">
</head>
<body>
  <div class="login-container">
    <form action="entrar.php" method="POST" class="login-form">
      <h2>Entrar</h2>

      <?php if (isset($_SESSION['erro_login'])): ?>
        <div class="error-message">
          <?php
            echo $_SESSION['erro_login'];
            unset($_SESSION['erro_login']);
          ?>
        </div>
      <?php endif; ?>

      <div class="input-group">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required>
      </div>

      <div class="input-group">
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" required>
      </div>

      <button type="submit" class="btn-login">Entrar</button>

      <div class="links">
        <a href="recuperarsenha.php">Esqueceu a senha?</a>
        <span> | </span>
        <a href="#" id="open-register-form">Criar conta</a> <!-- Link para abrir o modal -->
      </div>
    </form>
  </div>

  <!-- Modal (pop-up) do Formulário de Cadastro -->
  <div id="register-modal" class="modal">
    <div class="modal-content">
      <span id="close-modal" class="close">&times;</span>
      <h2>Crie sua conta</h2>
      <form action="actions/adicionar_hospede.php" method="post" id="form">
        <div id="input_container">
          <div class="input-box">
            <br>
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" placeholder="Digite seu nome" required>
          </div>
          <br>
          <div class="input-box">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="exemplo@gmail.com" required>
          </div>
          <br>
          <div class="input-box">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" required>
          </div>
          <br>
          <div class="input-box">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" placeholder="+00 (00)0000-0000" required>
          </div>
          <br>
          <div class="input-box">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required>
          </div>
          <div class="input-box">
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" placeholder="Digite seu endereço" required>
          </div>
          <br>
          <div class="input-box">
            <label for="birthdate">Data de Nascimento</label>
            <input type="date" name="birthdate" id="birthdate" required>
            <div class="input-box">
            <div class="form-group form-check mt-3">
                <input type="checkbox" class="form-check-input" id="lgpd" name="aceite_lgpd" required>
                <label class="form-check-label" for="lgpd">
                    Aceito os <a href="termos_e_condicoes.php" target="_blank">termos de uso e a política de privacidade (LGPD)</a>.
                </label>
            </div>
            <br>
          </div>
          <button type="submit" name="submit" class="btn-default">Cadastrar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Abrir o modal de cadastro
    const openModalButton = document.getElementById("open-register-form");
    const modal = document.getElementById("register-modal");
    const closeModalButton = document.getElementById("close-modal");

    openModalButton.onclick = function() {
      modal.style.display = "block";
    }

    // Fechar o modal de cadastro
    closeModalButton.onclick = function() {
      modal.style.display = "none";
    }

    // Fechar o modal quando clicar fora dele
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
