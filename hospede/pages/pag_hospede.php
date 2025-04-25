<?php
session_start();
// Verifica se o usuário está logado e se tem o perfil de hóspede (ID 2)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 2) {
    header("Location: entrar.php"); // Redireciona para o login caso não tenha permissão
    exit();
}
$nome = $_SESSION['usuario']['nome'];

// Conexão com o banco de dados
require 'config.php';
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

<body>
<?php include '../includes/header.php'; ?>

<div class="conteudo">
    <h3><?php echo "Bem-vindo, $nome!"; ?></h3>

    <div class="exibirperfil">
        <a href="exibir_hospede.php">Meu Perfil</a>
    </div>

    <div class="btn_logout">
        <form action="/HOTEL/logout.php" method="post" class="btn_logout">
            <button type="submit">Sair</button>
        </form>
    </div>

    <!-- Formulário de filtro de quartos -->
    <form method="GET" action="">
        <label>Check-in:</label>
        <input type="date" name="checkin" value="<?= isset($_GET['checkin']) ? $_GET['checkin'] : '' ?>">

        <label>Check-out:</label>
        <input type="date" name="checkout" value="<?= isset($_GET['checkout']) ? $_GET['checkout'] : '' ?>">

        <label>Categoria:</label>
        <select name="categoria">
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

    <hr>

    <?php
    // Filtros opcionais
    $checkin = !empty($_GET['checkin']) ? $_GET['checkin'] : null;
    $checkout = !empty($_GET['checkout']) ? $_GET['checkout'] : null;
    $categoria = !empty($_GET['categoria']) ? $_GET['categoria'] : null;

    // Monta a consulta base
    $sql = "SELECT q.*, c.Nome AS categoria_nome
            FROM quarto q
            JOIN categoria c ON q.Categoria_ID_Categoria = c.ID_Categoria
            WHERE q.Status = 'Disponível'";

    // Condições dinâmicas
    if ($categoria) {
        $sql .= " AND q.Categoria_ID_Categoria = :categoria";
    }
    if ($checkin && $checkout) {
        $sql .= " AND q.ID_Quarto NOT IN (
            SELECT Quarto_ID_Quarto
            FROM reserva
            WHERE (:checkin < Checkout AND :checkout > Checkin)
        )";
    }

    $stmt = $pdo->prepare($sql);

    // Bind params
    if ($categoria) {
        $stmt->bindParam(':categoria', $categoria);
    }
    if ($checkin && $checkout) {
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
    }

    $stmt->execute();
    $quartos = $stmt->fetchAll();

    // Exibição
    if (count($quartos) > 0) {
        echo "<h2>Quartos Disponíveis:</h2>";
        foreach ($quartos as $q) {
            echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
            echo "<strong>Categoria:</strong> {$q['categoria_nome']}<br>";
            echo "<strong>Capacidade:</strong> {$q['Capacidade']} pessoas<br>";
            echo "<img src='../uploads/{$q['Foto']}' width='150'><br>";
            echo "<a href='reservar.php?id={$q['ID_Quarto']}&checkin=$checkin&checkout=$checkout'>Reservar</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum quarto disponível nessas condições.</p>";
    }
    ?>
</div>

</body>
</html>
