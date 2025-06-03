<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['ID'];

// Busca os dados do usuário
$sql = "SELECT * FROM usuarios WHERE id = :id";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
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
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
    }
    
    .page-title {
        color: var(--secondary-color);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
        font-size: 28px;
    }
    
    .perfil {
        margin-bottom: 30px;
    }
    
    .perfil p {
        margin-bottom: 15px;
        padding: 10px 15px;
        background-color: var(--light-gray);
        border-radius: var(--border-radius);
        border-left: 3px solid var(--primary-color);
    }
    
    .perfil strong {
        color: var(--secondary-color);
        margin-right: 10px;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-group a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
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
    
    .btn-secondary {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #bb2d3b;
        transform: translateY(-2px);
    }
    
    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--dark-gray);
        color: var(--dark-gray);
    }
    
    .btn-outline:hover {
        background-color: var(--light-gray);
    }
    
    form button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        font-size: 16px;
        background-color: var(--secondary-color);
        color: white;
    }
    
    form button:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group a, form button {
            width: 100%;
            text-align: center;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Meu Perfil</h1>
        
        <div class="perfil">
            <p><strong><i class="fas fa-user"></i> Nome:</strong> <?= htmlspecialchars($usuario['Nome']) ?></p>
            <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?= htmlspecialchars($usuario['Email']) ?></p>
            <p><strong><i class="fas fa-phone"></i> Telefone:</strong> <?= htmlspecialchars($usuario['Telefone']) ?></p>
            <p><strong><i class="fas fa-id-card"></i> CPF:</strong> <?= htmlspecialchars($usuario['CPF']) ?></p>
            <p><strong><i class="fas fa-map-marker-alt"></i> Endereço:</strong> <?= htmlspecialchars($usuario['Endereco']) ?></p>
            <p><strong><i class="fas fa-birthday-cake"></i> Data de Nascimento:</strong> <?= htmlspecialchars($usuario['Data_Nascimento']) ?></p>
        </div>

        <div class="btn-group">
            <a href="editar_gestor.php" class="btn-primary">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
            
            <a href="deletar_gestor.php?id=<?= $usuario['ID'] ?>" class="btn-danger" onclick="return confirm('Tem certeza que deseja excluir sua conta?')">
                <i class="fas fa-trash-alt"></i> Excluir Conta
            </a>
            
            <a href="/HOTEL/gestor/dashboard.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</body>
</html>
</body>
</html>
