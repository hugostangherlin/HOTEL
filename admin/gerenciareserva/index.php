<?php 
require 'conexao.php';

$lista = [];

$sql = $pdo->query("SELECT * FROM reservas");

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
    
    <?php foreach($lista as $reservas): ?>
            <tr>
                <td><?=$reservas['ID_Reserva'];?></td>
                <td><?=$reservas['ID_Cliente'];?></td>
                <td><?=$reservass['ID_Quarto'];?></td>
                <td><?=$reservas['Status'];?></td>
                <td><?=$reservas['Check_in'];?></td>
                <td><?=$reservas['Check_out'];?></td>
                <td><?php echo $reservas['Data_Reserva']; ?></td>
                <td>
                    <a href="editar.php?id=<?=$reservas['ID_Reserva'];?>">[ Editar ]</a>
                    <a href="excluir.php?id=<?=$reservas['ID_Reserva'];?>" onclick="return confirm('Você tem certeza que deseja excluir esse quarto?')">[ Excluir ]</a>
                </td>
            </tr>
    <?php endforeach; ?>
    
</table>

