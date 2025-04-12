<?php
$db_name = 'rodeo_hotel';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
    
}

if (isset($_POST['submit'])) {
    $nome = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone');
    $senha = $_POST['password'];
    $cpf = filter_input(INPUT_POST, 'cpf');
    $endereco = filter_input(INPUT_POST, 'endereco');
    $birthdate = $_POST['birthdate'];

    // Definindo automaticamente o perfil para "Hóspede" (ou outro perfil padrão)
    $perfil = 2; // Supondo que o ID de "Hóspede" seja 2 no banco de dados

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

                header("Location: cadastro.php");
                exit;
            } else {
                echo "<script>alert('Este e-mail já está cadastrado!');</script>";
            }
        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!');</script>";
    }
};
