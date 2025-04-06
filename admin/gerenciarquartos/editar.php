<?php
require 'conexao.php';
$info = [];
$id = filter_input(INPUT_GET, 'id');
if($id){
    $sql = $pdo->prepare("SELECT * FROM quartos WHERE ID_Quarto = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();


    if($sql->rowCount() > 0){
        

        $info = $sql->fetch(PDO::FETCH_ASSOC);
    }else{
        header("Location: index.php");
        exit;
    }

}else{
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto</title>
    <link rel="stylesheet" href="editar.css">
</head>
<body>
    
</body>
</html>

<form method="POST" action="editar_action.php">
<input type="hidden" name="id" value="<?php echo $info['ID_Quarto'];?>" />
<label for="status">
          Status
        </label>
        <div>
          <select name="status" id="status" require>
          <option value="">Selecione</option>
          <option value="disponivel" <?php echo ($info['Status'] == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
          <option value="ocupado" <?php echo ($info['Status'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
        <option value="manutencao" <?php echo ($info['Status'] == 'manutencao') ? 'selected' : ''; ?>>Manutenção</option>
    </select>
    <br /><br />
    
    <label for="capacidade">Capacidade:</label>
    <input type="number" name="capacity" id="capacidade" value="<?php echo $info['Capacidade'] ?? ''; ?>" required />
    <br /><br />
    
    <label for="categoria">Categoria:</label>
    <input type="text" name="category" id="categoria" value="<?php echo $info['ID_Categoria'] ?? ''; ?>" required />
    <br /><br />
    
    <input type="submit" value="Salvar" />
</form>
