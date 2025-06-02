<?php
require __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodeo Hotel</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="rodeo.ico">
    <link rel="manifest" href="/site.webmanifest">

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
        }

        .hero-carousel {
            position: relative;
        }

        .hero-carousel .carousel-item {
            height: 100vh;
            min-height: 600px;
        }

        .hero-carousel .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .hero-carousel .carousel-caption {
            bottom: 50%;
            transform: translateY(50%);
            text-align: center;
            width: 100%;
            left: 0;
            right: 0;
        }

        .hero-carousel .carousel-caption h1 {
            font-size: 3.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
        }

        .hero-carousel .carousel-caption p {
            font-size: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Override Bootstrap's default indicator styles */
        .hero-carousel .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-carousel .carousel-caption h1 {
                font-size: 2.5rem;
            }

            .hero-carousel .carousel-caption p {
                font-size: 1.2rem;
            }

            .hero-carousel .carousel-item {
                min-height: 500px;
            }
        }

        .navbar-custom {
            background-color: var(--primary-color) !important;
            min-height: 80px;
            /* Altura mínima garantida */
            padding: 5px 0 !important;
        }

        .navbar-brand img {
            height: 55px;
            /* Altura ideal para 665×375px */
            width: auto;
            transition: all 0.3s;
        }

        .search-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .room-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 30px;
        }

        .room-card:hover {
            transform: translateY(-10px);
        }

        .room-img {
            height: 250px;
            object-fit: cover;
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

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
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

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
        }

        .btn-primary-custom:hover {
            background-color: #e0413a;
            border-color: #e0413a;
        }

        .feature-box {
            text-align: center;
            padding: 30px 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#" style="display: flex; align-items: center;">
                <img src="/HOTEL/assets/img/Rodeo-removebg-preview.png"
                    alt="Rodeo Hotel"
                    style="height: 55px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobre.html">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="entrar.php"><i class="fas fa-sign-in-alt me-1"></i> Entrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section as Carousel -->
    <section class="hero-carousel">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="/HOTEL/assets/img/carousel-1.jpg" class="d-block w-100" alt="Hotel Rodeo">
                    <div class="carousel-caption">
                        <h1 class="display-4 fw-bold">Bem-vindo ao Rodeo Hotel</h1>
                        <p class="lead">Experimente o luxo e conforto em nossa hospedagem</p>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="/HOTEL/assets/img/carousel-2.jpg" class="d-block w-100" alt="Quartos Rodeo">
                    <div class="carousel-caption">
                        <h1 class="display-4 fw-bold">Bem-vindo ao Rodeo Hotel</h1>
                        <p class="lead">Conforto e elegância em cada detalhe</p>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
    </section>
    <!-- Search Box -->
    <div class="container">
        <div class="search-box">
            <form id="form-busca" class="row g-3">
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
        document.getElementById('form-busca').addEventListener('submit', async function(e) {
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

    <!-- Quartos em Destaque -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Nossos Quartos</h2>

            <div class="row">
                <?php
                $stmt = $pdo->query("SELECT quarto.*, categoria.Nome AS NomeCategoria 
                                    FROM quarto 
                                    JOIN categoria ON quarto.Categoria_ID_Categoria = categoria.ID_Categoria 
                                    LIMIT 3");
                while ($quarto = $stmt->fetch()):
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="room-card">
                            <div class="position-relative">
                                <?php if (!empty($quarto['Foto'])): ?>
                                    <img src="/HOTEL/uploads/<?= $quarto['Foto'] ?>" class="img-fluid room-img" alt="<?= $quarto['NomeCategoria'] ?>">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/600x400?text=Rodeo+Hotel" class="img-fluid room-img" alt="Quarto">
                                <?php endif; ?>
                                <div class="price-tag">R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?>/noite</div>
                            </div>
                            <div class="p-4">
                                <h4><?= $quarto['NomeCategoria'] ?></h4>
                                <div class="d-flex mb-3">
                                    <small class="me-3"><i class="fas fa-bed me-2 text-primary"></i> <?= $quarto['Capacidade'] ?> Pessoa(s)</small>
                                </div>
                                <a href="/HOTEL/hospede/pages/detalhes_quarto.php?id=<?= $quarto['ID_Quarto'] ?>" class="btn btn-primary-custom w-100">Reservar Agora</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Nossas Avaliações</h2>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="testimonial-item text-center p-4">
                                    <div class="mb-4">
                                        <i class="fas fa-quote-left fa-2x text-primary"></i>
                                    </div>
                                    <p class="mb-4">"A experiência no Rodeo Hotel foi excepcional. O atendimento impecável e os quartos luxuosos superaram todas as expectativas."</p>
                                    <div class="d-flex justify-content-center">
                                        <div class="text-start">
                                            <h5 class="mb-0">Ana Silva</h5>
                                            <small>Hóspede</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="testimonial-item text-center p-4">
                                    <div class="mb-4">
                                        <i class="fas fa-quote-left fa-2x text-primary"></i>
                                    </div>
                                    <p class="mb-4">"Muito bom"</p>
                                    <div class="d-flex justify-content-center">
                                        <div class="text-start">
                                            <h5 class="mb-0">Carlos Mendes</h5>
                                            <small>Hóspede</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

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


            <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">

            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">&copy; <?= date('Y') ?> Rodeo Hotel. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Ativar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

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