<?php
$db_name = 'rodeo hotel';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

$pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_pass);
// $sql = $pdo->query('SELECT * FROM clientes');
// echo "TOTAL: ".$sql->rowCount();
// $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
try {
    // Criar conexão PDO
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
  }
  
  // Verificar se o formulário foi enviado
  if (isset($_POST['submit'])) {
    // Capturar e sanitizar os dados
    $nome = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefone = htmlspecialchars($_POST['telefone']);
    $senha = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash seguro para a senha
    $cpf = htmlspecialchars($_POST['cpf']);
    $endereco = htmlspecialchars($_POST['endereco']);
    $birthdate = $_POST['birthdate'];
  
    // Verificar se os campos obrigatórios foram preenchidos
    if (!empty($nome) && !empty($email) && !empty($telefone) && !empty($_POST['password'])) {
        try {
            // Query para inserir os dados no banco
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone, senha, cpf, endereco, data_nascimento) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $telefone, $senha, $cpf, $endereco, $birthdate]);
            
            echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='cadastro.php';</script>";
        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios!');</script>";
    }
  }