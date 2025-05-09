<?php 
$host = "localhost";
$usuario = "root";
$senha = "";
$nome_banco = "rodeo_hotel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nome_banco;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Rodeo Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonte Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f3f4f6;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #FB4D46;
            color: white;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: 500;
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
        }
        a.username {
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

        .dropdown-menu a {
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .dropdown-menu.show {
            display: flex;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Rodeo Hotel</div>

        <!-- Formulário de busca -->
        <form action="" method="GET" style="display: flex; gap: 8px; align-items: center;">
           <label for="checkin">Checkin</label>
            <input type="date" name="checkin" required placeholder="Data de Checkin" required style="padding: 5px; border-radius: 4px; border: none;">
             <label for="checkin">Checkout</label>
            <input type="date" name="checkout" required placeholder="Checkout" required style="padding: 5px; border-radius: 4px; border: none;">
            <select name="categoria" aria-placeholder="Categoria" style="padding: 5px; border-radius: 4px; border: none;">
                <option value="" disabled selected>Selecione uma categoria</option> 
                <?php
                // Conectando ao banco e buscando categorias
                $stmt = $pdo->query("SELECT * FROM categoria");
                while ($cat = $stmt->fetch()) {
                    echo "<option value='{$cat['ID_Categoria']}'>{$cat['Nome']}</option>";
                }
                ?>
            </select>
             <input type="number" name="hospedes" placeholder="Quantos hóspedes?" min="1" value="1" style="padding: 5px; border-radius: 4px; border: none;">
            <button type="submit" style="padding: 5px 10px; background: white; color: #FB4D46; border: none; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Ícone de Usuário (Link para Entrar) -->
        <div class="user-menu">
            <div class="user-dropdown" id="userDropdown">
                <a href="entrar.php" class="username d-flex align-items-center">
                    <i class="fas fa-user-circle mr-1"></i>
                    <span>Entrar</span>
                </a>
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
            if (!document.getElementById('userDropdown').contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>

</body>
</html>
