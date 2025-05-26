## 🗃️ Estrutura do Banco de Dados (resumo)

- `usuarios` (ID, nome, email, senha, perfil)
- `quarto` (ID, status, capacidade, categoria, preço)
- `categoria` (ID, nome, descrição)
- `reserva` (ID, checkin, checkout, quarto_id, usuario_id)
- `pagamentos` (ID, reserva_id, valor, forma_pagamento, status)
- `avaliacoes` (ID, usuario_id, reserva_id, nota, comentario)

## ✅ Requisitos

- PHP 7.4+
- MySQL 5.7+
- Composer instalado

## 📦 Instalação

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/sistema-hotel.git
   ```

2. Instale as dependências do Composer:
   ```bash
   composer install
   ```

3. Crie o banco de dados MySQL e importe o script SQL incluído no projeto.

4. Configure a conexão no arquivo `config/config.php`:
   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=nome_do_banco", "usuario", "senha");
   ```

5. Acesse o sistema no navegador:
   ```
   http://localhost/HOTEL/
   ```

## 📝 Licença

Este projeto é de uso acadêmico. Você pode reutilizá-lo com os devidos créditos.
