<?php
        date_default_timezone_set('America/Sao_Paulo');

        // Dados para saudação
        $nome = $_SESSION['usuario']['nome'];
        $dataAtual = date('d-m-Y');
        $horaAtual = date('H:i');
        $dataComHora = date('d-m-Y H:i:s');
        
        // Define saudação conforme o horário
        if ($horaAtual >= '00:00' && $horaAtual < '12:00') {
            $saudacao = "Bom dia";
        } elseif ($horaAtual >= '12:00' && $horaAtual < '18:00') {
            $saudacao = "Boa tarde";
        } else {
            $saudacao = "Boa noite";
        }
          
    ?>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <h1>Logo</h1>
            </div>
            <div class="nav-list">
                <ul>
                    <li class="nav-item"><a href="#" class="nav-link">Início</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Quartos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link"> Sobre</a></li>
                </ul>
            </div>
        </div>
    </header>
