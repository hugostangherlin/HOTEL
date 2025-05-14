<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

// Verifica se o ID do usuário está na sessão
$id_usuario = $_SESSION['usuario']['id'];

// Busca os dados do usuário
$sql = "SELECT * FROM usuarios WHERE ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o usuário
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Processamento do POST (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $dataNascimento = $_POST['data_nascimento'];

    try {
        // Atualiza os dados do usuário no banco
        $sql = "UPDATE usuarios SET Nome = ?, Email = ?, Telefone = ?, Endereco = ?, Data_Nascimento = ? WHERE ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $telefone, $endereco, $dataNascimento, $id_usuario]);

        // Atualiza a sessão com os novos dados
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefone'] = $telefone;
        $_SESSION['usuario']['endereco'] = $endereco;
        $_SESSION['usuario']['data_nascimento'] = $dataNascimento;

        // Atualiza a senha, se fornecida
        if (!empty($_POST['nova_senha']) && !empty($_POST['confirmar_senha'])) {
            $novaSenha = $_POST['nova_senha'];
            $confirmarSenha = $_POST['confirmar_senha'];

            if ($novaSenha === $confirmarSenha) {
                $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                $sqlSenha = "UPDATE usuarios SET Senha = ? WHERE ID = ?";
                $stmtSenha = $pdo->prepare($sqlSenha);
                $stmtSenha->execute([$senhaHash, $id_usuario]);
            } else {
                die("As senhas não coincidem.");
            }
        }

        // Redireciona de volta para o perfil
        header("Location: exibir_hospede.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao atualizar: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --border-radius: 10px;
            --box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            color: var(--secondary-color);
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .profile-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .divider {
            border-top: 1px solid #eee;
            margin: 25px 0;
        }
        
        .card-footer {
            padding: 20px;
            background-color: var(--light-gray);
            text-align: right;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            font-size: 16px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        .input-icon {
            color: var(--dark-gray);
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0;
            }
            
            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Editar Perfil</h1>
        </div>
        
        <div class="profile-card">
            <div class="card-header">
                <h3 class="card-title">Dados da sua conta</h3>
            </div>
            
            <form method="POST">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nome" class="form-label">
                            <i class="fas fa-user input-icon"></i>
                            Nome
                        </label>
                        <input type="text" id="nome" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['Nome'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope input-icon"></i>
                            Email
                        </label>
                        <input type="email" id="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['Email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone" class="form-label">
                            <i class="fas fa-phone input-icon"></i>
                            Telefone
                        </label>
                        <input type="text" id="telefone" class="form-control" name="telefone" value="<?= htmlspecialchars($usuario['Telefone'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="endereco" class="form-label">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            Endereço
                        </label>
                        <textarea id="endereco" class="form-control" name="endereco" rows="3"><?= htmlspecialchars($usuario['Endereco'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_nascimento" class="form-label">
                            <i class="fas fa-birthday-cake input-icon"></i>
                            Data de Nascimento
                        </label>
                        <input type="date" id="data_nascimento" class="form-control" name="data_nascimento" value="<?= htmlspecialchars($usuario['Data_Nascimento'] ?? '') ?>">
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="form-group">
                        <label for="nova_senha" class="form-label">
                            <i class="fas fa-lock input-icon"></i>
                            Nova Senha
                        </label>
                        <input type="password" id="nova_senha" class="form-control" name="nova_senha" placeholder="Deixe em branco se não for alterar">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha" class="form-label">
                            <i class="fas fa-lock input-icon"></i>
                            Confirmar Nova Senha
                        </label>
                        <input type="password" id="confirmar_senha" class="form-control" name="confirmar_senha" placeholder="Repita a nova senha">
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>