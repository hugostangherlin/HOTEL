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
<div class="conteudo">
    <h3><?php echo "Bem-vindo, $nome!"; ?></h3>

    <div class="exibirperfil">
        <a href="exibir_hospede.php">Meu Perfil</a>
    </div>

    <div class="minhasreservas">
<a href="minhasreservas.php">Minhas Reservas</a>
    </div>

    <div class="btn_logout">
        <form action="/HOTEL/logout.php" method="post" class="btn_logout">
            <button type="submit">Sair</button>
        </form>
    </div>

    <!-- Formulário de filtro de quartos -->
    <form method="GET" action="pag_hospede.php">
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
    echo "<h2>Lista de Quartos:</h2>";
    foreach ($quartos as $q) {
        echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
        echo "<strong>Categoria:</strong> {$q['categoria_nome']}<br>";
        echo "<strong>Capacidade:</strong> {$q['Capacidade']} pessoas<br>";
        echo "<strong>Status:</strong> {$q['status_reserva']}<br>";
        echo "<img src='../uploads/{$q['Foto']}' width='150'><br>";

        if ($q['status_reserva'] === 'Disponível') {
            echo "<a href='pagamento.php?id={$q['ID_Quarto']}&checkin=$checkin&checkout=$checkout'>Reservar</a>";
        } else {
            echo "<em>Indisponível no período selecionado</em>";
        }

        echo "</div>";
    }
} else {
    echo "<p>Nenhum quarto encontrado.</p>";
}
?>
