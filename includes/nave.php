<?php 
// Aqui, caso a sessão ainda não esteja iniciada ele inicia
if (session_status() === PHP_SESSION_NONE) session_start();
// No arquivo dentro da pasta "gestor" ou "hospede"
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
      <link rel="icon" href="/HOTEL/favicon.ico" type="image/x-icon">
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
        display: flex;
        align-items: center;
        gap: 5px;
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

    /* Improved Search Form */
    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .search-form:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .search-form label {
        color: white;
        font-size: 14px;
        font-weight: 400;
        white-space: nowrap;
    }

    .search-form input,
    .search-form select {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
        background-color: white;
        transition: all 0.2s ease;
    }

    .search-form input:focus,
    .search-form select:focus {
        outline: none;
        border-color: #FB4D46;
        box-shadow: 0 0 0 2px rgba(251, 77, 70, 0.2);
    }

    .search-form button {
        padding: 8px 16px;
        background: white;
        color: #FB4D46;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .search-form button:hover {
        background: #f0f0f0;
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .navbar {
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-form {
            order: 3;
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .search-form {
            flex-wrap: wrap;
        }
        
        .search-form > * {
            flex: 1 1 100%;
        }
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
 <div class="logo">
    <span>Rodeo Hotel</span>
 </div>
        <!-- Improved Search Form -->
        <form action="" method="GET" class="search-form">
            <label for="checkin">Check-in</label>
            <input type="date" name="checkin" required id="checkin">
            
            <label for="checkout">Check-out</label>
            <input type="date" name="checkout" required id="checkout">
            
            <select name="categoria" aria-label="Categoria">
                <option value="" disabled selected>Categoria</option> 
                <?php
                // Conectando ao banco e buscando categorias
                $stmt = $pdo->query("SELECT * FROM categoria");
                while ($cat = $stmt->fetch()) {
                    echo "<option value='{$cat['ID_Categoria']}'>{$cat['Nome']}</option>";
                }
                ?>
            </select>
            
            <button type="submit">
                <i class="fas fa-search"></i>
                <span>Buscar Quartos</span>
            </button>
        </form>

        <div class="user-menu">
    <div class="user-dropdown" id="userDropdown">
        <a href="#" class="username d-flex align-items-center" id="dropdownToggle">
        <i class="fas fa-user-circle mr-1"></i>
            <span><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?></span>
            
        </a>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="exibir_hospede.php">Meu Perfil</a>
            <a href="minhas_reservas.php">Minhas Reservas</a>
            <a href="/HOTEL/home.php">Sair</a>
        </div>
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
