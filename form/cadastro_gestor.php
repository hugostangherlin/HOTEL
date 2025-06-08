<?php 
require '../Config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Gestores | Rodeo Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            padding: 30px; /* Adicionado padding aqui para o conteúdo */
        }
        
        .form-header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
            margin: -30px -30px 30px -30px; /* Ajusta para preencher a largura do container */
        }
        
        .form-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        
        .back-btn {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .back-btn:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .input-box {
            margin-bottom: 15px; /* Ajuste para espaçamento entre os inputs */
        }
        
        .input-box label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .input-box input[type="text"],
        .input-box input[type="email"],
        .input-box input[type="password"],
        .input-box input[type="date"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .input-box input[type="text"]:focus,
        .input-box input[type="email"]:focus,
        .input-box input[type="password"]:focus,
        .input-box input[type="date"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }

        .input-box input::placeholder {
            color: #999;
        }

        .form-group.form-check {
            grid-column: 1 / -1; /* Ocupa todas as colunas */
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .form-check-input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 15px;
            color: var(--dark-gray);
        }

        .form-check-label a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }
        
        .btn-default {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 25px;
        }
        
        .btn-default:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
            .form-header {
                margin: -20px -20px 20px -20px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-title {
                font-size: 24px;
            }
            .back-btn {
                font-size: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .form-container {
                padding: 15px;
            }
            .form-header {
                padding: 20px 15px;
                margin: -15px -15px 15px -15px;
            }
            .form-title {
                font-size: 22px;
            }
            .back-btn {
                font-size: 18px;
                left: 15px;
            }
            .btn-default {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <main class="form-container">
        <div class="form-header">
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="form-title">Cadastro de Gestor</h1>
        </div>
        
        <form action="/HOTEL/actions/adicionar_gestor.php" method="post" id="manager-form">
            <div class="form-grid">
                <div class="input-box">
                    <label for="manager-name">Nome Completo</label>
                    <input type="text" name="name" id="manager-name" placeholder="Digite o nome completo" required>
                </div>

                <div class="input-box">
                    <label for="manager-email">E-mail</label>
                    <input type="email" name="email" id="manager-email" placeholder="exemplo@rodeohotel.com" required>
                </div>

                <div class="input-box">
                    <label for="manager-password">Senha</label>
                    <input type="password" name="password" id="manager-password" placeholder="Crie uma senha" required>
                </div>

                <div class="input-box">
                    <label for="manager-telefone">Telefone</label>
                    <input type="text" name="telefone" id="manager-telefone" placeholder="+00 (00)00000-0000" required>
                </div>

                <div class="input-box">
                    <label for="manager-cpf">CPF</label>
                    <input type="text" name="cpf" id="manager-cpf" placeholder="000.000.000-00" maxlength="14" required>
                </div>

                <div class="input-box">
                    <label for="manager-cep">CEP:</label>
                    <input type="text" id="manager-cep" name="cep" maxlength="9" placeholder="00000-000">
                </div>

                <div class="input-box">
                    <label for="manager-endereco">Endereço:</label>
                    <input type="text" id="manager-endereco" name="endereco" placeholder="Rua, número, complemento, bairro, cidade - UF" required>
                </div>

                <div class="input-box">
                    <label for="manager-birthdate">Data de Nascimento</label>
                    <input type="date" name="birthdate" id="manager-birthdate" required>
                </div>

                <input type="hidden" name="perfil" value="1">

                <button type="submit" name="submit" class="btn-default">Cadastrar Gestor</button>
            </div>
        </form>
    </main>

    <script>
        // Máscara para CPF
        document.getElementById('manager-cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 3) {
                value = value.replace(/^(\d{3})(\d)/g, '$1.$2');
            }
            if (value.length > 6) {
                value = value.replace(/^(\d{3})\.(\d{3})(\d)/g, '$1.$2.$3');
            }
            if (value.length > 9) {
                value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/g, '$1.$2.$3-$4');
            }
            if (value.length > 11) {
                value = value.substring(0, 14);
            }

            e.target.value = value;
        });

        // Máscara para telefone (com +55 e DDD)
        document.getElementById('manager-telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            // Adiciona o código do país +55 fixo se ainda não estiver presente e for um número brasileiro
            if (!value.startsWith('55')) {
                value = '55' + value;
            }

            // Limita ao máximo de 13 dígitos após o + (55 + 2 DDD + 9 número)
            value = value.substring(0, 13);

            // Aplica a máscara: +55 (DD) 9XXXX-XXXX ou +55 (DD) XXXX-XXXX
            if (value.length >= 2) {
                value = value.replace(/^(\d{2})/, '+$1 ');
            }
            if (value.length >= 4) {
                value = value.replace(/^\+(\d{2}) (\d{2})/, '+$1 ($2)');
            }
            if (value.length >= 9) {
                value = value.replace(/^\+(\d{2}) \((\d{2})\)(\d{5})(\d{0,4}).*/, '+$1 ($2) $3-$4');
            } else if (value.length >= 8) { // Para números de 8 dígitos (sem o 9 na frente)
                value = value.replace(/^\+(\d{2}) \((\d{2})\)(\d{4})(\d{0,4}).*/, '+$1 ($2) $3-$4');
            }
            
            e.target.value = value.trim();
        });

        // Máscara e busca de CEP
        document.getElementById('manager-cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });

        document.getElementById('manager-cep').addEventListener('blur', function() {
            var cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        // Monta o endereço com rua, bairro, cidade e estado
                        let enderecoCompleto = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                        document.getElementById('manager-endereco').value = enderecoCompleto;
                    } else {
                        alert('CEP não encontrado.');
                        document.getElementById('manager-endereco').value = '';
                    }
                })
                .catch(() => {
                    alert('Erro ao buscar CEP.');
                    document.getElementById('manager-endereco').value = '';
                });
            } else if (cep.length > 0) {
                alert('Formato de CEP inválido. Digite 8 dígitos.');
                document.getElementById('manager-endereco').value = '';
            }
        });
    </script>
</body>
</html>