<?php
require_once '../../../config/config.php';

// Buscar relatórios do tipo 'Faturamento'
$sql = "SELECT * FROM relatorio WHERE Tipo_Relatorio = 'Faturamento' ORDER BY Data_Relatorio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$relatorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Faturamento</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/HOTEL/rodeo.ico">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --primary: #FB4D46;
        --primary-light: #FFE9E9;
        --primary-dark: #E04141;
        --dark: #1A1A2E;
        --light: #F8F9FA;
        --gray: #E9ECEF;
        --text: #333333;
        --text-light: #6C757D;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--light);
        color: var(--text);
        line-height: 1.6;
        padding: 2rem;
    }

    h2, h3 {
        color: var(--dark);
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    h2::after, h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--primary);
    }

    hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, var(--primary), transparent);
        margin: 2rem 0;
    }

    /* Formulário de filtro */
    form {
        background-color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        border-top: 3px solid var(--primary);
    }

    .form-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .form-control {
        flex: 1;
        min-width: 200px;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.9rem;
    }

    select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
        background-color: white;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(251, 77, 70, 0.15);
    }

    button[type="submit"] {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(251, 77, 70, 0.2);
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    button[type="submit"]:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 77, 70, 0.3);
    }

    /* Tabela de relatórios */
    .table-container {
        overflow-x: auto;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    thead th {
        background-color: var(--dark);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 500;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        transition: var(--transition);
        border-bottom: 1px solid var(--gray);
    }

    tbody tr:nth-child(even) {
        background-color: var(--primary-light);
    }

    tbody tr:hover {
        background-color: rgba(251, 77, 70, 0.05);
    }

    tbody td {
        padding: 1rem;
        font-size: 0.95rem;
    }

    /* Links de ação */
    .action-links {
        display: flex;
        gap: 1rem;
    }

    .action-links a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.5rem 0;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .action-links a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .action-links a.delete {
        color: #DC3545;
    }

    .action-links a.delete:hover {
        color: #C82333;
    }

    /* Mensagem de lista vazia */
    .empty-message {
        text-align: center;
        padding: 2rem;
        color: var(--text-light);
        font-style: italic;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        body {
            padding: 1rem;
        }

        .form-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-control {
            min-width: 100%;
        margin-bottom: 0.5rem;
        margin-top: 0.5rem;
        width: 100%;
        flex: none;
        display: block;
        box-sizing: border-box;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
        background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        button[type="submit"] {
            width: 100%;
        }

        thead th, tbody td {
            padding: 0.75rem;
            font-size: 0.85rem;
        }

        .action-links {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
</head>
<body>
<h2>Relatório de Faturamento</h2>

<form method="GET" action="relatorio_faturamento_pdf.php">
    <label for="mes">Mês:</label>
    <select name="mes" id="mes">
        <option value="">Todos</option>
        <option value="01">Janeiro</option>
        <option value="02">Fevereiro</option>
        <option value="03">Março</option>
        <option value="04">Abril</option>
        <option value="05">Maio</option>
        <option value="06">Junho</option>
        <option value="07">Julho</option>
        <option value="08">Agosto</option>
        <option value="09">Setembro</option>
        <option value="10">Outubro</option>
        <option value="11">Novembro</option>
        <option value="12">Dezembro</option>
    </select>

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
</body>
</html>
