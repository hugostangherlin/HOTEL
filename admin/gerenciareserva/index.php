<?php 
require 'conexao.php';

$lista = [];

$sql = $pdo->query("SELECT * FROM reserva");

if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>
<table border="1" width="100%">
    <tr>
        <th>Número da Reserva</th>
        <th>Hóspede</th>
        <th>Número do Quarto</th>
        <th>Status</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Data de Criação da Reserva</th>
    </tr>
    
    <?php foreach($lista as $reserva): ?>
            <tr>
                <td><?=$reserva['ID_Reserva'];?></td>
                <td><?=$reserva['ID_Cliente'];?></td>
                <td><?=$reserva['ID_Quarto'];?></td>
                <td><?=$reserva['Status'];?></td>
                <td><?=$reserva['Check_in'];?></td>
                <td><?=$reserva['Check_out'];?></td>
                <td><?php echo reserva['Data_Reserva']; ?></td>
                <td>
                    <a href="editar.php?id=<?=$quartos['ID_Quarto'];?>">[ Editar ]</a>
                    <a href="excluir.php?id=<?=$quartos['ID_Quarto'];?>" onclick="return confirm('Você tem certeza que deseja excluir esse quarto?')">[ Excluir ]</a>
                </td>
            </tr>
    <?php endforeach; ?>
    
</table>

