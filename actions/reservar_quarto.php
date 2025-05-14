<?php
require '../config/config.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

// Verifica se o ID do quarto foi passado
if (empty($_GET['id'])) {
    echo "ID do quarto não fornecido.";
    exit();
}

$usuario_id = $_SESSION['usuario']['ID'];
$quarto_id = $_GET['id'];

// Gera datas padrão: hoje e amanhã
$checkin = $_GET['checkin'] ?? date('Y-m-d');
$checkout = $_GET['checkout'] ?? date('Y-m-d', strtotime('+1 day'));

// Buscar preço da diária do quarto
$stmt = $pdo->prepare("SELECT Preco_diaria FROM quarto WHERE ID_Quarto = :quarto_id");
$stmt->execute([':quarto_id' => $quarto_id]);
$quarto = $stmt->fetch();

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
    return strtoupper(bin2hex(random_bytes(16))); // 32 caracteres
}

$chave_pix = gerarChavePix();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FB4D46;
            --secondary-color: #2c3e50;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --border-radius: 10px;
            --box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
        }
        
        .page-title {
            color: var(--secondary-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .resume-section {
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .resume-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .resume-total {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .payment-form {
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }
        
        .payment-method {
            display: none;
            margin-top: 30px;
            padding: 20px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .payment-title {
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .card-input-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .card-input-group-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }
        
        .pix-code {
            background-color: var(--white);
            padding: 15px;
            border-radius: var(--border-radius);
            font-family: monospace;
            font-size: 18px;
            text-align: center;
            margin: 15px 0;
            word-break: break-all;
        }
        
        .barcode-img {
            width: 100%;
            max-width: 400px;
            margin: 15px auto;
            display: block;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        
        .btn:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .card-input-group,
            .card-input-group-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Finalizar Reserva</h1>
        
        <div class="resume-section">
            <h3>Resumo da Reserva</h3>
            <div class="resume-item">
                <span>Diária:</span>
                <span>R$ <?php echo number_format($quarto['Preco_diaria'], 2, ',', '.'); ?></span>
            </div>
            <div class="resume-item">
                <span>Período:</span>
                <span><?php echo date('d/m/Y', strtotime($checkin)); ?> a <?php echo date('d/m/Y', strtotime($checkout)); ?></span>
            </div>
            <div class="resume-item">
                <span>Total de diárias:</span>
                <span><?php echo $dias; ?> noite(s)</span>
            </div>
            <div class="resume-item resume-total">
                <span>Valor Total:</span>
                <span>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></span>
            </div>
        </div>
        
        <form method="POST" action="http://localhost/HOTEL/actions/processarpagamento.php" class="payment-form">
            <input type="hidden" name="quarto_id" value="<?php echo $quarto_id; ?>">
<input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
<input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
<input type="hidden" name="valor_total" value="<?php echo $valor_total; ?>">
            
            <div class="form-group">
                <label for="forma_pagamento">Forma de Pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" required onchange="mostrarFormulario()">
                    <option value="">Selecione a forma de pagamento</option>
                    <option value="Cartão">Cartão de Crédito</option>
                    <option value="Boleto">Boleto Bancário</option>
                    <option value="Pix">Pix</option>
                </select>
            </div>
            
            <!-- Cartão -->
            <div id="cartao-info" class="payment-method">
                <h4 class="payment-title">Dados do Cartão</h4>
                <div class="form-group">
                    <label>Número do Cartão</label>
                    <input type="text" name="numero_cartao" maxlength="19" placeholder="0000 0000 0000 0000">
                </div>
                
                <div class="form-group">
                    <label>Nome no Cartão</label>
                    <input type="text" name="nome_cartao" placeholder="Nome como está no cartão">
                </div>
                
                <div class="card-input-group">
                    <div class="form-group">
                        <label>Validade</label>
                        <input type="text" name="validade" maxlength="5" placeholder="MM/AA">
                    </div>
                    
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" name="cvv" maxlength="4" placeholder="123">
                    </div>
                </div>
            </div>
            
            <!-- Boleto -->
            <div id="boleto-info" class="payment-method">
                <h4 class="payment-title">Boleto Bancário</h4>
                <p>O boleto será gerado após a confirmação da reserva.</p>
                <img src="https://via.placeholder.com/500x100.png?text=Código+de+Barras+Fictício+%7C+%7C1234567890" alt="Código de Barras" class="barcode-img">
                <div class="form-group">
                    <label>Nosso número</label>
                    <input type="text" value="1234567890" readonly>
                </div>
            </div>
            
            <!-- Pix -->
            <div id="pix-info" class="payment-method">
                <h4 class="payment-title">Pagamento via Pix</h4>
                <p>Utilize a chave Pix abaixo para realizar o pagamento:</p>
                <div class="pix-code"><?php echo $chave_pix; ?></div>
                <div class="form-group">
                    <label>Chave Pix copiável</label>
                    <input type="text" value="<?php echo $chave_pix; ?>" readonly>
                </div>
            </div>
            
            <button type="submit" class="btn">Confirmar Pagamento</button>
        </form>
    </div>

    <script>
        function mostrarFormulario() {
            const forma = document.getElementById("forma_pagamento").value;
            document.getElementById("cartao-info").style.display = "none";
            document.getElementById("boleto-info").style.display = "none";
            document.getElementById("pix-info").style.display = "none";

            if (forma === "Cartão") {
                document.getElementById("cartao-info").style.display = "block";
            } else if (forma === "Boleto") {
                document.getElementById("boleto-info").style.display = "block";
            } else if (forma === "Pix") {
                document.getElementById("pix-info").style.display = "block";
            }
        }
    </script>
</body>
</html>