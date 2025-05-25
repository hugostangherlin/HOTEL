<?php
require_once '../Config/config.php';
require_once '../includes/header.php';
?>
<link rel="stylesheet" href="../assets/css/dashboard.css">
<head>
    <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <style>
/* =============================== */
/*      Importação da fonte Inter  */
/* =============================== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap');

/* =============================== */
/*         Reset e Fonte Global    */
/* =============================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

/* =============================== */
/*      Esquema de Cores           */
/* =============================== */
:root {
    --primary: #FB4D46;       /* Vermelho Rodeo - uso estratégico */
    --primary-light: #FFE9E9; /* Vermelho claro para fundos */
    --dark: #1A1A2E;          /* Azul escuro sofisticado */
    --light: #F8F9FA;         /* Fundo claro */
    --accent: #4A4E69;        /* Cinza azulado para elementos */
    --text: #333333;          /* Texto principal */
    --text-light: #6C757D;    /* Texto secundário */
}

/* =============================== */
/*      Estrutura Principal        */
/* =============================== */
body, .content-wrapper {
    background-color: var(--light) !important;
    color: var(--text);
}

/* =============================== */
/*      Barra Superior e Sidebar   */
/* =============================== */
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

/* =============================== */
/*      Botões e Ações             */
/* =============================== */
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

/* =============================== */
/*      Cabeçalhos e Títulos       */
/* =============================== */
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

/* =============================== */
/*      Cards e Elementos UI       */
/* =============================== */
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

/* =============================== */
/*      Tabelas                    */
/* =============================== */
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

/* =============================== */
/*      Formulários                */
/* =============================== */
.form-control, .custom-select {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.form-control:focus, .custom-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(251, 77, 70, 0.15);
}

/* =============================== */
/*      Rodapé                     */
/* =============================== */
.main-footer {
    background-color: var(--dark) !important;
    padding: 15px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

/* =============================== */
/*      Responsividade             */
/* =============================== */
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


