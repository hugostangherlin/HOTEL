<?php
if (!isset($_SESSION['usuario']['id'])) {
    header('Location: /HOTEL/login.php');
    exit;
} else {
    header('Location: /HOTEL/hospede/pages/detalhes_quarto.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['reserva_temporaria'] = [
        'checkin' => $_POST['checkin'],
        'checkout' => $_POST['checkout'],
        'categoria' => $_POST['categoria'],
        'hospedes' => $_POST['hospedes'],
        'quarto_id' => $_POST['quarto_id'],
        'preco_diaria' => $_POST['preco_diaria'],
        'capacidade' => $_POST['capacidade'],
        'foto' => $_POST['foto'],
    ];

    // Se o usuário não estiver logado, redireciona para login
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /HOTEL/login.php');
        exit;
    } else {
        header('Location: /HOTEL/hospede/pages/detalhes_quarto.php');
        exit;
    }
} else {
    // Acesso direto não permitido
    header('Location: /HOTEL/index.php');
    exit;
}
