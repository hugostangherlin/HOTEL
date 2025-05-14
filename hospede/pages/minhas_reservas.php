<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado e se é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$nomeUsuario = $_SESSION['usuario']['nome'];
$usuarioId = $_SESSION['usuario']['ID'];

// Consulta para buscar as reservas do hóspede logado
$sql = "SELECT 
            r.ID_Reserva,
            r.Checkin,
            r.Checkout,
            q.ID_Quarto,
            q.Capacidade,
            q.Preco_diaria,
            c.Nome AS Categoria,
            p.Status AS Status_Pagamento
        FROM reserva r
        INNER JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
        INNER JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
        LEFT JOIN pagamentos p ON r.ID_Reserva = p.ID_Reserva
        WHERE r.usuarios_ID = :usuario_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
            max-width: 1200px;
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
        
        /* DataTables Custom Styling */
        #myTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 20px;
        }
        
        #myTable thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: none;
        }
        
        #myTable tbody tr {
            background-color: var(--white);
            transition: var(--transition);
            border-radius: var(--border-radius);
        }
        
        #myTable tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        #myTable tbody td {
            padding: 15px;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        #myTable tbody td:first-child {
            border-left: 1px solid #eee;
            border-top-left-radius: var(--border-radius);
            border-bottom-left-radius: var(--border-radius);
        }
        
        #myTable tbody td:last-child {
            border-right: 1px solid #eee;
            border-top-right-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
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
        
        .status-nao-registrado {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn-action {
            display: inline-block;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            margin: 2px;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #0069d9;
            transform: translateY(-1px);
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            margin: 0 3px;
            transition: var(--transition);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color) !important;
            color: white !important;
            border: none;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--light-gray) !important;
            border: none !important;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            #myTable thead {
                display: none;
            }
            
            #myTable tbody tr {
                display: block;
                margin-bottom: 15px;
                border-radius: var(--border-radius);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            #myTable tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 15px;
                border: none;
                border-bottom: 1px solid #eee;
            }
            
            #myTable tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--secondary-color);
                margin-right: 15px;
                flex: 1;
            }
            
            #myTable tbody td > span, 
            #myTable tbody td > div {
                flex: 2;
                text-align: right;
            }
            
            #myTable tbody td:first-child {
                border-top-left-radius: var(--border-radius);
                border-top-right-radius: var(--border-radius);
            }
            
            #myTable tbody td:last-child {
                border-bottom-left-radius: var(--border-radius);
                border-bottom-right-radius: var(--border-radius);
                border-bottom: none;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn-action {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Minhas Reservas</h1>
        </div>
        
        <!-- jQuery (full version) -->
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        
        <table id="myTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Quarto</th>
                    <th>Capacidade</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Preço Diária</th>
                    <th>Status Pagamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva): 
                    $statusClass = '';
                    $statusText = $reserva['Status_Pagamento'] ?? 'Não registrado';
                    
                    if (strtolower($statusText) === 'pago') {
                        $statusClass = 'status-pago';
                    } elseif (strtolower($statusText) === 'pendente') {
                        $statusClass = 'status-pendente';
                    } else {
                        $statusClass = 'status-nao-registrado';
                    }
                ?>
                    <tr>
                        <td data-label="Quarto"><?= htmlspecialchars($reserva['Categoria']) ?></td>
                        <td data-label="Capacidade"><?= htmlspecialchars($reserva['Capacidade']) ?> pessoas</td>
                        <td data-label="Check-in"><?= date('d/m/Y', strtotime($reserva['Checkin'])) ?></td>
                        <td data-label="Check-out"><?= date('d/m/Y', strtotime($reserva['Checkout'])) ?></td>
                        <td data-label="Preço Diária">R$ <?= number_format($reserva['Preco_diaria'], 2, ',', '.') ?></td>
                        <td data-label="Status Pagamento">
                            <span class="status <?= $statusClass ?>"><?= htmlspecialchars($statusText) ?></span>
                        </td>
                        <td data-label="Ações">
                            <div class="action-buttons">
                                <a href="avaliar_reserva.php?id=<?= $reserva['ID_Quarto'] ?>" class="btn-action btn-edit">
                                    <i class="fas fa-star"></i> Avaliar
                                </a>
                                <a href="deletar_reserva.php?id=<?= $reserva['ID_Reserva'] ?>" class="btn-action btn-delete" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                    <i class="fas fa-trash-alt"></i> Cancelar
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- DataTables Scripts -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#myTable').DataTable({
                    responsive: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                        search: "_INPUT_",
                        searchPlaceholder: "Pesquisar reservas...",
                    },
                    dom: '<"top"f>rt<"bottom"lip><"clear">',
                    initComplete: function() {
                        $('.dataTables_filter input').addClass('form-control');
                    }
                });
            });
        </script>
    </div>
</body>
</html>