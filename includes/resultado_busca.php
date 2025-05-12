<?php
// Iniciar sessão se necessário
if (session_status() === PHP_SESSION_NONE) session_start();

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    // Redireciona se alguém tentar acessar diretamente
    header("Location: home.php");
    exit;
}

// Conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$nome_banco = "rodeo_hotel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nome_banco;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Recebe e valida os dados obrigatórios
$checkin    = $_GET['checkin'] ?? '';
$checkout   = $_GET['checkout'] ?? '';
$categoria  = $_GET['categoria'] ?? '';
$hospedes = isset($_GET['hospedes']) ? (int) $_GET['hospedes'] : 1;


if (!$checkin || !$checkout || !$categoria || !$hospedes) {
    die();
}

// Consulta para encontrar quartos disponíveis com base nos critérios
$sql = "
SELECT quarto.*, categoria.Nome AS NomeCategoria
FROM quarto
JOIN categoria ON quarto.Categoria_ID_Categoria = categoria.ID_Categoria
WHERE quarto.Categoria_ID_Categoria = :categoria
AND quarto.Capacidade >= :hospedes
AND quarto.ID_Quarto NOT IN (
    SELECT Quarto_ID_Quarto 
    FROM reserva 
    WHERE Checkout >= :checkin AND Checkin <= :checkout
)
";

// Preparando a consulta
$stmt = $pdo->prepare($sql);

// Definindo os valores para os parâmetros
$stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
$stmt->bindParam(':hospedes', $hospedes, PDO::PARAM_INT);
$stmt->bindParam(':checkin', $checkin, PDO::PARAM_STR);
$stmt->bindParam(':checkout', $checkout, PDO::PARAM_STR);

// Executando a consulta
$stmt->execute();

// Buscando os resultados
$quartos = $stmt->fetchAll();

if (!is_numeric($hospedes) || $hospedes < 1) {
    echo "Número de hóspedes inválido!";
    exit;
}


// Exibir os resultados
$checkin_formatado = DateTime::createFromFormat('Y-m-d', $checkin)->format('d/m/Y');
$checkout_formatado = DateTime::createFromFormat('Y-m-d', $checkout)->format('d/m/Y');
?>

<h2>Sua Busca</h2>
<p><strong>Checkin:</strong> <?= $checkin_formatado ?></p>
<p><strong>Checkout:</strong> <?= $checkout_formatado ?></p>
<p><strong>Categoria:</strong> <?= htmlspecialchars($quartos[0]['NomeCategoria']) ?></p> 
<p><strong>Hóspedes:</strong> <?= htmlspecialchars($hospedes) ?></p>


<?php if ($quartos): ?>
    <?php foreach ($quartos as $quarto): ?>
        <div style="border:1px solid #ccc; margin:10px 0; padding:10px;">
<?php if (!empty($quarto['Foto'])): ?>
    <img src="/HOTEL/uploads/<?= $quarto['Foto'] ?>" alt="Foto do Quarto" style="width: 200px; height: 100px; object-fit: cover;">
<?php endif; ?>

            <h3><?= htmlspecialchars($quarto['NomeCategoria']) ?></h3>
            <p><strong>Diária:</strong> R$ <?= number_format($quarto['Preco_diaria'], 2, ',', '.') ?></p>
            <p><strong>Hóspedes:</strong> <?= $quarto['Capacidade'] ?> pessoa(s)</p>
        

            <a href="detalhes_quarto.php?id=<?= $quarto['ID_Quarto'] ?>&checkin=<?= $_GET['checkin'] ?>&checkout=<?= $_GET['checkout'] ?>">Ver detalhes</a>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum quarto disponível para os critérios informados.</p>
<?php endif; ?>