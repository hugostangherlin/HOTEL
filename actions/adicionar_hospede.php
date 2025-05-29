<?php
session_start();
require '../config/config.php';

if (isset($_POST['submit'])) {
    $nome = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone');
    $senha = $_POST['password'];
    $cpf = filter_input(INPUT_POST, 'cpf');
    $endereco = filter_input(INPUT_POST, 'endereco');
    $birthdate = $_POST['birthdate'];

    $perfil = 2; // ID de Hóspede

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
                    'id' => $pdo->lastInsertId(),
                    'nome' => $nome,
                    'perfil' => $perfil
                ];
                if($_SESSION['checkin']!='' && $_SESSION['checkout']!=''){
                    header("Location: /HOTEL/hospede/pages/detalhes_quarto.php?id=".$_SESSION['id']."&checkin=".$_SESSION['checkin']."&checkout=".$_SESSION['checkout']);
                    exit;
                }
                // Redireciona para a página de hóspede
                header("Location: /HOTEL/hospede/pages/pag_hospede.php");
                exit;

            } else {
                echo "<script>alert('E-mail já cadastrado!');</script>";
            }
        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!');</script>";
    }
}
?>

