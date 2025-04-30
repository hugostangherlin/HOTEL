<?php
require '../config/config.php';
?>

<!-- Modal (pop-up) do Formulário de Cadastro -->
<div id="register-modal" class="modal">
    <div class="modal-content">
      <span id="close-modal" class="close">&times;</span>
      <h2>Criar conta</h2>
      <form action="hospede/actions/adicionar_action.php" method="post" id="form">
        <div id="input_container">
          <div class="input-box">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" placeholder="Digite seu nome" required>
          </div>
          <div class="input-box">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="exemplo@gmail.com" required>
          </div>
          <div class="input-box">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" required>
          </div>
          <div class="input-box">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" placeholder="+00 (00)0000-0000" required>
          </div>
          <div class="input-box">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required>
          </div>
          <div class="input-box">
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" placeholder="Digite seu endereço" required>
          </div>
          <div class="input-box">
            <label for="birthdate">Data de Nascimento</label>
            <input type="date" name="birthdate" id="birthdate" required>
          </div>
          <button type="submit" name="submit" class="btn-default">Cadastrar</button>
        </div>
      </form>
    </div>
</div>

<script>
  // Abrir o modal de cadastro
  const openModalButton = document.getElementById("open-register-form");
  const modal = document.getElementById("register-modal");
  const closeModalButton = document.getElementById("close-modal");

  openModalButton.onclick = function() {
    modal.style.display = "block";
  }

  // Fechar o modal de cadastro
  closeModalButton.onclick = function() {
    modal.style.display = "none";
  }

  // Fechar o modal quando clicar fora dele
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>
