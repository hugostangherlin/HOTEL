<?php
require __DIR__ . '/config/config.php';

$mensagem = "";
$etapa = 1;

if (isset($_POST['validar-email'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $etapa = 2;
        } else {
            $mensagem = "E-mail não encontrado.";
        }
    }
}

if (isset($_POST['definir-senha'])) {
    $email = $_POST['email'];
    $novaSenha = $_POST['nova_senha'];

    if (strlen($novaSenha) < 6) {
        $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        $etapa = 2;
    } else {
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([
            ":senha" => $senhaCriptografada,
            ":email" => $email
        ]);

        $mensagem = "Senha atualizada com sucesso! <a href='entrar.php'>Clique aqui para fazer login</a>.";
        $etapa = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
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

.form-container {
  width: 100%;
  max-width: 420px;
  background-color: rgba(255, 255, 255, 0.95);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 2.5rem;
  transform: translateY(0);
  transition: var(--transition);
  animation: fadeInUp 0.5s ease-out;
}

.form-container:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.form-container h2 {
  margin-bottom: 1.5rem;
  color: var(--secondary-color);
  font-weight: 700;
  text-align: center;
  font-size: 1.8rem;
  position: relative;
}

.form-container h2::after {
  content: '';
  display: block;
  width: 60px;
  height: 3px;
  background: var(--primary-color);
  margin: 0.5rem auto 0;
}

.form-container p {
  color: var(--secondary-color);
  margin-bottom: 1.5rem;
  text-align: center;
  font-size: 0.95rem;
  line-height: 1.5;
}

.form-container strong {
  color: var(--primary-dark);
  word-break: break-all;
}

.input-group {
  margin-bottom: 1.5rem;
  position: relative;
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

.btn-submit {
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

.btn-submit:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.mensagem {
  background-color: #f8d7da;
  color: var(--error-color);
  padding: 0.75rem;
  border-radius: var(--border-radius);
  margin: 1.5rem 0;
  text-align: center;
  border: 1px solid #f5c6cb;
  font-size: 0.9rem;
}

.mensagem.success {
  background-color: #d4edda;
  color: var(--success-color);
  border-color: #c3e6cb;
}

.back-link {
  display: block;
  text-align: center;
  margin-top: 1.5rem;
}

.back-link a {
  color: var(--primary-color);
  text-decoration: none;
  font-size: 0.9rem;
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
}

.back-link a:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

/* Animations */
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

/* Responsive */
@media (max-width: 768px) {
  .form-container {
    padding: 1.8rem;
  }
}

@media (max-width: 480px) {
  .form-container {
    padding: 1.5rem;
  }
  
  .form-container h2 {
    font-size: 1.5rem;
  }
  
  .form-container p {
    font-size: 0.85rem;
  }
}
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Recuperação de Senha</h2>

        <?php if ($etapa == 1): ?>
            <p>Digite seu e-mail cadastrado:</p>
            <form method="post">
                <input type="email" name="email" required placeholder="Seu e-mail"><br><br>
                <input type="submit" name="validar-email" value="Continuar">
            </form>
        <?php elseif ($etapa == 2): ?>
            <p>Digite a nova senha para o e-mail <strong><?php echo htmlspecialchars($email); ?></strong>:</p>
            <form method="post">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="password" name="nova_senha" required placeholder="Nova senha"><br><br>
                <input type="submit" name="definir-senha" value="Salvar Nova Senha">
            </form>
        <?php endif; ?>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <p><a href="entrar.php">Voltar ao login</a></p>
    </div>
</body>
</html>
