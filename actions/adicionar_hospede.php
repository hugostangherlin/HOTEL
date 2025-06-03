<?php
session_start();
require '../config/config.php';

// Início do HTML com estilos e SweetAlert2
echo '<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Hóspede</title>
    <!-- SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            font-family: "Poppins", sans-serif;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .swal2-title {
            color: #2c3e50;
            font-weight: 600;
        }
        .swal2-confirm {
            background-color: #3498db !important;
            transition: all 0.3s;
        }
        .swal2-confirm:hover {
            background-color: #2980b9 !important;
            transform: translateY(-2px);
        }
        .swal2-error {
            border-color: #e74c3c !important;
        }
        .swal2-warning {
            border-color: #f39c12 !important;
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
                    'ID' => $pdo->lastInsertId(),
                    'nome' => $nome,
                    'perfil' => $perfil
                ];
                
                // Verifica se há reserva em andamento
                if(isset($_SESSION['checkin']) && isset($_SESSION['checkout']) && isset($_SESSION['id'])) {
                    echo '<script>
                    Swal.fire({
                        title: "Cadastro realizado com sucesso!",
                        text: "Redirecionando para finalizar sua reserva...",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = "/HOTEL/hospede/pages/detalhes_quarto.php?id='.$_SESSION['id'].'&checkin='.$_SESSION['checkin'].'&checkout='.$_SESSION['checkout'].'";
                        }
                    });
                    </script>';
                } else {
                    echo '<script>
                    Swal.fire({
                        title: "Bem-vindo, '.htmlspecialchars($nome).'!",
                        text: "Seu cadastro foi realizado com sucesso. Você será redirecionado para sua área de hóspede.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = "/HOTEL/hospede/pages/pag_hospede.php";
                        }
                    });
                    </script>';
                }
                exit;

            } else {
                echo '<script>
                Swal.fire({
                    title: "E-mail já cadastrado",
                    html: "O e-mail <strong>'.htmlspecialchars($email).'</strong> já está registrado em nosso sistema.<br><br>Deseja <a href=\'/HOTEL/hospede/pages/login.php\'>fazer login</a> ou recuperar sua senha?",
                    icon: "warning",
                    confirmButtonText: "Entendi",
                    confirmButtonColor: "#f39c12"
                });
                </script>';
            }
        } catch (PDOException $e) {
            echo '<script>
            Swal.fire({
                title: "Erro no cadastro",
                html: "Ocorreu um erro ao processar seu cadastro:<br><br><div style=\'background: #f8f9fa; padding: 10px; border-radius: 5px; color: #e74c3c; font-family: monospace;\'>'.htmlspecialchars($e->getMessage()).'</div>",
                icon: "error",
                confirmButtonText: "Fechar",
                confirmButtonColor: "#e74c3c"
            });
            </script>';
        }
    } else {
        $missingFields = [];
        if (!$nome) $missingFields[] = "Nome completo";
        if (!$email) $missingFields[] = "E-mail válido";
        if (!$telefone) $missingFields[] = "Telefone";
        if (!$senha) $missingFields[] = "Senha";
        
        echo '<script>
        Swal.fire({
            title: "Campos obrigatórios",
            html: "Por favor, preencha corretamente todos os campos obrigatórios:<br><br><div style=\'text-align: left; margin-left: 20px;\'>• '.implode('<br>• ', $missingFields).'</div>",
            icon: "info",
            confirmButtonText: "Entendi",
            confirmButtonColor: "#3498db"
        });
        </script>';
    }
}

echo '</body>
</html>';