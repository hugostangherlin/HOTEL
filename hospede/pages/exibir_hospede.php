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

// Verifica se encontrou o usuário
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Garante que apenas hóspedes acessem esta página
if ($usuario['Perfil_ID_Perfil'] != 2) {
    die("Acesso restrito a hóspedes.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Conta | Rodeo Hotel</title>
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
        
        .profile-section {
            margin-bottom: 30px;
        }
        
        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-value {
            padding: 10px 15px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            border-left: 3px solid var(--primary-color);
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
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
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Sua Conta</h1>
        
        <div class="profile-section">
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user"></i>
                        <span>Nome</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['Nome']) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['Email']) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-phone"></i>
                        <span>Telefone</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['Telefone']) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-card"></i>
                        <span>CPF</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['CPF']) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Endereço</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['Endereco']) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-birthday-cake"></i>
                        <span>Data de Nascimento</span>
                    </div>
                    <div class="info-value"><?= htmlspecialchars($usuario['Data_Nascimento']) ?></div>
                </div>
            </div>
        </div>
        
        <div class="btn-group">
            <form action="editar_perfil.php">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar Conta
                </button>
            </form>
            
            <form action="deletar_perfil.php" method="post">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Solicitar Exclusão da Conta
                </button>
            </form>
            
            <form action="pag_hospede.php">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
