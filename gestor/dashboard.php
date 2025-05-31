<?php
require_once '../Config/config.php';
require_once '../includes/header.php';

// --- Dados para gráfico reservas últimos 6 meses ---
$sql = "SELECT DATE_FORMAT(Checkin, '%Y-%m') AS mes, COUNT(*) AS total 
        FROM reserva 
        WHERE Checkin >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY mes
        ORDER BY mes";
$stmt = $pdo->query($sql);
$reservas_mes = [];
$labels = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $labels[] = $row['mes'];
    $reservas_mes[] = (int)$row['total'];
}

// --- Dados para gráfico ocupação ---
$sql2 = "SELECT 
          (SELECT COUNT(*) FROM quarto WHERE Status = 'ocupado') AS ocupados,
          (SELECT COUNT(*) FROM quarto) AS total";
$stmt2 = $pdo->query($sql2);
$ocupacao = $stmt2->fetch(PDO::FETCH_ASSOC);

// --- Últimas 10 reservas ---
$sql3 = "SELECT r.ID_Reserva, u.Nome AS Hospede, r.Checkin, r.Checkout, c.Nome AS Nome_Quarto
         FROM reserva r
         JOIN usuarios u ON r.usuarios_ID = u.ID
         JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
         JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
         ORDER BY r.ID_Reserva DESC LIMIT 10";
$stmt3 = $pdo->query($sql3);

$stmt3 = $pdo->query($sql3);
?>

<head>
    <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <style>
        /* Seu estilo atual, mantém o que você já tinha */

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary: #FB4D46;
            --primary-light: #FFE9E9;
            --dark: #1A1A2E;
            --light: #F8F9FA;
            --accent: #4A4E69;
            --text: #333333;
            --text-light: #6C757D;
        }

        body,
        .content-wrapper {
            background-color: var(--light) !important;
            color: var(--text);
        }

        .main-header {
            background-color: var(--dark) !important;
            border-bottom: none;
        }

        .main-sidebar {
            background-color: var(--dark) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar a,
        .nav-icon,
        .nav-link,
        .main-footer,
        .main-footer a {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .nav-link.active {
            background-color: var(--primary) !important;
            color: white !important;
            border-left: 3px solid white;
        }

        .nav-link:hover {
            background-color: rgba(251, 77, 70, 0.2) !important;
        }

        .btn-primary,
        .btn-block {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background-color: #E04141 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-outline-primary {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: transparent !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary) !important;
            color: white !important;
        }

        .content-header {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .content-header h3 {
            font-weight: 600;
            color: var(--dark);
            font-size: 1.5rem;
            margin: 0;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 500;
            color: var(--dark);
            border-radius: 8px 8px 0 0 !important;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: var(--dark);
            color: white;
            font-weight: 500;
            border: none;
        }

        .table td {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
        }

        .table-striped tbody tr:nth-of-type(odd) td {
            background-color: var(--primary-light);
        }

        .form-control,
        .custom-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(251, 77, 70, 0.15);
        }

        .main-footer {
            background-color: var(--dark) !important;
            padding: 15px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
#ocupacaoChart {
    max-width: 300px;  /* ou qualquer largura que quiser */
    max-height: 300px; /* altura proporcional */
    margin: 0 auto;    /* centralizar horizontalmente */
    display: block;
}

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px !important;
            }

            .card {
                margin-bottom: 15px;
            }

            .table-responsive {
                border-radius: 8px;
                overflow: hidden;
            }
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Conteúdo principal -->
        <div class="content-wrapper">

            <!-- Cabeçalho da página -->
            <section class="content-header">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><?= htmlspecialchars("$saudacao, $nome!") ?></h3>
                </div>
            </section>

            <!-- Conteúdo da página -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Botões principais -->
                    <div class="row text-center">
                        <div class="col-sm-6 col-md-3 mb-3">
                            <a href="../gestor/quartos/index.php" class="btn btn-block btn-primary">Gerenciar Quartos</a>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3">
                            <a href="../gestor/reservas/index.php" class="btn btn-block btn-primary">Gerenciar Reservas</a>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row">

                        <div class="col-md-6">
                            <div class="card">
                                <!-- Gerar relatório de últimas Reservas dos ultimos 6 meses -->
                                <a href="#"div class="card-header">Reservas nos últimos 6 meses</a>
                                <div class="card-body">
                                    <canvas id="reservasChart"></canvas>
                                </div>
                            </div>
                        </div>
<!-- Gerar relatório de Ocupação atual do Hotel-->
                        <div class="col-md-6">
                            <div class="card">
                                <a href="#" div class="card-header">Ocupação atual dos quartos</a>
                                <div class="card-body">
                                    <canvas id="ocupacaoChart" width="300" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Últimas reservas -->
                    <div class="card">
                        <div class="card-header">Últimas Reservas</div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Número da Reserva</th>
                                        <th>Nome do Quarto</th>
                                        <th>Hóspede</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['ID_Reserva']) ?></td>
                                            <td><?= htmlspecialchars($row['Nome_Quarto']) ?></td>
                                            <td><?= htmlspecialchars($row['Hospede']) ?></td>
                                            <td><?= htmlspecialchars($row['Checkin']) ?></td>
                                            <td><?= htmlspecialchars($row['Checkout']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </section>

        </div>

        <!-- Rodapé -->
        <?php include '../includes/footer.php'; ?>
    </div>

    <script>
        const ctxReservas = document.getElementById('reservasChart').getContext('2d');
        const reservasChart = new Chart(ctxReservas, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Reservas',
                    data: <?= json_encode($reservas_mes) ?>,
                    backgroundColor: 'rgba(251, 77, 70, 0.7)',
                    borderColor: 'rgba(251, 77, 70, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });

        const ctxOcupacao = document.getElementById('ocupacaoChart').getContext('2d');
        const ocupacaoChart = new Chart(ctxOcupacao, {
            type: 'doughnut',
            data: {
                labels: ['Ocupados', 'Disponíveis'],
                datasets: [{
                    data: [<?= (int)$ocupacao['ocupados'] ?>, <?= (int)$ocupacao['total'] - (int)$ocupacao['ocupados'] ?>],
                    backgroundColor: [
                        'rgba(251, 77, 70, 0.7)',
                        'rgba(74, 78, 105, 0.7)'
                    ],
                    borderColor: [
                        'rgba(251, 77, 70, 1)',
                        'rgba(74, 78, 105, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>