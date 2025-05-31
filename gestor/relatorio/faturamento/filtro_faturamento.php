<?php
require_once '../../../config/config.php';

// Buscar relatórios do tipo 'Faturamento'
$sql = "SELECT * FROM relatorio WHERE Tipo_Relatorio = 'Faturamento' ORDER BY Data_Relatorio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$relatorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Filtro - Relatório de Faturamento</h2>

<form method="GET" action="relatorio_faturamento_pdf.php">
    <label for="data_inicio">Data Início:</label>
    <input type="date" name="data_inicio" id="data_inicio">

    <label for="data_fim">Data Fim:</label>
    <input type="date" name="data_fim" id="data_fim">

    <label for="forma_pagamento">Forma de Pagamento:</label>
    <select name="forma_pagamento" id="forma_pagamento">
        <option value="">Todas</option>
        <option value="Cartão de Crédito">Cartão de Crédito</option>
        <option value="Boleto">Boleto</option>
        <option value="Pix">Pix</option>
        <option value="Dinheiro">Dinheiro</option>
    </select>

    <button type="submit">Gerar Relatório</button>
</form>

<hr>

<h3>Relatórios de Faturamento Gerados</h3>

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th>Código do Relatório</th>
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
                   <td>
  <a href="/HOTEL/relatorios/faturamento/<?= htmlspecialchars($rel['Arquivo']) ?>" target="_blank">Ver PDF</a>
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
            <tr><td colspan="5" style="text-align: center;">Nenhum relatório gerado ainda.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
