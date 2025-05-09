<?php
require '../../config/config.php';
session_start();

// Verifica se o ID foi passado corretamente
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}

// Consulta o quarto específico
$sql = $pdo->prepare("
    SELECT q.*, c.Nome AS nome_categoria
    FROM quarto q
    JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
    WHERE q.ID_Quarto = :id
");
$sql->bindValue(':id', $id);
$sql->execute();
$quarto = $sql->fetch(PDO::FETCH_ASSOC);

if (!$quarto) {
    echo "Quarto não encontrado.";
    exit;
}

// Busca outros quartos da mesma categoria (excluindo o atual)
$outros = $pdo->prepare("
    SELECT q.*, c.Nome AS nome_categoria
    FROM quarto q
    JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
    WHERE q.Categoria_ID_Categoria = :cat_id AND q.ID_Quarto != :id
");
$outros->bindValue(':cat_id', $quarto['Categoria_ID_Categoria']);
$outros->bindValue(':id', $id);
$outros->execute();
$outros_quartos = $outros->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quarto['nome_categoria']) ?> - Detalhes</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>

    </style>
</head>
<body>

<?php include '../../includes/naaverbar.php';; ?>

<div class="container">
    <div class="quarto-img" style="background-image: url('../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>');"></div>

    <div class="quarto-conteudo">
        <h2><?= htmlspecialchars($quarto['nome_categoria']) ?></h2>
        <p><strong>Capacidade:</strong> <?= htmlspecialchars($quarto['Capacidade']) ?> pessoa(s)</p>
        <p><strong>Preço da Diária:</strong> R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></p>

        <div class="descricao">
            <p>Este quarto oferece conforto ideal para relaxar após um longo dia. Aproveite a vista e a tranquilidade com todos os serviços essenciais.</p>
        </div>

        <div class="comodidades">
            <p><i class="fas fa-bed"></i> 2 camas</p>
            <p><i class="fas fa-bath"></i> 2 banheiros</p>
            <p><i class="fas fa-wifi"></i> Wi-Fi gratuito</p>
        </div>

        <form action="/HOTEL/actions/reservar_quarto.php" method="GET">
    <input type="hidden" name="id" value="<?= $quarto['ID_Quarto'] ?>">

    <label for="checkin">Check-in:</label>
    <input type="date" name="checkin" required>

    <label for="checkout">Check-out:</label>
    <input type="date" name="checkout" required>

    <button type="submit" class="btn">Reservar este quarto</button>
</form>


        <a href="index.php" class="btn btn-secundario">Voltar</a>
    </div>
</div>

<?php if (count($outros_quartos) > 0): ?>
<div class="outros-quartos">
    <h3 style="padding-left: 20px;">Outros quartos da mesma categoria:</h3>
    <?php foreach ($outros_quartos as $q): ?>
        <div class="quarto-card">
            <img src="../../uploads/<?= htmlspecialchars($q['Foto']) ?>" alt="Quarto">
            <h4><?= htmlspecialchars($q['nome_categoria']) ?></h4>
            <p>R$ <?= number_format($q['Preco_diaria'], 2, ',', '.') ?>/noite</p>
            <a href="detalhes_quarto.php?id=<?= $q['ID_Quarto'] ?>" class="btn">Ver detalhes</a>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>

</body>
</html>