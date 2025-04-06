<?php
require 'conexao.php';

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
    $sql = $pdo->prepare("SELECT * FROM quartos WHERE ID_Quarto = :id");
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
    <link rel="stylesheet" href="editar.css">
</head>

<body>

    <!-- Formulário -->
    <form
        method="POST"
        action="editar_action.php"
        id="form_container">
        <h2 id="form_title">Editar Quarto</h2>
        <!-- Campo oculto com o ID do quarto -->
        <input type="hidden" name="id" value="<?php echo $info['ID_Quarto']; ?>" />

        <!--Status -->
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="">Selecione</option>
            <option value="disponivel" <?php echo ($info['Status'] == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
            <option value="ocupado" <?php echo ($info['Status'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
            <option value="manutencao" <?php echo ($info['Status'] == 'manutencao') ? 'selected' : ''; ?>>Manutenção</option>
        </select>


        <!-- Capacidade -->
        <label for="capacidade">Capacidade:</label>
        <input type="number" name="capacity" id="capacidade" value="<?php echo $info['Capacidade'] ?? ''; ?>" required />

        <!-- Categoria -->
        <label for="categoria">Categoria:</label>
        <select name="category" id="categoria" required>
            <option value="">Selecione uma categoria</option>

            <!-- Loop para listar as categorias disponíveis -->
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['ID_Categoria']; ?>"
                    <?= ($info['ID_Categoria'] == $categoria['ID_Categoria']) ? 'selected' : ''; ?>>
                    <?= $categoria['Nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br /><br />

        <input type="submit" value="Salvar" />
    </form>

</body>

</html>