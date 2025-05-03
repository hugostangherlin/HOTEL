<?php 
// Aqui, caso a sessão ainda não esteja iniciada ele inicia
if (session_status() === PHP_SESSION_NONE) session_start();
// No arquivo dentro da pasta "gestor" ou "hospede"
require '../Config/config.php';


?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Botão sidebar Para responsividade. Importante !!! -->
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
                <span class="d-none d-md-inline ml-1">Menu</span>
            </a>
        </li>
    </ul>

    <!-- icone que fica o usuário que está logado a direita -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" href="../gestor/telas/exibir_gestor.php">
                <i class="fas fa-user-circle mr-1"></i>
                <span class="d-none d-md-inline">
                    <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?></strong>
                    <div class="text-muted small">
                        <?= ucfirst($_SESSION['usuario']['perfil'] ?? 'Perfil') ?>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sair
                </a>
            </div>
        </li>
    </ul>
</nav>
