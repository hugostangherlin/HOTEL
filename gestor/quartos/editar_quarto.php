<?php 
require_once '../../config/config.php';

// Array para armazenar as informações do quarto a ser editado
$info = [];

// Captura o ID do quarto da URL (via GET)
$id = filter_input(INPUT_GET, 'id');

// Inicia um array para armazenar as categorias disponíveis
$categorias = [];

// Consulta todas as categorias no banco para exibir no campo de seleção
$sql = $pdo->query("SELECT ID_Categoria, Nome FROM categoria");

// Verifica se encontrou categorias e armazena no array
if ($sql->rowCount() > 0) {
    $categorias = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// Se o ID do quarto foi enviado pela URL
if ($id) {
    // Prepara a consulta para buscar os dados do quarto com base no ID
    $sql = $pdo->prepare("SELECT * FROM quarto WHERE ID_Quarto = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();

    // Se encontrou o quarto, armazena os dados no array $info
    if ($sql->rowCount() > 0) {
        $info = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        // Se o quarto não for encontrado volta para o painel
        header("Location: index.php");
        exit;
    }
} else {
    // Volta para o painel de gerenciar quarto
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto | Rodeo Hotel</title>
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
            padding: 30px;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-title {
            color: var(--secondary-color);
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .current-image {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-image img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
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
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form method="POST" action="../../actions/editar_quarto.php" enctype="multipart/form-data">
            <div class="form-header">
                <h1 class="form-title">Editar Quarto <?php echo $info['ID_Quarto']; ?></h1>
            </div>
            
            <!-- Campo oculto com o ID do quarto -->
            <input type="hidden" name="id" value="<?php echo $info['ID_Quarto']; ?>">
            
            <!-- Mostrar imagem atual -->
            <?php if(!empty($info['Foto'])): ?>
            <div class="current-image">
                <p><strong>Imagem atual:</strong></p>
                <img src="../../uploads/<?php echo htmlspecialchars($info['Foto']); ?>" alt="Foto atual do quarto">
            </div>
            <?php endif; ?>
            
            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-info-circle"></i>
                    Status
                </label>
                <select name="status" id="status" class="form-control" >
                    <option value="">Selecione o status</option>
                    <option value="Disponivel" <?php echo ($info['Status'] == 'Disponivel') ? 'selected' : ''; ?>>Disponível</option>
                    <option value="Ocupado" <?php echo ($info['Status'] == 'Ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                    <option value="Manutencao" <?php echo ($info['Status'] == 'Manutencao') ? 'selected' : ''; ?>>Manutenção</option>
                </select>
            </div>
            
            <!-- Capacidade -->
            <div class="form-group">
                <label for="capacidade" class="form-label">
                    <i class="fas fa-users"></i>
                    Capacidade
                </label>
                <input type="number" name="capacity" id="capacidade" class="form-control" 
                       min="1" value="<?php echo htmlspecialchars($info['Capacidade'] ?? ''); ?>" >
            </div>
            
            <!-- Categoria -->
            <div class="form-group">
                <label for="categoria" class="form-label">
                    <i class="fas fa-list"></i>
                    Categoria
                </label>
                <select name="category" id="categoria" class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['ID_Categoria']; ?>"
                            <?= ($info['Categoria_ID_Categoria'] == $categoria['ID_Categoria']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($categoria['Nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Preço Diário -->
            <div class="form-group">
                <label for="preco_diaria" class="form-label">
                    <i class="fas fa-money-bill-wave"></i>
                    Preço da Diária
                </label>
                <input type="number" name="preco_diaria" id="preco_diaria" class="form-control" 
                       step="0.01" min="0" value="<?php echo htmlspecialchars($info['Preco_Diaria'] ?? ''); ?>" >
            </div>
            
            <!-- Foto -->
            <div class="form-group">
                <label for="foto" class="form-label">
                    <i class="fas fa-camera"></i>
                    Nova Foto do Quarto (opcional)
                </label>
                <input type="file" name="foto" id="foto" class="form-control file" accept="image/*">
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </form>
    </div>

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