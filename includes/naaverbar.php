<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/config.php';

$isHospede = isset($_SESSION['usuario_id']) && isset($_SESSION['perfil']) && $_SESSION['perfil'] == 2;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Rodeo Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f3f4f6;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #FB4D46;
            color: white;
        }
        .logo {
            font-size: 20px;
            font-weight: 500;
        }
        .navbar form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .navbar input, .navbar select {
            padding: 5px;
            border-radius: 4px;
            border: none;
        }
        .navbar button {
            padding: 5px 10px;
            background: white;
            color: #FB4D46;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .user-dropdown {
            position: relative;
            cursor: pointer;
        }
        .username {
            color: white;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            min-width: 180px;
            z-index: 999;
        }
        .dropdown-menu.show {
            display: flex;
        }
        .dropdown-menu a {
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
        }
        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }
        .dropdown-menu a:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">Rodeo Hotel</div>

    <!-- Formulário de busca -->
    <form action="" method="GET">
        <label for="checkin">Checkin</label>
        <input type="date" name="checkin" required>
        <label for="checkout">Checkout</label>
        <input type="date" name="checkout" required>
        <select name="categoria">
            <option value="" disabled selected>Selecione uma categoria</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM categoria");
            while ($cat = $stmt->fetch()) {
                echo "<option value='{$cat['ID_Categoria']}'>{$cat['Nome']}</option>";
            }
            ?>
        </select>
        <input type="number" name="hospedes" placeholder="Quantos hóspedes?" min="1" value="1">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>

    <!-- Ícone do usuário e menu -->
    <div class="user-dropdown" id="dropdownToggle">
        <a href="javascript:void(0);" class="username">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?>
        </a>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="/HOTEL/hospede/pages/perfil.php">Meu Perfil</a>
            <a href="/HOTEL/hospede/pages/minhas_reservas.php">Minhas Reservas</a>
            <a href="/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
        
    </div>
</nav>

<!-- Script do Dropdown -->
<script>
    const toggle = document.getElementById('dropdownToggle');
    const menu = document.getElementById('dropdownMenu');

    toggle.addEventListener('click', () => {
        menu.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
</script>

</body>
</html>
