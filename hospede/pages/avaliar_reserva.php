<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado e se é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

// Pega ID do usuário da sessão
$id_usuario = $_SESSION['usuario']['ID'];

// Verifica se recebeu ID da reserva
$id_reserva = $_GET['id_reserva'] ?? $_GET['id'] ?? null;
if (!$id_reserva) {
    echo "Reserva não encontrada.";
    exit();
}
// Verifica se a reserva pertence ao usuário e já passou o checkout
$stmt = $pdo->prepare("SELECT * FROM reserva WHERE ID_Reserva = ? AND usuarios_ID = ?");
$stmt->execute([$id_reserva, $id_usuario]);
$reserva = $stmt->fetch();

if (!$reserva) {
    echo "Reserva inválida ou ainda não concluída.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Reserva</title>
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
            max-width: 600px;
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
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        label {
            font-weight: 600;
            color: var(--secondary-color);
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
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            align-self: flex-start;
        }
        
        .btn-submit:hover {
            background-color: #e0443d;
            transform: translateY(-2px);
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
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .star-rating label:hover,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Avaliar Reserva</h1>
        </div>
        
        <form method="POST" action="/HOTEL/actions/avaliar_action.php" class="evaluation-form">
            <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
            
            <div class="form-group">
                <label for="nota">Sua avaliação</label>
                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="nota" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comentario">Comentário</label>
                <textarea name="comentario" placeholder="Compartilhe sua experiência conosco..."></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Enviar Avaliação</button>
        </form>
    </div>
</body>
</html>