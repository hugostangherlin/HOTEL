<?php
// Iniciar sessão se necessário
if (session_status() === PHP_SESSION_NONE) session_start();

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    // Redireciona se alguém tentar acessar diretamente
    header("Location: home.php");
    exit;
}

// Conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$nome_banco = "rodeo_hotel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nome_banco;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Recebe e valida os dados obrigatórios
$checkin    = $_GET['checkin'] ?? '';
$checkout   = $_GET['checkout'] ?? '';
$categoria  = $_GET['categoria'] ?? '';
// $hospedes = isset($_GET['hospedes']) ? (int) $_GET['hospedes'] : 1;


if (!$checkin || !$checkout || !$categoria) {
    die();
}

// Consulta para encontrar quartos disponíveis com base nos critérios
$sql = "
SELECT quarto.*, categoria.Nome AS NomeCategoria
FROM quarto
JOIN categoria ON quarto.Categoria_ID_Categoria = categoria.ID_Categoria
WHERE quarto.Categoria_ID_Categoria = :categoria
AND quarto.ID_Quarto NOT IN (
    SELECT Quarto_ID_Quarto 
    FROM reserva 
    WHERE Checkout >= :checkin AND Checkin <= :checkout
)
";

// Preparando a consulta
$stmt = $pdo->prepare($sql);

// Definindo os valores para os parâmetros
$stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
$stmt->bindParam(':checkin', $checkin, PDO::PARAM_STR);
$stmt->bindParam(':checkout', $checkout, PDO::PARAM_STR);

// Executando a consulta
$stmt->execute();

// Buscando os resultados
$quartos = $stmt->fetchAll();


// if (!is_numeric($hospedes) || $hospedes < 1) {
//     echo "Número de hóspedes inválido!";
//     exit;
// }


// Exibir os resultados
$checkin_formatado = DateTime::createFromFormat('Y-m-d', $checkin)->format('d/m/Y');
$checkout_formatado = DateTime::createFromFormat('Y-m-d', $checkout)->format('d/m/Y');
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartos Disponíveis - Rodeo Hotel</title>
      <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --white: #ffffff;
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-summary {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .search-summary h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .search-summary p {
            margin-bottom: 8px;
            font-size: 16px;
        }
        
        .search-summary strong {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .rooms-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .room-card {
            background-color: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .room-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .room-details {
            padding: 20px;
        }
        
        .room-category {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .room-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 10px 0;
        }
        
        .room-features {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--dark-gray);
            font-size: 14px;
        }
        
        .feature i {
            color: var(--primary-color);
        }
        
        .view-details-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
            margin-top: 15px;
        }
        
        .view-details-btn:hover {
            background-color: #e0413a;
        }
        
        .no-rooms {
            text-align: center;
            padding: 40px;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .no-rooms p {
            font-size: 18px;
            color: var(--dark-gray);
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .rooms-container {
                grid-template-columns: 1fr;
            }
            
            .search-summary {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-summary">
            <h2>Sua Busca</h2>
            <p><strong>Check-in:</strong> <?= $checkin_formatado ?></p>
            <p><strong>Check-out:</strong> <?= $checkout_formatado ?></p>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($quartos[0]['NomeCategoria'] ?? 'N/A') ?></p>
        </div>

        <?php if ($quartos): ?>
            <div class="rooms-container">
                <?php foreach ($quartos as $quarto): ?>
                    <div class="room-card">
                        <?php if (!empty($quarto['Foto'])): ?>
                            <img src="/HOTEL/uploads/<?= $quarto['Foto'] ?>" alt="Foto do Quarto" class="room-image">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x200?text=Rodeo+Hotel" alt="Quarto sem foto" class="room-image">
                        <?php endif; ?>
                        
                        <div class="room-details">
                            <h3 class="room-category"><?= htmlspecialchars($quarto['NomeCategoria']) ?></h3>
                            <div class="room-price">R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?>/noite</div>
                            
                            <div class="room-features">
                                <div class="feature">
                                    <i class="fas fa-user"></i>
                                    <span><?= $quarto['Capacidade'] ?> pessoa(s)</span>
                                </div>
                            </div>
                            
                            <a href="/HOTEL/hospede/pages/detalhes_quarto.php?id=<?= $quarto['ID_Quarto'] ?>&checkin=<?= $_GET['checkin'] ?>&checkout=<?= $_GET['checkout'] ?>" class="view-details-btn">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-rooms">
                <p>Nenhum quarto disponível para os critérios informados.</p>
                <a href="home.php" class="view-details-btn">Nova Busca</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>