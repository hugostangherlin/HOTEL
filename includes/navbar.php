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

 <!-- Menu do usuário (lado direito) -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <div class="user-menu">
                <div class="user-dropdown" id="userDropdown">
                    <a href="#" class="username nav-link d-flex align-items-center" id="dropdownToggle">
                        <i class="fas fa-user-circle mr-1"></i>
                        <span><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?></span>
                    </a>
                    <div class="dropdown-menu" id="dropdownMenu" style="display: none; position: absolute; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.15); padding: 10px; border-radius: 5px;">
                        <a class="dropdown-item" href="../gestor/telas/exibir_gestor.php">Meu Perfil</a>
                        <a class="dropdown-item" href="/HOTEL/logout.php">Sair</a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
<script>
    const toggle = document.getElementById('dropdownToggle');
    const menu = document.getElementById('dropdownMenu');

    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('userDropdown').contains(e.target)) {
            menu.style.display = 'none';
        }
    });
</script>
