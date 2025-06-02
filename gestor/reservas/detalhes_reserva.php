<?php
session_start();
require_once '../../config/config.php';

// Verifique se o ID da reserva foi passado corretamente na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID da reserva não informado.";
    exit();
}

$id_reserva = $_GET['id'];

// Consulta para pegar os dados de pagamento
$sql = "SELECT p.ID_Pagamento, p.Forma_Pagamento, p.Status, p.Valor, p.Data_Pagamento, u.Nome
        FROM pagamentos p
        JOIN usuarios u ON p.ID_Usuarios = u.ID
        WHERE p.ID_Reserva = :id_reserva";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_reserva', $id_reserva);
$stmt->execute();
$pagamento = $stmt->fetch();

if (!$pagamento) {
    echo "Pagamento não encontrado para a reserva.";
    exit();
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pagamento | Rodeo Hotel</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
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
            margin-bottom: 10px;
        }
        
        .reservation-id {
            color: var(--dark-gray);
            font-size: 16px;
        }
        
        .payment-details {
            margin-top: 30px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .detail-item {
            margin-bottom: 20px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-value {
            padding: 12px 15px;
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
        
        .status-pago {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pendente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-cancelado {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 18px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-top: 30px;
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
            <h1 class="page-title">Detalhes do Pagamento</h1>
            <p class="reservation-id">Reserva #<?= htmlspecialchars($_GET['id']) ?></p>
        </div>
        
        <?php if ($pagamento): ?>
            <div class="payment-details">
                <h2 class="section-title">Informações do Pagamento</h2>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-hashtag"></i>
                            ID do Pagamento
                        </div>
                        <div class="detail-value"><?= $pagamento['ID_Pagamento'] ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-user"></i>
                            Cliente
                        </div>
                        <div class="detail-value"><?= htmlspecialchars($pagamento['Nome']) ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-credit-card"></i>
                            Forma de Pagamento
                        </div>
                        <div class="detail-value"><?= htmlspecialchars($pagamento['Forma_Pagamento']) ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-info-circle"></i>
                            Status
                        </div>
                        <div class="detail-value">
                            <?php
                            $statusClass = '';
                            switch(strtolower($pagamento['Status'])) {
                                case 'pago':
                                    $statusClass = 'status-pago';
                                    break;
                                case 'pendente':
                                    $statusClass = 'status-pendente';
                                    break;
                                case 'cancelado':
                                    $statusClass = 'status-cancelado';
                                    break;
                                default:
                                    $statusClass = '';
                            }
                            ?>
                            <span class="status <?= $statusClass ?>"><?= htmlspecialchars($pagamento['Status']) ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Valor
                        </div>
                        <div class="detail-value price">R$ <?= number_format($pagamento['Valor'], 2, ',', '.') ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i>
                            Data do Pagamento
                        </div>
                        <div class="detail-value"><?= date('d/m/Y H:i:s', strtotime($pagamento['Data_Pagamento'])) ?></div>
                    </div>
                </div>
                
                <a href="javascript:history.back()" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        <?php else: ?>
            <div class="detail-value" style="text-align: center; padding: 20px;">
                Pagamento não encontrado para esta reserva.
            </div>
            <a href="javascript:history.back()" class="back-btn" style="display: block; text-align: center;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        <?php endif; ?>
    </div>
</body>
</html>
