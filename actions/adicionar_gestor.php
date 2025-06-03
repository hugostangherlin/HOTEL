<?php
session_start();
require '../config/config.php';

// Adicionando SweetAlert2 no cabeçalho
echo '<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            font-family: "Arial", sans-serif;
            border-radius: 10px;
        }
    </style>
</head>
<body>';

if (isset($_POST['submit'])) {
    $nome = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone');
    $senha = $_POST['password'];
    $cpf = filter_input(INPUT_POST, 'cpf');
    $endereco = filter_input(INPUT_POST, 'endereco');
    $birthdate = $_POST['birthdate'];

    $perfil = 1; 

    if ($nome && $email && $telefone && $senha && $perfil) {
        try {
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();

            if ($sql->rowCount() === 0) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = $pdo->prepare("INSERT INTO usuarios (Nome, Email, Telefone, Senha, CPF, Endereco, Data_Nascimento, Perfil_ID_Perfil)
                                      VALUES (:nome, :email, :telefone, :senha, :cpf, :endereco, :data_nascimento, :perfil)");
                $sql->bindValue(':nome', $nome);
                $sql->bindValue(':email', $email);
                $sql->bindValue(':telefone', $telefone);
                $sql->bindValue(':senha', $senhaHash);
                $sql->bindValue(':cpf', $cpf);
                $sql->bindValue(':endereco', $endereco);
                $sql->bindValue(':data_nascimento', $birthdate);
                $sql->bindValue(':perfil', $perfil);
                $sql->execute();

                // Inicia sessão com os dados do novo usuário
                $_SESSION['usuario'] = [
                    'ID' => $pdo->lastInsertId(),
                    'nome' => $nome,
                    'perfil' => $perfil
                ];
                
                echo '<script>
                Swal.fire({
                    title: "Cadastro realizado!",
                    text: "Bem-vindo(a), '.$nome.'! Você será redirecionado para o dashboard.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ótimo!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/HOTEL/gestor/dashboard.php";
                    }
                });
                </script>';
                exit;
            } else {
                echo '<script>
                Swal.fire({
                    title: "E-mail já cadastrado",
                    text: "O e-mail '.$email.' já está em uso. Por favor, utilize outro e-mail ou faça login.",
                    icon: "warning",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Entendi"
                });
                </script>';
            }
        } catch (PDOException $e) {
            echo '<script>
            Swal.fire({
                title: "Erro no cadastro",
                text: "Ocorreu um erro: '.str_replace("'", "\\'", $e->getMessage()).'",
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "Fechar"
            });
            </script>';
        }
    } else {
        $missingFields = [];
        if (!$nome) $missingFields[] = "Nome";
        if (!$email) $missingFields[] = "E-mail válido";
        if (!$telefone) $missingFields[] = "Telefone";
        if (!$senha) $missingFields[] = "Senha";
        
        echo '<script>
        Swal.fire({
            title: "Campos obrigatórios",
            html: "Por favor, preencha todos os campos obrigatórios:<br><br><ul style=\"text-align: left; margin-left: 20px;\"><li>'.implode('</li><li>', $missingFields).'</li></ul>",
            icon: "info",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Entendi"
        });
        </script>';
    }
}

echo '</body>
</html>';

