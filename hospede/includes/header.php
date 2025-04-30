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
