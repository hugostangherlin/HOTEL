<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Deletar Sua Conta</title>
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

// Verifica se já foi solicitada
$sql = $pdo->prepare("SELECT solicitou_exclusao FROM usuarios WHERE ID = :id");
$sql->bindValue(':id', $usuario_id);
$sql->execute();
$usuario = $sql->fetch();

if ($usuario && $usuario['solicitou_exclusao'] == 1) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Solicitação já enviada',
            text: 'Você já solicitou a exclusão. Aguarde o gestor.',
        }).then(() => {
            history.back();
        });
    </script>";
    exit();
}

// Atualiza o campo
$sql = $pdo->prepare("UPDATE usuarios SET solicitou_exclusao = 1 WHERE ID = :id");
$sql->bindValue(':id', $usuario_id);
$sql->execute();

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
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