<?php
// Conexão com o banco de dados (ajuste conforme necessário)
require_once '../../config/config.php';

// Consulta para pegar os detalhes do quarto
$query = "SELECT q.ID_Quarto, q.Status, q.Capacidade, q.Foto, q.Preco_diaria, c.Nome AS Categoria,
                 r.ID_Reserva, r.checkin, r.checkout, u.Nome AS Usuario_Nome, u.Email AS Usuario_Email
          FROM quarto q
          JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
          LEFT JOIN reserva r ON r.Quarto_ID_Quarto = q.ID_Quarto
          LEFT JOIN usuarios u ON r.usuarios_ID = u.ID
          WHERE q.ID_Quarto = :id_quarto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_quarto', $_GET['id']);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<?php
require_once '../../config/config.php';

// [Previous PHP code remains the same until the HTML section]
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Quarto | Rodeo Hotel</title>
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
            max-width: 1000px;
            margin: 30px auto;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .page-title {
            color: var(--secondary-color);
            font-size: 28px;
            margin: 0;
        }
        
        .room-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .details-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-value {
            padding: 10px 15px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            border-left: 3px solid var(--primary-color);
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .status-disponivel {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-ocupado {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-top: 20px;
        }
        
        .back-btn:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Detalhes do Quarto</h1>
        </div>
        
        <?php if ($row): ?>
            <!-- Imagem do quarto -->
            <?php if(!empty($row['Foto'])): ?>
                <img src="../../uploads/<?= htmlspecialchars($row['Foto']) ?>" alt="Foto do Quarto" class="room-image">
            <?php endif; ?>
            
            <div class="details-section">
                <h2 class="section-title">Informações Básicas</h2>
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-hashtag"></i>
                            Código do Quarto
                        </div>
                        <div class="detail-value"><?= $row['ID_Quarto'] ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-info-circle"></i>
                            Status
                        </div>
                        <div class="detail-value">
                            <span class="status <?= ($row['ID_Reserva'] !== null) ? 'status-ocupado' : 'status-disponivel' ?>">
                                <?= ($row['ID_Reserva'] !== null) ? 'Ocupado' : 'Disponível' ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-users"></i>
                            Capacidade
                        </div>
                        <div class="detail-value"><?= $row['Capacidade'] ?> pessoas</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-tag"></i>
                            Categoria
                        </div>
                        <div class="detail-value"><?= $row['Categoria'] ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Preço por Diária
                        </div>
                        <div class="detail-value">R$ <?= number_format($row['Preco_diaria'], 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            
            <?php if($row['ID_Reserva'] !== null): ?>
                <div class="details-section">
                    <h2 class="section-title">Informações de Reserva</h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-hashtag"></i>
                                ID da Reserva
                            </div>
                            <div class="detail-value"><?= $row['ID_Reserva'] ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-calendar-check"></i>
                                Check-in
                            </div>
                            <div class="detail-value"><?= date('d \d\e F \d\e Y', strtotime($row['checkin'])) ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-calendar-times"></i>
                                Check-out
                            </div>
                            <div class="detail-value"><?= date('d \d\e F \d\e Y', strtotime($row['checkout'])) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="details-section">
                    <h2 class="section-title">Informações do Hóspede</h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-user"></i>
                                Nome
                            </div>
                            <div class="detail-value"><?= $row['Usuario_Nome'] ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-envelope"></i>
                                E-mail
                            </div>
                            <div class="detail-value"><?= $row['Usuario_Email'] ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <a href="javascript:history.back()" class="back-btn">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        <?php else: ?>
            <div class="detail-value" style="text-align: center; padding: 20px;">
                Nenhum detalhe encontrado para este quarto.
            </div>
            <a href="javascript:history.back()" class="back-btn" style="display: block; text-align: center;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        <?php endif; ?>
    </div>
</body>
</html>