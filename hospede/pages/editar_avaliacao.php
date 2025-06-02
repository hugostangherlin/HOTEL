<?php
include '../../config/config.php';
session_start();

if (!isset($_GET['id'])) {
    echo "ID da avaliação não fornecido.";
    exit;
}

$idAvaliacao = $_GET['id'];

// Buscar dados da avaliação atual
$sql = "SELECT * FROM avaliacao WHERE ID_Avaliacao = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $idAvaliacao, PDO::PARAM_INT);
$stmt->execute();
$avaliacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$avaliacao) {
    echo "Avaliação não encontrada.";
    exit;
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaNota = $_POST['nota'];
    $novoComentario = $_POST['comentario'];

    $sql = "UPDATE avaliacao SET Nota = :nota, Comentario = :comentario WHERE ID_Avaliacao = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nota', $novaNota, PDO::PARAM_INT);
    $stmt->bindParam(':comentario', $novoComentario, PDO::PARAM_STR);
    $stmt->bindParam(':id', $idAvaliacao, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: pag_hospede.php?sucesso=avaliacao_editada");
        exit;
    } else {
        echo "Erro ao atualizar a avaliação.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avaliação</title>
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
        
        .evaluation-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 16px;
        }
        
        select, textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .star-rating {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .star-rating label:hover,
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating input[type="radio"]:hover ~ label {
            color: #ffc107;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #e0443d;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            
        }
        
        
        .btn-secondary:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-actions {
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
            <h1 class="page-title">Editar Avaliação</h1>
        </div>
        
        <form method="POST" class="evaluation-form">
            <div class="form-group">
                <label for="nota">Sua avaliação</label>
                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="nota" value="<?= $i ?>" 
                               <?= $avaliacao['Nota'] == $i ? 'checked' : '' ?> required>
                        <label for="star<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comentario">Comentário</label>
                <textarea name="comentario" id="comentario" required><?= htmlspecialchars($avaliacao['Comentario']) ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="ver_avaliacao.php?id=<?= $avaliacao['ID_Reserva'] ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>