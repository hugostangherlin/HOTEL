<?php
session_start();
require __DIR__ . '/Config/config.php';

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
                header("Location: gestor/dashboard.php");
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
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Hotel</title>
  <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #d32f2f;
      --primary-dark: #b71c1c;
      --primary-light: #ff6659;
      --secondary-color: #2c3e50;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --gray-color: #6c757d;
      --success-color: #28a745;
      --error-color: #dc3545;
      --border-radius: 8px;
      --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Raleway', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('HOTEL/assets/img/login.png') no-repeat center center;
      background-size: cover;
      padding: 20px;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      transform: translateY(0);
      transition: var(--transition);
      animation: fadeInUp 0.5s ease-out;
    }

    .login-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .login-form {
      padding: 2.5rem;
    }

    .login-form h2 {
      margin-bottom: 1.5rem;
      color: var(--secondary-color);
      font-weight: 700;
      text-align: center;
      font-size: 1.8rem;
      position: relative;
    }

    .login-form h2::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background: var(--primary-color);
      margin: 0.5rem auto 0;
    }

    .input-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .input-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--secondary-color);
      font-weight: 500;
      font-size: 0.95rem;
    }

    .input-group input {
      width: 100%;
      padding: 0.8rem 1rem;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: var(--transition);
      background-color: var(--light-color);
    }

    .input-group input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2);
    }

    .btn-login {
      width: 100%;
      padding: 0.8rem;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 0.5rem;
      letter-spacing: 0.5px;
    }

    .btn-login:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .links {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 1.5rem;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .links a {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .links a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .links span {
      color: var(--gray-color);
    }

    .error-message {
      background-color: #f8d7da;
      color: var(--error-color);
      padding: 0.75rem;
      border-radius: var(--border-radius);
      margin-bottom: 1.5rem;
      text-align: center;
      border: 1px solid #f5c6cb;
      font-size: 0.9rem;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.6);
      animation: fadeIn 0.3s;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 8% auto;
      padding: 2rem;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      width: 90%;
      max-width: 500px;
      position: relative;
      animation: slideDown 0.4s;
    }

    .close {
      position: absolute;
      right: 1.5rem;
      top: 1rem;
      color: var(--gray-color);
      font-size: 1.8rem;
      font-weight: bold;
      cursor: pointer;
      transition: var(--transition);
    }

    .close:hover {
      color: var(--primary-color);
    }

    .modal h2 {
      color: var(--secondary-color);
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
      position: relative;
    }

    .modal h2::after {
      content: '';
      display: block;
      width: 50px;
      height: 3px;
      background: var(--primary-color);
      margin: 0.5rem auto 0;
    }

    .input-box {
      margin-bottom: 1rem;
    }

    .input-box label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--secondary-color);
      font-weight: 500;
      font-size: 0.95rem;
    }

    .input-box input {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-size: 1rem;
      background-color: var(--light-color);
    }

    .input-box input:focus {
      outline: none;
      border-color: var(--primary-color);
    }

    .btn-default {
      width: 100%;
      padding: 0.8rem;
      background-color: var(--success-color);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 1rem;
    }

    .btn-default:hover {
      background-color: #218838;
      transform: translateY(-2px);
    }

    .form-check {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
    }

    .form-check-input {
      margin-right: 0.5rem;
      width: 1.1em;
      height: 1.1em;
    }

    .form-check-label {
      font-size: 0.85rem;
      color: var(--gray-color);
    }

    .form-check-label a {
      color: var(--primary-color);
      text-decoration: none;
    }

    .form-check-label a:hover {
      text-decoration: underline;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideDown {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .login-form {
        padding: 1.8rem;
      }
      
      .modal-content {
        margin: 12% auto;
        padding: 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .login-form {
        padding: 1.5rem;
      }
      
      .links {
        flex-direction: column;
        gap: 0.3rem;
      }
      
      .links span {
        display: none;
      }
      
      .modal-content {
        margin: 15% auto;
        padding: 1.2rem;
      }
    }
  </style>
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
        <a href="#" id="open-register-form">Criar conta</a>
      </div>
    </form>
  </div>

  <!-- Modal de Cadastro -->
  <div id="register-modal" class="modal">
    <div class="modal-content">
      <span id="close-modal" class="close">&times;</span>
      <h2>Crie sua conta</h2>
      <form action="actions/adicionar_hospede.php" method="post" id="form">
        <div id="input_container">
          <div class="input-box">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" placeholder="Digite seu nome" required>
          </div>

          <div class="input-box">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="exemplo@gmail.com" required>
          </div>

          <div class="input-box">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" required>
          </div>

          <div class="input-box">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" placeholder="+00 (00)0000-0000" required>
          </div>

          <div class="input-box">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required>
          </div>

          <div class="input-box">
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" placeholder="Digite seu endereço" required>
          </div>

          <div class="input-box">
            <label for="birthdate">Data de Nascimento</label>
            <input type="date" name="birthdate" id="birthdate" required>
          </div>

          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="lgpd" name="aceite_lgpd" required>
            <label class="form-check-label" for="lgpd">
              Aceito os <a href="termos_e_condicoes.php" target="_blank">termos de uso e a política de privacidade (LGPD)</a>.
            </label>
          </div>

          <button type="submit" name="submit" class="btn-default">Cadastrar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Modal functionality
    document.getElementById('open-register-form').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('register-modal').style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    });

    document.getElementById('close-modal').addEventListener('click', function() {
      document.getElementById('register-modal').style.display = 'none';
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
      if (event.target === document.getElementById('register-modal')) {
        document.getElementById('register-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
      }
    });

    // CPF mask
    document.getElementById('cpf').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      
      if (value.length > 3) {
        value = value.replace(/^(\d{3})(\d)/g, '$1.$2');
      }
      if (value.length > 6) {
        value = value.replace(/^(\d{3})\.(\d{3})(\d)/g, '$1.$2.$3');
      }
      if (value.length > 9) {
        value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/g, '$1.$2.$3-$4');
      }
      if (value.length > 11) {
        value = value.substring(0, 14);
      }
      
      e.target.value = value;
    });

    // Phone mask
    document.getElementById('telefone').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      
      if (value.length > 0) {
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
      }
      if (value.length > 10) {
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
      }
      if (value.length > 15) {
        value = value.substring(0, 15);
      }
      
      e.target.value = value;
    });
  </script>
</body>
</html>