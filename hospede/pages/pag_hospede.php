<?php
session_start();

// Verifica se o usuário é hóspede
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php");
    exit();
}

$nomeUsuario = $_SESSION['usuario']['nome'];

require_once '../../config/config.php';
include '../../includes/header_hospede.php';
include '../../includes/navbar_hospede.php';

// Parâmetros do filtro
$checkin = $_GET['checkin'] ?? null;
$checkout = $_GET['checkout'] ?? null;
$categoriaSelecionada = $_GET['categoria'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rodeo Hotel</title>
  <link rel="stylesheet" href="pag_hospede.css">
  <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
</head>
<body>
  <main class="main-content">
    <div class="container">
      <h3 class="mb-0">Olá, <?= $nomeUsuario ?></h3>

      <!-- Formulário de filtro -->
      <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="checkin" class="form-label">Check-in:</label>
            <input type="date" name="checkin" id="checkin" class="form-control" value="<?= htmlspecialchars($checkin) ?>">
        </div>
        <div class="col-md-3">
            <label for="checkout" class="form-label">Check-out:</label>
            <input type="date" name="checkout" id="checkout" class="form-control" value="<?= htmlspecialchars($checkout) ?>">
        </div>
        <div class="col-md-3">
            <label for="categoria" class="form-label">Categoria:</label>
            <select name="categoria" id="categoria" class="form-select">
                <option value="">Todas</option>
                <?php
                // Recupera as categorias do banco de dados
                $res = $pdo->query("SELECT * FROM categoria");
                foreach ($res as $cat) {
                    // Marca a categoria selecionada no filtro
                    $sel = ($categoriaSelecionada == $cat['ID_Categoria']) ? 'selected' : '';
                    echo "<option value='{$cat['ID_Categoria']}' $sel>{$cat['Nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
      </form>

      <!-- Listagem de Quartos -->
      <section class="room-list">
        <?php
        // Consulta SQL para buscar quartos
        $sql = "SELECT q.*, c.Nome AS nome_categoria,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM reserva r
                        WHERE r.Quarto_ID_Quarto = q.ID_Quarto
                        AND (:checkin IS NOT NULL AND :checkout IS NOT NULL)
                        AND (:checkin < r.Checkout AND :checkout > r.Checkin)
                    )
                    THEN 'Ocupado'
                    ELSE 'Disponível'
                END AS status_reserva
                FROM quarto q
                JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
                WHERE 1 = 1";

        // Aplica o filtro de categoria se selecionado
        if ($categoriaSelecionada) {
            $sql .= " AND q.Categoria_ID_Categoria = :categoria";
        }

        $consulta = $pdo->prepare($sql);
        $consulta->bindParam(':checkin', $checkin);
        $consulta->bindParam(':checkout', $checkout);
        if ($categoriaSelecionada) {
            $consulta->bindParam(':categoria', $categoriaSelecionada);
        }
        $consulta->execute();
        $quartos = $consulta->fetchAll();
        ?>

        <!-- Exibe os quartos -->
        <div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Quartos</h6>
            <h1 class="mb-5"><span class="text-primary text-uppercase"></span></h1>
        </div>
        <div class="row g-4">
            <?php if (count($quartos) > 0): ?>
                <?php foreach ($quartos as $q): ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="room-item shadow rounded overflow-hidden">
                            <div class="position-relative">
                            <img src="/HOTEL/uploads/<?= $q['Foto'] ?>" alt="Foto do Quarto" style="width: 200px; height: 100px; object-fit: cover;">
                                <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">
                                    R$<?= number_format($q['Preco_diaria'], 2, ',', '.') ?>/Diária
                                </small>
                            </div>
                            <div class="p-4 mt-2">
                                <h5 class="mb-1"><?= htmlspecialchars($q['nome_categoria']) ?></h5>
                                <p>Capacidade: <?= $q['Capacidade'] ?> pessoas</p>
                                <p>Status: <?= $q['status_reserva'] ?></p>
                                <?php if ($q['status_reserva'] === 'Disponível'): ?>
                                    <a class="btn btn-sm btn-primary mt-2" href="detalhes_quarto.php?id=<?= $q['ID_Quarto'] ?>">Ver Quarto</a>
                                <?php else: ?>
                                    <span class="text-danger">Indisponível</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Nenhum quarto encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
      </section>
    </div>
  </main>

  <?php include '../../includes/footer_hospede.php'; ?>
</body>
</html>