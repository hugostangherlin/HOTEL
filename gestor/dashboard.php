<?php
require_once '../Config/config.php';
require_once '../includes/header.php';
?>
<link rel="stylesheet" href="../assests/css/dashboard.css">
<head>
    <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
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
                <h3 class="mb-0"><?= "$saudacao, $nome!" ?></h3>
                <form action="/HOTEL/logout.php" method="post">
                    <button type="submit" class="btn btn-danger">Sair</button>
                </form>
            </div>
        </section>

        <!-- Conteúdo da página -->
        <section class="content">
    <div class="container-fluid">
        <!-- Dashboard com botões estilizados -->
        <div class="row text-center">

            <div class="col-sm-6 col-md-3 mb-3">
                <a href="../gestor/quartos/index.php" class="btn btn-block custom-btn">Gerenciar Quartos</a>
                
            </div>

            <div class="col-sm-6 col-md-3 mb-3">
                <a href="../gestor/reservas/index.php" class="btn btn-block custom-btn">Gerenciar Reservas</a>
            </div>

        </div>
    </div>
</section>
                <!-- Link para perfil -->
                <!-- <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="exibir_gestor.php" class="btn btn-link">Meu Perfil</a>
                    </div>
                </div> -->

            </div>
        </section>
    </div>

    <!-- Rodapé -->
    <?php include '../includes/footer.php'; ?>
</div>
</body>
</html>


