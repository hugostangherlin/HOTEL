<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/HOTEL/config/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}

// Verifica se checkin e checkout vieram por GET e salva na sessão
if (isset($_GET['checkin']) && isset($_GET['checkout'])) {
    $_SESSION['checkin'] = $_GET['checkin'];
    $_SESSION['checkout'] = $_GET['checkout'];
}

// Se ainda assim não tiver datas, interrompe
if (!isset($_SESSION['checkin']) || !isset($_SESSION['checkout'])) {
    echo "Por favor, selecione as datas primeiro.";
    echo "<br><a href='/HOTEL/home.php'>Voltar para a página inicial</a>";
    exit;
}

// Consulta o quarto específico
$sql = $pdo->prepare("SELECT q.*, c.Nome AS nome_categoria FROM quarto q
    JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria WHERE q.ID_Quarto = :id");
$sql->bindValue(':id', $id);
$sql->execute();
$quarto = $sql->fetch(PDO::FETCH_ASSOC);

if (!$quarto) {
    echo "Quarto não encontrado.";
    exit;
}

// Salva informações para reserva na sessão
$_SESSION['reserva_temporaria'] = [
    'id_quarto' => $quarto['ID_Quarto'],
    'checkin' => $_SESSION['checkin'],
    'checkout' => $_SESSION['checkout'],
];

// Verifica se o usuário está logado ao clicar em "Reservar"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario'])) {
        // Salva a URL de onde o usuário veio
        $_SESSION['retorno'] = $_SERVER['REQUEST_URI']; // URL da página de detalhes

        // Redireciona para a página de login
        header("Location: /HOTEL/entrar.php");
        exit;
    } else {
        // Usuário logado, vai para a página de pagamento
        header("Location: /HOTEL/actions/reservar_quarto.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quarto['nome_categoria']) ?> - Detalhes</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
<div class="container">
    <div class="quarto-img" style="background-image: url('../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>');"></div>

    <div class="quarto-conteudo">
        <h2><?= htmlspecialchars($quarto['nome_categoria']) ?></h2>
        <p><strong>Capacidade:</strong> <?= htmlspecialchars($quarto['Capacidade']) ?> pessoa(s)</p>
        <p><strong>Preço da Diária:</strong> R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></p>
        <p><strong>Check-in:</strong> <?= htmlspecialchars($_SESSION['checkin']) ?></p>
        <p><strong>Check-out:</strong> <?= htmlspecialchars($_SESSION['checkout']) ?></p>

        <form method="POST">
            <button type="submit">Reservar este quarto</button>
        </form>
    </div>
</div>

<?php include 'includes/foter.php'; ?>

</body>
</html>
