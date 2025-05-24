<?php
require_once '../../config/config.php';
session_start();

if (!isset($_GET['id'])) {
    echo "ID da reserva não fornecido.";
    exit;
}

$reservaId = $_GET['id'];

// Buscar a avaliação da reserva
$sql = "SELECT * FROM avaliacao WHERE ID_Reserva = :reservaId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':reservaId', $reservaId, PDO::PARAM_INT);
$stmt->execute();
$avaliacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$avaliacao) {
    echo "Avaliação não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Avaliação | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --border-radius: 8px;
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
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .page-title {
            color: var(--secondary-color);
            font-size: 28px;
            margin: 0;
            text-align: center;
        }
        
        .evaluation-details {
            margin: 30px 0;
            padding: 25px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
        }
        
        .detail-item {
            margin-bottom: 20px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
            display: block;
        }
        
        .detail-value {
            font-size: 16px;
            line-height: 1.5;
        }
        
        .rating-stars {
            color: #ffc107;
            font-size: 24px;
            letter-spacing: 3px;
        }
        
        .comment-box {
            background-color: var(--white);
            padding: 15px;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
        }
        
        .btn-edit:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background-color: var(--secondary-color);
            color: white;
            border: none;
        }
        
        .btn-back:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Minha Avaliação</h1>
        </div>
        
        <div class="evaluation-details">
            <div class="detail-item">
                <span class="detail-label">Nota</span>
                <div class="detail-value rating-stars">
                    <?php 
                    $nota = $avaliacao['Nota'];
                    echo str_repeat('★', $nota) . str_repeat('☆', 5 - $nota);
                    ?>
                    <span style="color: var(--secondary-color); font-size: 16px; margin-left: 10px;">
                        (<?= $nota ?>/5)
                    </span>
                </div>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Comentário</span>
                <div class="detail-value comment-box">
                    <?= nl2br(htmlspecialchars($avaliacao['Comentario'])) ?>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="editar_avaliacao.php?id=<?= $avaliacao['ID_Avaliacao'] ?>" class="btn btn-edit">
                <i class="fas fa-edit"></i> Editar Avaliação
            </a>
            
            <form action="excluir_avaliacao.php" method="POST" style="display: inline;">
                <input type="hidden" name="id" value="<?= $avaliacao['ID_Avaliacao'] ?>">
                <button type="submit" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir a avaliação?')">
                    <i class="fas fa-trash-alt"></i> Excluir Avaliação
                </button>
            </form>
            
            <a href="minhas_reservas.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</body>
</html>