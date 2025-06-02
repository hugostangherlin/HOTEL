<?php
require_once '../../config/config.php';

$sth  = $pdo->prepare("SELECT * FROM categoria");
$sth->execute();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Quarto | Rodeo Hotel</title>
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
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
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
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px 12px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.2);
        }
        
        .form-control.file {
            padding: 12px 15px;
        }
        
        .form-control.file::file-selector-button {
            padding: 8px 12px;
            background-color: var(--light-gray);
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .form-control.file::file-selector-button:hover {
            background-color: #e9ecef;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .submit-btn:hover {
            background-color: #e0413a;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
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
            <h1 class="form-title">Adicionar Novo Quarto</h1>
        </div>
        
        <form action="../../actions/adicionar_quarto.php" method="POST" enctype="multipart/form-data" class="form-body">
            <div class="form-grid">
                <!-- Categoria -->
                <div class="input-group">
                    <label for="category" class="input-label">
                        <i class="fas fa-list"></i>
                        Categoria
                    </label>
                    <div class="input-field">
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Selecione uma categoria</option>
                            <?php while ($categ = $sth->fetch(PDO::FETCH_ASSOC)) : ?>
                                <option value="<?= $categ["ID_Categoria"]; ?>"><?= $categ["Nome"]; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="input-group">
                    <label for="status" class="input-label">
                        <i class="fas fa-info-circle"></i>
                        Status
                    </label>
                    <div class="input-field">
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Selecione o status</option>
                            <option value="Disponivel">Disponível</option>
                            <option value="Ocupado">Ocupado</option>
                            <option value="Manutencao">Manutenção</option>
                        </select>
                    </div>
                </div>

                <!-- Capacidade -->
                <div class="input-group">
                    <label for="capacity" class="input-label">
                        <i class="fas fa-users"></i>
                        Capacidade
                    </label>
                    <div class="input-field">
                        <input type="number" name="capacity" id="capacity" class="form-control" min="1" placeholder="Número de pessoas" required>
                    </div>
                </div>

                <!-- Preço da Diária -->
                <div class="input-group">
                    <label for="preco_diaria" class="input-label">
                        <i class="fas fa-money-bill-wave"></i>
                        Preço da Diária
                    </label>
                    <div class="input-field">
                        <input type="number" name="preco_diaria" id="preco_diaria" class="form-control" step="0.01" min="0" placeholder="R$ 0,00" required>
                    </div>
                </div>

                <!-- Foto do Quarto -->
                <div class="input-group">
                    <label for="foto" class="input-label">
                        <i class="fas fa-camera"></i>
                        Foto do Quarto
                    </label>
                    <div class="input-field">
                        <input type="file" name="foto" id="foto" class="form-control file" accept="image/*" required>
                    </div>
                </div>

                <!-- Botão de envio -->
                <button type="submit" name="submit" class="submit-btn">
                    <i class="fas fa-plus-circle"></i> Cadastrar Quarto
                </button>
            </div>
        </form>
    </main>

    <script>
        // Formatação automática para o campo de preço
        document.getElementById('preco_diaria').addEventListener('input', function(e) {
            // Garante que o valor tenha sempre duas casas decimais
            let value = parseFloat(e.target.value);
            if (!isNaN(value)) {
                e.target.value = value.toFixed(2);
            }
        });
    </script>
</body>
</html>