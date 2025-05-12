<aside class="main-sidebar sidebar-dark-purple elevation-4" style="background-color:rgb(32, 31, 32);">

    <div class="brand-link d-flex justify-content-between align-items-center">
        <a href="/entrar.php" class="brand-text font-weight-light" style="color: white !important; margin-left: 15px;">
            Rodeo Hotel
        </a>
        <a href="../home.php" class="btn btn-danger btn-sm mr-2 d-none d-md-inline" title="Sair">
            <i class="fas fa-sign-out-alt"></i>
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
                        <!-- Solicitação de Exclusão   -->
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
                                <a href="../gestor/gerar_relatorio.php?tipo=funcionarios" class="nav-link" target="_blank">
                                    <i class="fas fa-user text-warning nav-icon"></i>
                                    <p>Usuários</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../gestor/gerar_relatorio.php?tipo=produtos" class="nav-link" target="_blank">
                                    <i class="fa-solid fa-bed"></i>
                                    <p>Reservas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../gestor/gerar_relatorio.php?tipo=vendas" class="nav-link" target="_blank">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                    <p>Faturamento</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php elseif ($_SESSION['usuario']['perfil'] == 2): ?>
                    <!-- Se o perfil for de Preparador -->
                    <li class="nav-item">
                        <a href="../preparador/pedidos_recebidos.php" class="nav-link">
                            <i class="nav-icon fas fa-blender text-white"></i>
                            <p>Pedidos Recebidos</p>
                        </a>
                    </li>

                <?php elseif ($_SESSION['usuario']['perfil'] == 3): ?>
                    <!-- Se o perfil for de Caixa -->
                    <li class="nav-item">
                        <a href="../caixa/registrar_pedido.php" class="nav-link">
                            <i class="nav-icon fas fa-cash-register text-white"></i>
                            <p>Registrar Pedido</p>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item d-md-none">
                    <a href="../home.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sair</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>