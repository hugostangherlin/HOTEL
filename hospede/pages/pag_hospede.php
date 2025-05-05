<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de hóspede (ID 2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
$nome = $_SESSION['usuario']['nome'];

// Conexão com o banco de dados
require_once '../../config/config.php';
include '../../includes/header_hospede.php'; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rodeo Hotel</title>
  <link rel="stylesheet" href="pag_hospede.css" />
</head>
<body>
<?php 
include '../../includes/navbar_hospede.php'; 


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rodeo Hotel</title>
  <link rel="stylesheet" href="pag_hospede.css" />
</head>
<body>

  <main class="main-content">
    <div class="container">
    <h3 class="mb-0"><?= "$saudacao, $nome" ?></h3>

      <form method="GET" action="pag_hospede.php" class="search-form">
        <label for="checkin">Check-in:</label>
        <input type="date" id="checkin" name="checkin" value="<?= isset($_GET['checkin']) ? $_GET['checkin'] : '' ?>">

        <label for="checkout">Check-out:</label>
        <input type="date" id="checkout" name="checkout" value="<?= isset($_GET['checkout']) ? $_GET['checkout'] : '' ?>">

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria">
          <option value="">--Selecione--</option>
          <?php
          $sql = $pdo->query("SELECT * FROM categoria");
          $categorias = $sql->fetchAll();
          foreach ($categorias as $cat) {
              $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $cat['ID_Categoria']) ? 'selected' : '';
              echo "<option value='{$cat['ID_Categoria']}' $selected>{$cat['Nome']}</option>";
          }
          ?>
        </select>

        <button type="submit">Buscar</button>
      </form>

      <section class="room-list">
        <?php
        // Filtros opcionais
        $checkin = !empty($_GET['checkin']) ? $_GET['checkin'] : null;
        $checkout = !empty($_GET['checkout']) ? $_GET['checkout'] : null;
        $categoria = !empty($_GET['categoria']) ? $_GET['categoria'] : null;

        // Monta a consulta com status de ocupação
        $sql = "SELECT q.*, c.Nome AS categoria_nome,
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

        if ($categoria) {
            $sql .= " AND q.Categoria_ID_Categoria = :categoria";
        }

        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
        if ($categoria) {
            $stmt->bindParam(':categoria', $categoria);
        }

        $stmt->execute();
        $quartos = $stmt->fetchAll();

        // Exibição
        if (count($quartos) > 0) {
            foreach ($quartos as $q) {
                echo "<div class='quarto-card'>";
                echo "<img src='../uploads/{$q['Foto']}' alt='Foto do quarto' class='room-image'>";
                echo "<div class='room-info'>";
                echo "<h3 class='room-category'>{$q['categoria_nome']}</h3>";
                echo "<p class='room-capacity'>Capacidade: {$q['Capacidade']} pessoas</p>";
                echo "<p class='room-status'>Status: {$q['status_reserva']}</p>";
                if ($q['status_reserva'] === 'Disponível') {
                    echo "<a href='pagamento.php?id={$q['ID_Quarto']}&checkin=$checkin&checkout=$checkout' class='btn-reserve'>Reservar</a>";
                } else {
                    echo "<span class='room-unavailable'>Indisponível no período selecionado</span>";
                }
                echo "</div>";
                echo "</article>";
            }
        } else {
            echo "<p>Nenhum quarto encontrado.</p>";
        }
        ?>
      </section>
    </div>
  </main>

  <?php include '../../includes/footer_hospede.php'; ?>
</body>
</html>
