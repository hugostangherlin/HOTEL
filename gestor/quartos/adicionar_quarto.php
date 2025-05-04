<?php
require_once '../../config/config.php';

$sth  = $pdo->prepare("SELECT * FROM categoria");
$sth->execute();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Quarto</title>
  <link rel="stylesheet" href="criarquarto.css">
</head>

<body>
  <main id="form_container">
    <div id="form_header">
      <h1 id="form_title">Adicionar Novo Quarto</h1>
    </div>

    <form action="../../actions/adicionar_quarto.php" method="POST" enctype="multipart/form-data" id="form">
      <!-- Categoria -->
      <div class="input-box">
        <label for="category" class="form-label">Categoria</label>
        <div class="input-field">
          <select name="category" id="category" class="form-control" >
            <option value="">Selecione</option>
            <?php while ($categ = $sth->fetch(PDO::FETCH_ASSOC)) : ?>
              <option value="<?= $categ["ID_Categoria"]; ?>"><?= $categ["Nome"]; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <!-- Status -->
      <div class="input-box">
        <label for="status" class="form-label">Status</label>
        <div class="input-field">
          <select name="status" id="status" class="form-control">
            <option value="">Selecione</option>
            <option value="disponivel">Disponível</option>
            <option value="ocupado">Ocupado</option>
            <option value="manutencao">Manutenção</option>
          </select>
        </div>
      </div>

      <!-- Capacidade -->
      <div class="input-box">
        <label for="capacity" class="form-label">Capacidade</label>
        <div class="input-field">
          <input type="number" name="capacity" id="capacity" class="form-control">
        </div>
      </div>

      <!-- Preço da Diária -->
      <div class="input-box">
        <label for="preco_diaria" class="form-label">Preço da Diária</label>
        <div class="input-field">
          <input type="number" name="preco_diaria" id="preco_diaria" class="form-control" step="0.01">
        </div>
      </div>

      <!-- Foto do Quarto -->
      <div class="input-box">
        <label for="foto" class="form-label">Foto do Quarto</label>
        <div class="input-field">
          <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>
      </div>

      <!-- Botão de envio -->
      <div class="input-box">
        <button type="submit" name="submit" class="btn-default">Cadastrar Quarto</button>
      </div>
    </form>
  </main>
</body>

</html>
