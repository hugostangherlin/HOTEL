<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de hóspede (ID 2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodeo Hotel</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <link rel="icon" href="../assets/favicon.ico?v=1" type="image/x-icon">
</head>
<body>
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
</body>
</html>

<?php
if (isset($_SESSION['usuarios'])) {
    header("Location: entrar.php");
    exit();
}
$nome = $_SESSION['usuario']['nome']; 
?>

<div class="saudacao">
    <h3><?php echo "$saudacao, $nome!"; ?></h3>
</div>

<div class="exibirperfil">
    <a href="exibir_hospede.php">Meu Perfil</a>
</div>

<div class="btn_logout">
<a href="logout.php">Sair</a>
</div>
