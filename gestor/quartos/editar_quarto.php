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
<!-- HTML -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto</title>
    <link rel="stylesheet" href="quartos.css">
    <style>
        #form_container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Título do formulário */
        #form_title {
            font-size: 24px;
            font-weight: 500;
            color: #272727;
            text-align: center;
            position: relative;
            margin-bottom: 16px;
        }

        /* Linha decorativa abaixo do título */
        #form_title::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 3px;
            border-radius: 30px;
            background-color: #6366f1;
            left: 50%;
            transform: translateX(-50%);
            bottom: -8px;
        }

        /* Estrutura geral do formulário */
        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Estilização dos rótulos */
        label {
            font-size: 14px;
            color: #404044;
            font-weight: 500;
            margin-bottom: 4px;
        }

        /* Campos de entrada e seleção */
        select,
        input[type="number"],
        input[type="text"],
        input[type="file"] {
            padding: 10px;
            border: none;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        /* Destaque no foco dos campos */
        select:focus,
        input:focus {
            outline: 2px solid #6366f1;
        }

        /* Botão de envio (Salvar) */
        input[type="submit"] {
            background-color: #6366f1;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Efeito hover no botão */
        input[type="submit"]:hover {
            background-color: #818cf8;
            transform: scale(1.03);
        }
    </style>
</head>

<body>

    <!-- Formulário -->
    <form method="POST" action="../../actions/editar_quarto.php" id="form_container" enctype="multipart/form-data">
        <h2 id="form_title">Editar Quarto</h2>
        <!-- Campo oculto com o ID do quarto -->
        <input type="hidden" name="id" value="<?php echo $info['ID_Quarto']; ?>" />

        <!-- Status -->
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">Selecione</option>
            <option value="disponivel" <?php echo ($info['Status'] == 'Disponível') ? 'selected' : ''; ?>>Disponível</option>
            <option value="ocupado" <?php echo ($info['Status'] == 'Ocupado') ? 'selected' : ''; ?>>Ocupado</option>
            <option value="manutencao" <?php echo ($info['Status'] == 'Manutenção') ? 'selected' : ''; ?>>Manutenção</option>
        </select>

        <!-- Capacidade -->
        <label for="capacidade">Capacidade:</label>
        <input type="number" name="capacity" id="capacidade" value="<?php echo $info['Capacidade'] ?? ''; ?>" />

        <!-- Categoria -->
        <label for="categoria">Categoria:</label>
        <select name="category" id="categoria" >
            <option value="">Selecione</option>
            <!-- Loop para listar as categorias disponíveis -->
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['ID_Categoria']; ?>"
                <?= ($info['Categoria_ID_Categoria'] == $categoria['ID_Categoria']) ? 'selected' : ''; ?>>
                <?= $categoria['Nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Preço Diário -->
        <label for="preco_diaria">Preço da Diária:</label>
        <input type="number" name="preco_diaria" id="preco_diaria" value="<?php echo $info['Preco_Diaria'] ?? ''; ?>" required />

        <!-- Foto -->
        <label for="foto">Foto do Quarto:</label>
        <input type="file" name="foto" id="foto" accept="image/*" />

        <br /><br />

        <input type="submit" value="Salvar" />
    </form>

</body>

</html>
