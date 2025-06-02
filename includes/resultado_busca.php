<?php
require_once '../config/config.php';

$checkin   = $_POST['checkin'] ?? '';
$checkout  = $_POST['checkout'] ?? '';
$categoria = $_POST['categoria'] ?? '';

// Consulta SQL modificada (removida a coluna Descricao)
$sql = "SELECT q.*, c.Nome AS NomeCategoria
        FROM quarto q
        INNER JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
        WHERE q.Status = 'Disponível'";

$params = [];

if (!empty($checkin) && !empty($checkout)) {
    $sql .= " AND q.ID_Quarto NOT IN (
        SELECT Quarto_ID_Quarto
        FROM reserva
        WHERE (Checkin <= :checkout AND Checkout >= :checkin)
    )";
    $params[':checkin'] = $checkin;
    $params[':checkout'] = $checkout;
}

if (!empty($categoria)) {
    $sql .= " AND c.ID_Categoria = :categoria";
    $params[':categoria'] = $categoria;
}

$sql .= " ORDER BY q.ID_Quarto";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$quartos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($quartos):
    foreach ($quartos as $quarto): ?>
        <div class="col-lg-4 col-md-6">
            <div class="room-card">
                <div class="position-relative">
                    <?php if (!empty($quarto['Foto'])): ?>
                        <img src="/HOTEL/uploads/<?= htmlspecialchars($quarto['Foto']) ?>" class="img-fluid room-img" alt="<?= htmlspecialchars($quarto['NomeCategoria']) ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/600x400?text=Rodeo+Hotel" class="img-fluid room-img" alt="Quarto">
                    <?php endif; ?>
                    <div class="price-tag">R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?>/noite</div>
                </div>
                <div class="p-4">
                    <h4><?= htmlspecialchars($quarto['NomeCategoria']) ?></h4>
                    <div class="d-flex mb-3">
                        <small class="me-3"><i class="fas fa-bed me-2 text-primary"></i> <?= $quarto['Capacidade'] ?> Pessoa(s)</small>
                    </div>
                    <a href="/HOTEL/hospede/pages/detalhes_quarto.php?id=<?= $quarto['ID_Quarto'] ?>&checkin=<?= htmlspecialchars($checkin) ?>&checkout=<?= htmlspecialchars($checkout) ?>" class="btn btn-primary-custom w-100">Reservar Agora</a>
                </div>
            </div>
        </div>
    <?php endforeach;
else: ?>
    <div class="col-12">
        <div class="alert alert-warning text-center">Nenhum quarto disponível encontrado para os critérios selecionados.</div>
    </div>
<?php endif; ?>