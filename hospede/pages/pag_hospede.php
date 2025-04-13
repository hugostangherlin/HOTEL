<?php
include '../includes/head.php';
include '../config/config.php';

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
