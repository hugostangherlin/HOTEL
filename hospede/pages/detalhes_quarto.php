<?php
require_once '../../config/config.php';
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "ID do quarto inválido.";
    exit;
}

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
</head>
<body>

<div class="container">
    <div class="quarto-img" style="background-image: url('../../uploads/<?= htmlspecialchars($quarto['Foto']) ?>');"></div>


<div class="quarto-conteudo">
    <h2>Quarto - <?= htmlspecialchars($quarto['nome_categoria']) ?></h2>
    <p><strong>Capacidade:</strong> <?= htmlspecialchars($quarto['Capacidade']) ?> pessoa(s)</p>
    <p><strong>Preço da Diária:</strong> R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></p>

    <div class="descricao">
        <p>Este quarto é ideal para quem busca conforto e praticidade. Acomoda até <?= htmlspecialchars($quarto['Capacidade']) ?> pessoas e oferece todas as comodidades necessárias para uma estadia agradável.</p>
    </div>

    <div class="comodidades">
        <p><i class="fas fa-bed"></i> 2 camas</p>
        <p><i class="fas fa-bath"></i> 2 banheiros</p>
        <p><i class="fas fa-wifi"></i> Wi-Fi gratuito</p>
    </div>

    <!-- Formulário de datas -->
    <form id="form-reserva">
        <label for="checkin"><strong>Check-in:</strong></label>
        <input type="date" id="checkin" name="checkin" required>

        <label for="checkout"><strong>Check-out:</strong></label>
        <input type="date" id="checkout" name="checkout" required>

        <button class="btn" type="submit">Reservar este quarto</button>
    </form>
</div>


<?php if (count($outros_quartos) > 0): ?>
    <div class="outros-quartos">
        <h3 style="padding-left: 20px;">Outros quartos da mesma categoria:</h3>
        <div class="quartos">
        <?php foreach ($outros_quartos as $q): ?>
            <div class="quarto-card">
                <img src="../../uploads/<?= htmlspecialchars($q['Foto']) ?>" alt="Quarto">
                <h4><?= htmlspecialchars($q['nome_categoria']) ?></h4>
                <p>R$ <?= number_format($q['Preco_diaria'], 2, ',', '.') ?>/noite</p>
                <a href="detalhes_quarto.php?id=<?= $q['ID_Quarto'] ?>" class="btn btn-secundario">Ver detalhes</a>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
document.getElementById("form-reserva").addEventListener("submit", function(e) {
    e.preventDefault();

    const checkin = document.getElementById("checkin").value;
    const checkout = document.getElementById("checkout").value;

    if (!checkin || !checkout) {
        alert("Por favor, selecione as datas de check-in e check-out.");
        return;
    }

    const confirmado = confirm("Você deseja fazer uma reserva para este quarto?");
    if (confirmado) {
        const quartoId = <?= $quarto['ID_Quarto'] ?>;
        window.location.href = "/HOTEL/actions/reservar_quarto.php?id=" + quartoId + "&checkin=" + checkin + "&checkout=" + checkout;
    }
});
</script>


<?php include '/HOTEL/includes/rodape.php'; ?>

</body>
</html>
