<?php
require_once '../../config/config.php';
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}

$sql = $pdo->prepare("
    SELECT q.*, c.Nome AS nome_categoria
    FROM quarto q
    JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
    WHERE q.ID_Quarto = :id
");
$sql->bindValue(':id', $id);
$sql->execute();
$quarto = $sql->fetch(PDO::FETCH_ASSOC);

if (!$quarto) {
    echo "Quarto não encontrado.";
    exit;
}

$outros = $pdo->prepare("
    SELECT q.*, c.Nome AS nome_categoria
    FROM quarto q
    JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
    WHERE q.Categoria_ID_Categoria = :cat_id AND q.ID_Quarto != :id
");
$outros->bindValue(':cat_id', $quarto['Categoria_ID_Categoria']);
$outros->bindValue(':id', $id);
$outros->execute();
$outros_quartos = $outros->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($quarto['nome_categoria']) ?> - Detalhes | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --border-radius: 12px;
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Room Header Section */
        .room-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .room-image {
            height: 400px;
            background-size: cover;
            background-position: center;
        }
        
        .room-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .room-title {
            color: var(--secondary-color);
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .room-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0;
        }
        
        .room-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .feature i {
            color: var(--primary-color);
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .room-description {
            margin: 20px 0;
            line-height: 1.7;
        }
        
        /* Booking Form */
        .booking-form {
            margin-top: 30px;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            width: 100%;
            text-align: center;
        }
        
        .btn:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        /* Similar Rooms Section */
        .similar-rooms {
            margin-top: 50px;
        }
        
        .section-title {
            font-size: 24px;
            color: var(--secondary-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .room-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .room-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .room-card-content {
            padding: 20px;
        }
        
        .room-card-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        .room-card-price {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #1a252f;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .room-header {
                grid-template-columns: 1fr;
            }
            
            .room-image {
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .room-features {
                grid-template-columns: 1fr;
            }
            
            .room-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="room-header">
        <div class="room-image" style="background-image: url('../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>');"></div>
        
        <div class="room-content">
            <div>
                <h1 class="room-title"><?= htmlspecialchars($quarto['nome_categoria']) ?></h1>
                <div class="room-price">R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?> <small>/noite</small></div>
                
                <div class="room-features">
                    <div class="feature">
                        <i class="fas fa-user-friends"></i>
                        <span><?= htmlspecialchars($quarto['Capacidade']) ?> pessoa(s)</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-ruler-combined"></i>
                        <span><?= $quarto['Metragem'] ?? 'N/A' ?> m²</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-bed"></i>
                        <span>2 camas</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-bath"></i>
                        <span>1 banheiro</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-wifi"></i>
                        <span>Wi-Fi gratuito</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-tv"></i>
                        <span>TV a cabo</span>
                    </div>
                </div>
                
                <div class="room-description">
                    <p>Este quarto <?= htmlspecialchars($quarto['nome_categoria']) ?> é ideal para quem busca conforto e praticidade. Acomoda até <?= htmlspecialchars($quarto['Capacidade']) ?> pessoas e oferece todas as comodidades necessárias para uma estadia agradável no Rodeo Hotel.</p>
                </div>
            </div>
            
            <form id="form-reserva" class="booking-form">
                <button class="btn" type="submit">Reservar este quarto</button>
            </form>
        </div>
    </div>

    <?php if (count($outros_quartos) > 0): ?>
        <div class="similar-rooms">
            <h2 class="section-title">Outros quartos da mesma categoria</h2>
            <div class="rooms-grid">
                <?php foreach ($outros_quartos as $q): ?>
                    <div class="room-card">
                        <img src="../../uploads/<?= htmlspecialchars($q['Foto']) ?>" alt="<?= htmlspecialchars($q['nome_categoria']) ?>" class="room-card-img">
                        <div class="room-card-content">
                            <h3 class="room-card-title"><?= htmlspecialchars($q['nome_categoria']) ?></h3>
                            <div class="room-card-price">R$ <?= number_format($q['Preco_diaria'], 2, ',', '.') ?>/noite</div>
                            <a href="detalhes_quarto.php?id=<?= $q['ID_Quarto'] ?>" class="btn btn-secondary">Ver detalhes</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById("form-reserva").addEventListener("submit", function(e) {
    e.preventDefault();

    const confirmado = confirm("Você deseja fazer uma reserva para este quarto?");
    if (confirmado) {
        const quartoId = <?= $quarto['ID_Quarto'] ?>;
        window.location.href = "/HOTEL/actions/reservar_quarto.php?id=" + quartoId;
    }
});
</script>

<?php include '../../includes/rodape.php'; ?>

</body>
</html>