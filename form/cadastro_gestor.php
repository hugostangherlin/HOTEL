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
        }
        
        .form-header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 600;
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
            font-size: 18px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .back-btn:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .input-field {
            position: relative;
        }
        
        .input-field i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }
        
        .form-control.date {
            padding-left: 15px;
        }
        
        .submit-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .form-header {
                padding: 20px 15px;
            }
            
            .form-title {
                font-size: 20px;
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
        
        <form method="POST" action="/HOTEL/actions/adicionar_gestor.php" class="form-body">
            <div class="form-grid">
                <!-- Nome -->
                <div class="input-group">
                    <label for="name" class="input-label">
                        <i class="fas fa-user"></i>
                        Nome Completo
                    </label>
                    <div class="input-field">
                        <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email" class="input-label">
                        <i class="fas fa-envelope"></i>
                        E-mail
                    </label>
                    <div class="input-field">
                        <input type="email" name="email" id="email" class="form-control" placeholder="exemplo@rodeohotel.com" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <!-- Senha -->
                <div class="input-group">
                    <label for="password" class="input-label">
                        <i class="fas fa-lock"></i>
                        Senha
                    </label>
                    <div class="input-field">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Crie uma senha segura" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <!-- Telefone -->
                <div class="input-group">
                    <label for="telefone" class="input-label">
                        <i class="fas fa-phone"></i>
                        Telefone
                    </label>
                    <div class="input-field">
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="(00) 00000-0000">
                        <i class="fas fa-phone"></i>
                    </div>
                </div>

                <!-- CPF -->
                <div class="input-group">
                    <label for="cpf" class="input-label">
                        <i class="fas fa-id-card"></i>
                        CPF
                    </label>
                    <div class="input-field">
                        <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" maxlength="14">
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="input-group">
                    <label for="endereco" class="input-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Endereço
                    </label>
                    <div class="input-field">
                        <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Rua, número, complemento">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>

                <!-- Data de Nascimento -->
                <div class="input-group">
                    <label for="birthdate" class="input-label">
                        <i class="fas fa-calendar-alt"></i>
                        Data de Nascimento
                    </label>
                    <div class="input-field">
                        <input type="date" name="birthdate" id="birthdate" class="form-control date">
                    </div>
                </div>
            </div>

            <!-- Campo oculto para definir o perfil -->
            <input type="hidden" name="perfil" value="1">

            <button type="submit" name="submit" class="submit-btn">
                <i class="fas fa-user-plus"></i> Cadastrar Gestor
            </button>
        </form>
    </main>

    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                if (value.length > 10) {
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                }
            }
            e.target.value = value;
        });
    </script>
</body>
</html>