<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Gestor</title>
    <link rel="icon" href="../assets/favicon.ico?v=1" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="conteudo">
    <h3><?php echo "$saudacao, $nome!"; ?></h3>

    <div class="dashboard">
    <a href="../form/cadastroform.php" class="btn-default">Gerenciar Usuários</a>
    <a href="gerenciarquartos/index.php" class="btn-default">Gerenciar Quartos</a>
    <a href="gerenciar_reservas.php" class="btn-default">Gerenciar Reservas</a>
    <a href="gerar_relatorios.php" class="btn-default">Gerar Relatórios</a>
    </div>
</div>
<div class="exibirperfil">
    <a href="exibir_gestor.php">Meu Perfil</a>
</div>
<div class="btn_logout">
<form action="/HOTEL/logout.php" method="post" class="btn_logout">
<button type="submit">Sair</button>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>

