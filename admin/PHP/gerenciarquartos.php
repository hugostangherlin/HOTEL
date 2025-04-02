<?php
require 'conexao.php';

$sth  = $pdo->prepare("SELECT * FROM categoria");
$sth->execute();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="gerenciarquarto.css">
  <title>Gerenciamento de Quarto</title>
</head>

<body>
  <main id="form_container">
    <div id="form_header">
      <h1 id="form_title">
        Criar Quartos
      </h1>
      <button class="btn_default">
        <i></i>
      </button>
    </div>

    <form action="adicionarquarto.php" method="POST" id="form">
      <!-- Categoria -->
      <div class="input-box">
        <label for="category" class="form-label">
          Categoria
        </label>
        <div class="input-field">
          <select name="category" id="category" class="form-control" require>
            <option value="">Selecione</option>
          <?php
              while($categ = $sth->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='{$categ["ID_Categoria"]}'>{$categ["Nome"]}</option>";
              }
           ?>
          </select>
          <i class="#"></i>
        </div>
      </div>
      <!-- Status -->
      <div class="input-box">
        <label for="status" class="form-label">
          Status
        </label>
        <div class="input-field">
          <select name="status" id="status" class="form-control" require>
          <option value="">Selecione</option>
            <option value="disponivel">Disponível</option>
            <option value="ocupado">Ocupado</option>
            <option value="manutencao">Manutenção</option>
          </select>
          <i class="#"></i>
        </div>
      </div>
      <!-- Capacidade -->
      <div class="input-box">
        <label for="number" class="form-label">
          Capacidade
        </label>
        <div class="input-field">
          <input type="number"
            name="capacity"
            id="capacity"
            class="form-control">
              <input type="submit" name="submit" class="btn-default">
              <i class="#"></i> 
              Criar Quarto
              </input>
            </div>
    </form>
  </main>
</body>

</html>
