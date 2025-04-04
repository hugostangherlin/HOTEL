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

<h2>Editar Quarto</h2>
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
    <input type="number" name="capacidade" id="capacidade" value="<?php echo $info['Capacidade'] ?? ''; ?>" required />
    <br /><br />
    
    <label for="categoria">Categoria:</label>
    <input type="text" name="categoria" id="categoria" value="<?php echo $info['ID_Categoria'] ?? ''; ?>" required />
    <br /><br />
    
    <input type="submit" value="Salvar" />
</form>
