<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 1) {
    header("Location: ../entrar.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Exclusão de Reservas - Painel Administrativo</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --text-color: #333;
            --text-light: #7f8c8d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: #f5f6fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        h2 i {
            color: var(--accent-color);
        }
        
        .no-requests {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 18px;
            background-color: #f9f9f9;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        }
        
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-approve {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-approve:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
        }
        
        .reservation-id {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .guest-name {
            font-weight: 500;
        }
        
        .dates {
            font-family: monospace;
            font-size: 13px;
            color: var(--text-light);
        }
        
        .room-info {
            display: flex;
            flex-direction: column;
        }
        
        .room-category {
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .room-id {
            font-size: 12px;
            color: var(--text-light);
        }
        
        .request-date {
            font-size: 13px;
            color: var(--text-light);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #f1c40f20;
            color: #f39c12;
            border: 1px solid #f1c40f;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            th, td {
                padding: 10px 12px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 6px;
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
        <h2> Solicitações de Exclusão de Reservas</h2>

        <?php
        $sql = $pdo->query("
            SELECT r.ID_Reserva, r.Checkin, r.Checkout, r.Data_Solicitacao_Exclusao,
                   u.nome AS hospede, q.ID_Quarto, c.Nome AS categoria
            FROM reserva r
            JOIN usuarios u ON r.usuarios_ID = u.ID
            JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
            JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
            WHERE r.solicitou_exclusao = 1
        ");

        if ($sql->rowCount() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Hóspede</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Quarto</th>
                        <th>Data da Solicitação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reserva = $sql->fetch()): ?>
                        <tr>
                            <td class="reservation-id">#<?= $reserva['ID_Reserva'] ?></td>
                            <td class="guest-name"><?= htmlspecialchars($reserva['hospede']) ?></td>
                            <td class="dates"><?= date('d/m/Y', strtotime($reserva['Checkin'])) ?></td>
                            <td class="dates"><?= date('d/m/Y', strtotime($reserva['Checkout'])) ?></td>
                            <td>
                                <div class="room-info">
                                    <span class="room-category"><?= htmlspecialchars($reserva['categoria']) ?></span>
                                    <span class="room-id">ID <?= $reserva['ID_Quarto'] ?></span>
                                </div>
                            </td>
                            <td class="request-date"><?= date('d/m/Y H:i', strtotime($reserva['Data_Solicitacao_Exclusao'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/HOTEL/actions/excluir_reserva.php?id=<?= $reserva['ID_Reserva'] ?>" 
                                       class="btn btn-approve"
                                       onclick="return confirm('Tem certeza que deseja aprovar a exclusão desta reserva?')">
                                        <i class="fas fa-check-circle"></i> Aprovar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-requests">
                <p><i class="fas fa-calendar-check"></i> Nenhuma solicitação de exclusão de reserva pendente.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Adiciona confirmação antes de excluir
        document.querySelectorAll('.btn-approve').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja aprovar a exclusão desta reserva?\nEsta ação não pode ser desfeita.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>