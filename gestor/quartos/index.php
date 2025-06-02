<?php
require_once '../../config/config.php';

// Array vazio para armazenar os dados
$lista = [];

// Consulta SQL com JOIN para buscar dados da tabela quartos e consultar tabela categoria
$sql = $pdo->query("
    SELECT q.*, c.Nome as CategoriaNome,
    (
        SELECT COUNT(*) FROM reserva r
        WHERE r.Quarto_ID_Quarto = q.ID_QUARTO
        AND CURRENT_TIMESTAMP BETWEEN r.Checkin AND r.Checkout
    ) as Ocupado
    FROM Quarto q
    INNER JOIN Categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
");
// Retorno da consulta
if ($sql->rowCount() > 0) {
    // Armazena os dados em um array associativo
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Quartos | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
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
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-title {
            color: var(--secondary-color);
            font-size: 28px;
            margin: 0;
        }
        
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-add:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        
        .room-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        
        .status-manutencao {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            text-align: center;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #0069d9;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .btn-more {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-more:hover {
            background-color: #1a252f;
        }
        
        .price {
            font-weight: 600;
            color: var(--primary-color);
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
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: flex-end;
            }
            
            .btn-action {
                padding: 6px 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Painel de Gerenciamento de Quartos</h1>
            <a href="adicionar_quarto.php" class="btn-add">
                <i class="fas fa-plus"></i> Adicionar Quarto
            </a>
        </div>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <table id="myTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Número</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Capacidade</th>
                    <th>Diária</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista as $quarto): 
                    $statusClass = '';
                    $statusText = '';
                    if ($quarto['Status'] === 'Manutencao') {
                        $statusClass = 'status-manutencao';
                        $statusText = 'Manutenção';
                    } elseif ($quarto['Status'] ==='Ocupado') {
                        $statusClass = 'status-ocupado';
                        $statusText = 'Ocupado';
                    } else {
                        $statusClass = 'status-disponivel';
                        $statusText = 'Disponível';
                    }
                ?>
                    <tr>
                        <td data-label="Foto">
                            <?php if (!empty($quarto['Foto'])): ?>
    <img src="../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>" alt="Foto do Quarto" class="room-image">
<?php else: ?>
    <img src="../../assets/img/sem-imagem.jpg" alt="Sem imagem" class="room-image">
<?php endif; ?>

                        </td>
                        <td data-label="Número"><?= $quarto['ID_Quarto'] ?></td>
                        <td data-label="Categoria"><?= $quarto['CategoriaNome'] ?></td>
                        <td data-label="Status">
                            <span class="status <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td data-label="Capacidade"><?= $quarto['Capacidade'] ?> pessoas</td>
                        <td data-label="Diária" class="price">R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></td>
                        <td data-label="Ações">
                            <div class="action-buttons">
                                <a href="editar_quarto.php?id=<?= $quarto['ID_Quarto'] ?>" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="../../actions/excluir_quarto.php?id=<?= $quarto['ID_Quarto'] ?>" class="btn-action btn-delete" onclick="return confirm('Tem certeza que deseja excluir este quarto?')">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                                <a href="maisdetalhes.php?id=<?= $quarto['ID_Quarto'] ?>" class="btn-action btn-more">
                                    <i class="fas fa-info-circle"></i> Detalhes
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- DataTables Script -->
        <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#myTable').DataTable({
                    responsive: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                        search: "_INPUT_",
                        searchPlaceholder: "Pesquisar quartos...",
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