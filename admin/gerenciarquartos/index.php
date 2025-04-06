<?php 
require 'conexao.php';


$lista = [];

$sql = $pdo->query("SELECT * FROM quartos");

if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Quarto</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    
</body>
</html>
<a href="criarquarto.php">Adicionar quarto</a>

<table border="1" width="100%">
    <tr>
        <th>Número do Quarto</th>
        <th>Categoria</th>
        <th>Status</th>
        <th>Capacidade</th>
    </tr>
    <!--Agora vamos montar o restante da tabela com informações do BD-->
    <?php foreach($lista as $quartos): ?>
            <tr>
                <td><?=$quartos['ID_Quarto'];?></td>
                <td><?=$quartos['ID_Categoria'];?></td>
                <td><?=$quartos['Status'];?></td>
                <td><?php echo $quartos['Capacidade']; ?></td>
                <td>
                    <a href="editar.php?id=<?=$quartos['ID_Quarto'];?>">[ Editar ]</a>
                    <a href="excluir.php?id=<?=$quartos['ID_Quarto'];?>" onclick="return confirm('Você tem certeza que deseja excluir esse quarto?')">[ Excluir ]</a>
                </td>
            </tr>
    <?php endforeach; ?>
    
</table>
