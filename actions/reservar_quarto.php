<?php
require '../config/config.php';
session_start();


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

// Verifica se os dados necessários foram passados via GET
if (empty($_GET['id']) || empty($_GET['checkin']) || empty($_GET['checkout'])) {
    echo "Dados incompletos para pagamento.";
    exit();
}

// Depuração para verificar se os dados estão chegando
var_dump($_GET); // Verifica os valores de id, checkin e checkout

$usuario_id = $_SESSION['usuario']['id'];
$quarto_id = $_GET['id'];  // Corrigido para pegar 'id' da URL
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];

// Buscar preço da diária do quarto
$stmt = $pdo->prepare("SELECT Preco_diaria FROM quarto WHERE ID_Quarto = :quarto_id");
$stmt->execute([':quarto_id' => $quarto_id]);
$quarto = $stmt->fetch();

// Verifica se o quarto foi encontrado
if (!$quarto) {
    echo "Quarto não encontrado.";
    exit();
}

// Calcular total
$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$dias = $checkin_date->diff($checkout_date)->days;
$valor_total = $quarto['Preco_diaria'] * $dias;

// Função para gerar chave Pix aleatória
function gerarChavePix() {
    // Gerar um número aleatório de 32 caracteres (simulando uma chave Pix aleatória)
    return strtoupper(bin2hex(random_bytes(16))); // Cria uma chave aleatória de 32 caracteres
}

$chave_pix = gerarChavePix(); // Gerar chave Pix aleatória
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento</title>
    <style>
        #cartao-info, #boleto-info, #pix-info { display: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h3>Simulação de Pagamento</h3>
    <p><strong>Valor da Reserva:</strong> R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></p>

    <form method="POST" action="http://localhost/HOTEL/actions/processarpagamento.php">
        <input type="hidden" name="quarto_id" value="<?php echo $quarto_id; ?>">
        <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
        <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
        <input type="hidden" name="valor_total" value="<?php echo $valor_total; ?>">

        <label>Forma de Pagamento:</label>
        <select name="forma_pagamento" id="forma_pagamento" required onchange="mostrarFormulario()">
            <option value="">Selecione</option>
            <option value="Cartão">Cartão</option>
            <option value="Boleto">Boleto</option>
            <option value="Pix">Pix</option>
        </select>

        <!-- Formulário para Cartão -->
        <div id="cartao-info">
            <h4>Dados do Cartão (simulação):</h4>
            <label>Número do Cartão:</label>
            <input type="text" name="numero_cartao" maxlength="19" placeholder="XXXX XXXX XXXX XXXX"><br>

            <label>Nome no Cartão:</label>
            <input type="text" name="nome_cartao" placeholder="Nome como está no cartão"><br>

            <label>Validade:</label>
            <input type="text" name="validade" maxlength="5" placeholder="MM/AA"><br>

            <label>CVV:</label>
            <input type="text" name="cvv" maxlength="4" placeholder="123"><br>
        </div>

        <!-- Formulário para Boleto -->
        <div id="boleto-info">
            <h4>Simulação do Boleto:</h4>
            <p>Utilize o código de barras gerado abaixo para simulação de pagamento:</p>
            <div>
                <!-- Simulação do código de barras (pode ser um número ou imagem fictícia) -->
                <img src="https://via.placeholder.com/500x100.png?text=Código+de+Barras+Fictício+%7C+%7C1234567890" alt="Código de Barras">
                <p><strong>Nosso número do boleto:</strong> 1234567890</p>
            </div>
        </div>

        <!-- Formulário para Pix -->
        <div id="pix-info">
            <h4>Simulação do Pix:</h4>
            <p>Utilize a chave Pix abaixo para simulação de pagamento:</p>
            <div>
                <p><strong>Chave Pix:</strong> <?php echo $chave_pix; ?></p>
            </div>
        </div>

        <button type="submit">Confirmar Pagamento</button>
    </form>

    <script>
        function mostrarFormulario() {
            const forma = document.getElementById("forma_pagamento").value;
            const cartaoInfo = document.getElementById("cartao-info");
            const boletoInfo = document.getElementById("boleto-info");
            const pixInfo = document.getElementById("pix-info");

            // Esconde os formulários
            cartaoInfo.style.display = "none";
            boletoInfo.style.display = "none";
            pixInfo.style.display = "none";

            // Exibe o formulário adequado
            if (forma === "Cartão") {
                cartaoInfo.style.display = "block";
            } else if (forma === "Boleto") {
                boletoInfo.style.display = "block";
            } else if (forma === "Pix") {
                pixInfo.style.display = "block";
            }
        }
    </script>
</body>
</html>
