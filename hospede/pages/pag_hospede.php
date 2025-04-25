<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de hóspede (ID 2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
$nome = $_SESSION['usuario']['nome'];
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
<?php include '../includes/header.php'; ?>

<div class="conteudo">
    <h3><?php echo "$saudacao, $nome!"; ?></h3>
<div class="exibirperfil">
    <a href="exibir_hospede.php">Meu Perfil</a>
</div>

<div class="btn_logout">
<form action="/HOTEL/logout.php" method="post" class="btn_logout">
<button type="submit">Sair</button>
</div>
<div>
<form method="GET" action="">
    <label>Check-in:</label>
    <input type="date" name="checkin" required>

    <label>Check-out:</label>
    <input type="date" name="checkout" required>

    <label>Categoria:</label>
    <select name="categoria" required>
      <option value="">--Selecione--</option>
      <?php
      // Buscar categorias do banco
      $sql = $pdo->query("SELECT * FROM categoria");
      $categorias = $sql->fetchAll();
      foreach ($categorias as $cat) {
        echo "<option value='{$cat['ID_Categoria']}'>{$cat['Nome']}</option>";
      }
      ?>
    </select>

    <button type="submit">Buscar</button>
  </form>

  <!-- RESULTADOS -->
  <?php
  if (!empty($_GET['checkin']) && !empty($_GET['checkout']) && !empty($_GET['categoria'])) {
    $checkin = $_GET['checkin'];
    $checkout = $_GET['checkout'];
    $categoria = $_GET['categoria'];

    $stmt = $pdo->prepare("
      SELECT q.*, c.Nome AS categoria_nome
      FROM quarto q
      JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
      WHERE q.Status = 'Disponível'
        AND q.Categoria_ID_Categoria = :categoria
        AND q.ID_Quarto NOT IN (
            SELECT Quarto_ID_Quarto
            FROM reserva
            WHERE (:checkin < Checkout AND :checkout > Checkin)
        )
    ");
    $stmt->execute([
      ':checkin' => $checkin,
      ':checkout' => $checkout,
      ':categoria' => $categoria
    ]);

    $quartos = $stmt->fetchAll();

    if (count($quartos) > 0) {
      echo "<h2>Quartos disponíveis:</h2>";
      foreach ($quartos as $q) {
        echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
        echo "<strong>Categoria:</strong> {$q['categoria_nome']}<br>";
        echo "<strong>Capacidade:</strong> {$q['Capacidade']} pessoas<br>";
        echo "<img src='uploads/{$q['Foto']}' width='150'><br>"; // ajuste o caminho da foto se necessário
        echo "<button>Reservar</button>"; // futuramente, pode linkar para a página de reserva
        echo "</div>";
      }
    } else {
      echo "<p>Nenhum quarto disponível nessas condições.</p>";
    }
  }
  ?>

</div>

