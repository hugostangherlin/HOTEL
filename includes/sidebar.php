<aside class="main-sidebar sidebar-dark-purple elevation-4" style="background-color:rgb(32, 31, 32);">

    <div class="brand-link d-flex justify-content-between align-items-center">
        <a href="/entrar.php" class="brand-text font-weight-light" style="color: white !important; margin-left: 15px;">
            Rodeo Hotel
        </a>
    </div>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php if ($_SESSION['usuario']['perfil'] == 1): ?>
                    <li class="nav-item">
                        <a href="/HOTEL/form/cadastro_gestor.php" class="nav-link">
                            <i class="nav-icon fas fa-user-plus text-white"></i>
                            <p>Cadastro de Funcionários</p>
                        </a>
                    </li>

                    <!-- Solicitação de Exclusão -->
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-trash"></i>
                            <p>
                                Solicitação de Exclusões
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <li class="nav-item">
                                <a href="/HOTEL/gestor/telas/solicitacao.php" class="nav-link" target="_blank">
                                    <i class="fas fa-user text-warning nav-icon"></i>
                                    <p>Usuários</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/HOTEL/gestor/telas/solicitacao_reserva.php" class="nav-link" target="_blank">
                                    <i class="fa-solid fa-bed"></i>
                                    <p>Reservas</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-pdf text-warning"></i>
                            <p>
                                Emitir Relatórios
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <li class="nav-item">
                                <a href="../gestor/relatorio/usuario/filtro_usuarios.php" class="nav-link" target="_blank">
                                    <i class="fas fa-user text-warning nav-icon"></i>
                                    <p>Usuários</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../gestor/relatorio/reserva/filtro_reservas.php" class="nav-link" target="_blank">
                                    <i class="fa-solid fa-bed"></i>
                                    <p>Reservas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../gestor/relatorio/faturamento/filtro_faturamento.php" class="nav-link" target="_blank">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                    <p>Faturamento</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="nav-item d-md-none">
                    <a href="../logout.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sair</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
