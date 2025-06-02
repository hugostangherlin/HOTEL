<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitação de Exclusão</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    <style>
                :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$usuario_id = $_SESSION['usuario']['ID'];
$reserva_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($reserva_id <= 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'ID da reserva inválido.',
        }).then(() => {
            history.back();
        });
    </script>";
    exit();
}

// Verifica se a reserva pertence ao usuário e se já foi solicitada
$sql = $pdo->prepare("SELECT solicitou_exclusao FROM reserva WHERE ID_Reserva = :id AND usuarios_ID = :usuario_id");
$sql->bindValue(':id', $reserva_id);
$sql->bindValue(':usuario_id', $usuario_id);
$sql->execute();
$reserva = $sql->fetch();

if (!$reserva) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Reserva não encontrada ou não pertence a você.',
        }).then(() => {
            history.back();
        });
    </script>";
    exit();
}

if ($reserva['solicitou_exclusao'] == 1) {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Solicitação já enviada',
            text: 'Você já solicitou a exclusão dessa reserva. Aguarde o gestor.',
        }).then(() => {
            history.back();
        });
    </script>";
    exit();
}

// Atualiza o campo na tabela de reservas
$sql = $pdo->prepare("UPDATE reserva SET solicitou_exclusao = 1, Data_Solicitacao_Exclusao = NOW() WHERE ID_Reserva = :id AND usuarios_ID = :usuario_id");
$sql->bindValue(':id', $reserva_id);
$sql->bindValue(':usuario_id', $usuario_id);
$sql->execute();

echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Solicitação Enviada',
        text: 'A solicitação de exclusão foi enviada. Aguarde a análise do gestor.',
    }).then(() => {
        window.location.href = 'pag_hospede.php';
    });
</script>";
?>

</body>
</html>
