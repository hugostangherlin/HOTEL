<?php
require_once '../Config/config.php';
?>
<link rel="stylesheet" href="../assests/css/dashboard.css">
<head>
    <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
     <!-- CDN bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- AdminLTE CDN-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- jQuery e Bootstrap (necessários para AdminLTE) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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


