<?php 
require '../Config/config.php';
?>
<!-- PARA GESTORES -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Gestores</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<main id="form_container">
    <div id="form_header">
      <h1 id="form_title">
        Criar conta
      </h1>
      <button class="btn-default">
        <i class="fa-solid fa-right-to-bracket"></i>
      </button>
    </div>
    <form method="POST" action="/HOTEL/actions/adicionar_gestor.php"id="form">
  <div id="input_container">
    <!-- Nome -->
    <div class="input-box">
      <label for="name" class="form-label">Nome</label>
      <div class="input-field">
        <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome" required>
        <i class="fa-regular fa-user"></i>
      </div>
    </div>

    <!-- Email -->
    <div class="input-box">
      <label for="email" class="form-label">E-mail</label>
      <div class="input-field">
        <input type="email" name="email" id="email" class="form-control" placeholder="exemplo@gmail.com" required>
        <i class="fa-regular fa-envelope"></i>
      </div>
    </div>

    <!-- Senha -->
    <div class="input-box">
      <label for="password" class="form-label">Senha</label>
      <div class="input-field">
        <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua senha" required>
        <i class="fa-solid fa-key"></i>
      </div>
    </div>

    <!-- Telefone -->
    <div class="input-box">
      <label for="telefone" class="form-label">Telefone</label>
      <div class="input-field">
        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="+00 (00)0000-0000">
        <i class="fa-regular fa-phone"></i>
      </div>
    </div>

    <!-- CPF -->
    <div class="input-box">
      <label for="cpf" class="form-label">CPF</label>
      <div class="input-field">
        <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" maxlength="14">
        <i class="fa-regular fa-id-card"></i>
      </div>
    </div>

    <!-- Endereço -->
    <div class="input-box">
      <label for="endereco" class="form-label">Endereço</label>
      <div class="input-field">
        <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Digite seu endereço">
        <i class="fa-solid fa-location-dot"></i>
      </div>
    </div>

    <!-- Data de Nascimento -->
    <div class="input-box">
      <label for="birthdate" class="form-label">Data de Nascimento</label>
      <div class="input-field">
        <input type="date" name="birthdate" id="birthdate" class="form-control">
      </div>
    </div>

    <!-- Campo oculto para definir o perfil -->
    <input type="hidden" name="perfil" value="1">

    <br>

    <input type="submit" name="submit" class="btn-default" value="Cadastrar">
  </div>
</fo>

</body>
</html>