<?php
session_start();
require_once '../../config/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : null;
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : null;

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
    <title><?= htmlspecialchars($quarto['nome_categoria']) ?> - Detalhes do Quarto</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--secondary-color);
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 15px;
        }
        
        .room-header {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 50px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .room-image {
            flex: 1 1 400px;
            min-height: 400px;
            background-size: cover;
            background-position: center;
        }
        
        .room-content {
            flex: 1 1 400px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .room-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .room-price {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 25px;
        }
        
        .room-price small {
            font-size: 1rem;
            color: var(--secondary-color);
            opacity: 0.8;
        }
        
        .room-features {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        
        .feature i {
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .room-description {
            margin: 25px 0;
            line-height: 1.6;
        }
        
        .booking-form {
            margin-top: 30px;
        }
        
        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        .section-title {
            position: relative;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .similar-rooms {
            margin-top: 60px;
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .room-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
        }
        
        .room-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .room-card-content {
            padding: 20px;
        }
        
        .room-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .room-card-price {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #1a252f;
        }
        
        @media (max-width: 768px) {
            .room-header {
                flex-direction: column;
            }
            
            .room-image {
                min-height: 250px;
            }
            
            .room-title {
                font-size: 1.8rem;
            }
            
            .room-price {
                font-size: 1.5rem;
            }
            
            .rooms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="main-container">
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
                            <i class="fas fa-bed"></i>
                            <span>2 camas</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-bath"></i>
                            <span>1 banheiro</span>
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
                <h2 class="section-title">Outros Quartos</h2>
                <div class="rooms-grid">
                    <?php foreach ($outros_quartos as $q): ?>
                        <div class="room-card">
                            <img src="../../uploads/<?= htmlspecialchars($q['Foto']) ?>" alt="<?= htmlspecialchars($q['nome_categoria']) ?>" class="room-card-img">
                            <div class="room-card-content">
                                <h3 class="room-card-title"><?= htmlspecialchars($q['nome_categoria']) ?></h3>
                                <div class="room-card-price">R$ <?= number_format($q['Preco_diaria'], 2, ',', '.') ?>/noite</div>
                                <a href="detalhes_quarto.php?id=<?= $q['ID_Quarto'] ?><?= $checkin ? '&checkin='.$checkin : '' ?><?= $checkout ? '&checkout='.$checkout : '' ?>" class="btn btn-secondary">Ver detalhes</a>
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
    
    Swal.fire({
        title: 'Confirmar Reserva',
        html: `Você deseja reservar o quarto <b>${<?= json_encode($quarto['nome_categoria']) ?>}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FB4D46',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, reservar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const quartoId = <?= $quarto['ID_Quarto'] ?>;
            const checkin = "<?= $checkin ?>";
            const checkout = "<?= $checkout ?>";
            window.location.href = "/HOTEL/actions/reservar_quarto.php?id=" + quartoId + "&checkin=" + checkin + "&checkout=" + checkout;
        }
    });
});
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>