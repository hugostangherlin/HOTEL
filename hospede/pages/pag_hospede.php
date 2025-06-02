<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$nomeUsuario = $_SESSION['usuario']['nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodeo Hotel</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Sweet Alert2 -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        color: #333;
    }

    /* Navbar */
    .navbar {
        background-color: var(--primary-color) !important;
        min-height: 80px;
        padding: 5px 0 !important;
    }

    .navbar-brand img {
        height: 55px;
        width: auto;
        transition: all 0.3s;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 8px 15px;
        transition: all 0.3s;
    }

    .dropdown-item:hover {
        background-color: rgba(251, 77, 70, 0.1);
        color: var(--primary-color);
    }

    /* Header do Hóspede */
    .user-header {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/HOTEL/assets/img/carousel-1.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 80px 0;
        text-align: center;
        margin-bottom: 30px;
    }

    .user-welcome {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    /* Card Dashboard */
    .card-dashboard {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border: none;
    }

    .card-body {
        padding: 30px;
    }

    /* Search Box */
    .search-box {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    /* Section Titles */
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 30px;
        font-weight: 600;
        color: var(--secondary-color);
    }

    .section-title:after {
        content: '';
        position: absolute;
        width: 50px;
        height: 3px;
        background-color: var(--primary-color);
        bottom: -10px;
        left: 0;
    }

    /* Buttons */
    .btn-primary-custom {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 10px 25px;
        color: white;
        font-weight: 500;
    }

    .btn-primary-custom:hover {
        background-color: #e0413a;
        border-color: #e0413a;
        color: white;
    }

    /* Room Cards */
    .room-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        margin-bottom: 30px;
        background: white;
    }

    .room-card:hover {
        transform: translateY(-10px);
    }

    .room-img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .price-tag {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background-color: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 5px;
        font-weight: 600;
    }

    /* Table Styles */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
        color: var(--secondary-color);
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
          footer {
            background-color: var(--dark-color);
            color: white;
            padding: 50px 0 0;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            transform: translateY(-5px);
        }

    /* Alert Styles */
    .alert {
        border-radius: 8px;
        padding: 15px 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .user-welcome {
            font-size: 2rem;
        }
        
        .search-box {
            padding: 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    /* Action Buttons */
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 4px;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* Form Controls */
    .form-control, .form-select {
        padding: 10px 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(251, 77, 70, 0.25);
    }

    /* Main Content */
    main {
        padding: 30px 0;
    }

    /* Responsive Grid */
    .row {
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-lg-9 {
        padding-right: 15px;
        padding-left: 15px;
    }
</style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/HOTEL/assets/img/Rodeo-removebg-preview.png" alt="Rodeo Hotel" height="40">
            </a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Olá,  <?= htmlspecialchars($nomeUsuario) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="exibir_hospede.php"><i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/HOTEL/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Script do Dropdown -->
    <script>
        const toggle = document.getElementById('dropdownToggle');
        const menu = document.getElementById('dropdownMenu');

        toggle.addEventListener('click', () => {
            menu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('userDropdown').contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>


    <!-- Header do Hóspede -->
    <header class="user-header">
        <div class="container">
            <h1 class="user-welcome">Bem-vindo, <?= htmlspecialchars($nomeUsuario) ?></h1>
            <p class="lead">Sua experiência conosco é nossa maior prioridade</p>
        </div>
    </header>
            
            <!-- Conteúdo Principal -->
            <div class="col-lg-9">
                <div class="card-dashboard">
                    <div class="card-body">
                        <h3 class="section-title">Buscar Quartos</h3>
                        
    <div class="container">
        <div class="search-box">
            <form id="form-busca"class="row g-3">
                <div class="col-md-3">
                    <label for="checkin" class="form-label">Check-in</label>
                    <input type="date" class="form-control" id="checkin" name="checkin" required>
                </div>
                <div class="col-md-3">
                    <label for="checkout" class="form-label">Check-out</label>
                    <input type="date" class="form-control" id="checkout" name="checkout" required>
                </div>
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" id="categoria" name="categoria" required>
                        <option value="" selected disabled>Selecione</option>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM categoria");
                        while ($cat = $stmt->fetch()) {
                            echo "<option value='{$cat['ID_Categoria']}'>{$cat['Nome']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-search me-2"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
 <!-- Resultados da Busca -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title"></h2>
        <div class="row" id="resultado-quartos">
            <!-- Os resultados serão carregados aqui via AJAX -->
        </div>
    </div>
</section>
<!-- Script AJAX para buscar quartos -->
<script>
document.getElementById('form-busca').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/HOTEL/includes/resultado_busca.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Erro na requisição');
        }

        const html = await response.text();
        document.getElementById('resultado-quartos').innerHTML = html;
    } catch (error) {
        console.error('Erro:', error);
        document.getElementById('resultado-quartos').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">Ocorreu um erro ao buscar os quartos. Por favor, tente novamente.</div>
            </div>
        `;
    }
});

</script>
<!-- Reservas Recentes (modificado para exibir todas) -->
<div class="card-dashboard mt-4">
    <div class="card-body">
        <h3 class="section-title">Minhas Reservas</h3>

        <?php
        $stmt = $pdo->prepare("
            SELECT 
                r.ID_Reserva,
                r.Checkin,
                r.Checkout,
                q.ID_Quarto,
                q.Capacidade,
                q.Preco_diaria,
                c.Nome AS Categoria,
                a.ID_Avaliacao
            FROM reserva r
            INNER JOIN quarto q ON r.Quarto_ID_Quarto = q.ID_Quarto
            INNER JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
            LEFT JOIN avaliacao a ON r.ID_Reserva = a.ID_Reserva
            WHERE r.usuarios_ID = ?
            ORDER BY r.Checkin DESC
        ");
        $stmt->execute([$_SESSION['usuario']['ID']]);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if ($reservas): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quarto</th>
                            <th>Capacidade</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Preço Diária</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['Categoria']) ?></td>
                                <td><?= htmlspecialchars($reserva['Capacidade']) ?> pessoas</td>
                                <td><?= date('d/m/Y', strtotime($reserva['Checkin'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($reserva['Checkout'])) ?></td>
                                <td>R$ <?= number_format($reserva['Preco_diaria'], 2, ',', '.') ?></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <?php if (!$reserva['ID_Avaliacao']): ?>
                                            <a href="avaliar_reserva.php?id=<?= $reserva['ID_Reserva'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-star"></i> Avaliar
                                            </a>
                                        <?php else: ?>
                                            <a href="ver_avaliacao.php?id=<?= $reserva['ID_Reserva'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-pen"></i> Minha Avaliação
                                            </a>
                                        <?php endif; ?>
<a href="deletar_reserva.php?id=<?= $reserva['ID_Reserva'] ?>" class="btn btn-sm btn-danger" onclick="return confirmarExclusao(event)">
    <i class="fas fa-trash-alt"></i> Cancelar
</a>

<script>
function confirmarExclusao(event) {
    event.preventDefault(); // Evita que o link seja seguido imediatamente
    const linkExclusao = event.currentTarget.getAttribute('href');

    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação cancelará a reserva permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, cancelar!',
        cancelButtonText: 'Não, voltar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = linkExclusao; // Redireciona para deletar_reserva.php
        }
    });
}
</script>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Você ainda não possui reservas.</div>
        <?php endif; ?>
    </div>
</div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="pt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="/HOTEL/assets/img/Rodeo-removebg-preview.png" alt="Rodeo Hotel" class="mb-3" width="180">
                    <p class="text-muted">O Rodeo Hotel oferece a melhor experiência em hospedagem de luxo, combinando conforto, elegância e atendimento personalizado.</p>
                    <div class="mt-4">
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-4">Links Rápidos</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="/home.php">Home</a></li>
                        <li class="mb-2"><a href="sobre.html">Sobre Nós</a></li>
                        <li class="mb-2"><a href="#">Contato</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h5 class="text-white mb-4">Contato</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> EQNN 14 Área Especial S/ Nº- Ceilândia Sul - DF</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> (61) 94002-8922</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> rodeohotel@gmail.com</li>
                    </ul>
                </div>
            </div>

            <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">

            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">&copy; 2023 Rodeo Hotel. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Configurar datas mínimas para check-in/out
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkin').min = today;
            
            document.getElementById('checkin').addEventListener('change', function() {
                const checkinDate = this.value;
                document.getElementById('checkout').min = checkinDate;
                if (document.getElementById('checkout').value < checkinDate) {
                    document.getElementById('checkout').value = '';
                }
            });
        });
    </script>
</body>
</html>