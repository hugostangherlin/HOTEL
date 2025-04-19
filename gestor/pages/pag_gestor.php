<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Gestor</title>
    <link rel="icon" href="../assets/favicon.ico?v=1" type="image/x-icon">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="conteudo">
    <h3><?php echo "$saudacao, $nome!"; ?></h3>

    <div class="cards">
        <a href="../form/cadastroform.php">Cadastrar Gestor</a>
        <a href="gerenciarquartos/index.php">Gerenciar Quartos</a>
        <a href="gerenciar_reservas.php">Gerenciar Reservas</a>
        <a href="gerar_relatorios.php">Gerar Relatórios</a>
    </div>
</div>
<div class="btn_logout">
<form action="/HOTEL/logout.php" method="post" class="btn_logout">
<button type="submit">Sair</button>
</div>

</body>
</html>

