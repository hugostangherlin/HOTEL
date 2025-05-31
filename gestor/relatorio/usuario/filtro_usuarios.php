<?php
require_once '../../../config/config.php'; // ajuste o caminho conforme sua estrutura

// Buscar relatórios do tipo 'Usuários'
$sql = "SELECT * FROM relatorio WHERE Tipo_Relatorio = 'Usuários' ORDER BY Data_Relatorio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$relatorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Filtro - Relatório de Usuários</h2>

<form method="GET" action="relatorio_usuarios_pdf.php">
    <label for="perfil">Perfil:</label>
    <select name="perfil" id="perfil">
        <option value="">Todos</option>
        <option value="1">Gestor</option>
        <option value="2">Hóspede</option>
    </select>

    <button type="submit">Gerar Relatório</button>
</form>

<hr>

<h3>Relatórios Gerados</h3>

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th>Codigo do Relatório</th>
            <th>Data</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
<tbody>
    <?php if (count($relatorios) > 0): ?>
        <?php foreach ($relatorios as $rel): ?>
            <tr>
                <td><?= htmlspecialchars($rel['ID_Relatorio']) ?></td>
                <td><?= htmlspecialchars($rel['Data_Relatorio']) ?></td>
                <td><?= htmlspecialchars($rel['Tipo_Relatorio']) ?></td>
                <td><?= htmlspecialchars($rel['Descricao']) ?></td>
                <td><a href="/HOTEL/relatorios/usuario/<?= htmlspecialchars($rel['Arquivo']) ?>" target="_blank">Ver PDF</a>
</td>
                <td>
                    <a href="../../actions/excluir_relatorio.php?id=<?= $rel['ID_Relatorio'] ?>"
                       onclick="return confirm('Tem certeza que deseja excluir este relatório?')"
                       style="color: red; text-decoration: none;">
                        🗑️ Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">Nenhum relatório gerado ainda.</td></tr>
    <?php endif; ?>
</tbody>
</table>
