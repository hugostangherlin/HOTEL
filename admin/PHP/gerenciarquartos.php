<?php
require 'conexao.php';
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
      <!-- Categoria
      <div class="input-box">
        <label for="category" class="form-label">
          Categoria
        </label>
        <div class="input-field">
          <select name="category" id="category" class="form-control" require>
          <option value="suite">Suite Master</option>
            <option value="luxo">Luxo</option>
            <option value="standard">Standard</option>
            <option value="economico">Ecônomico</option>
          </select>
          <i class="#"></i>
        </div>
      </div> -->
      <!-- Status -->
      <div class="input-box">
        <label for="status" class="form-label">
          Status
        </label>
        <div class="input-field">
          <select name="status" id="status" class="form-control" require>
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
          <!-- Preço
          <div class="input-box">
            <label for="number" class="form-label">
              Preço
            </label>
            <div class="input-field">
              <input type="number"
                name="price"
                id="price"
                step="0.01"
                min="0"
                class="form-control">
              <br><br>-->
              <input type="submit" name="submit" class="btn-default">
              <i class="#"></i> 
              Criar Quarto
              </input>
            </div>
    </form>
  </main>
</body>

</html>
