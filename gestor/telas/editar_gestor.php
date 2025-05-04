<?php
session_start();
require_once '../../config/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: entrar.php");
    exit();
}

// Verifica se o ID do usuário está na sessão
$id_usuario = $_SESSION['usuario']['id'];

// Busca os dados do usuário
$sql = "SELECT * FROM usuarios WHERE ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o usuário
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Processamento do POST (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $dataNascimento = $_POST['data_nascimento'];

    try {
        // Atualiza os dados do usuário no banco
        $sql = "UPDATE usuarios SET Nome = ?, Email = ?, Telefone = ?, Endereco = ?, Data_Nascimento = ? WHERE ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $telefone, $endereco, $dataNascimento, $id_usuario]);

        // Atualiza a sessão com os novos dados
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefone'] = $telefone;
        $_SESSION['usuario']['endereco'] = $endereco;
        $_SESSION['usuario']['data_nascimento'] = $dataNascimento;

        // Redireciona de volta para o perfil
        header("Location: exibir_gestor.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao atualizar: " . $e->getMessage());
    }
}

?>

<!-- Conteúdo Principal -->
<div class="content-wrapper">
    <!-- Cabeçalho do Conteúdo -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Editar Perfil</h1>
        </div>
    </section>

    <!-- Conteúdo Principal -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dados do Usuário</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['Nome'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['Email'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($usuario['Telefone'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <textarea class="form-control" name="endereco" rows="2"><?= htmlspecialchars($usuario['Endereco'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="<?= htmlspecialchars($usuario['DataNascimento'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
