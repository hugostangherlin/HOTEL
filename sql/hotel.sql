CREATE DATABASE RodeoHotel;
USE RodeoHotel;

CREATE TABLE Clientes(
ID_Cliente INT PRIMARY KEY auto_increment, 
Nome VARCHAR (100) NOT NULL
E-mail VARCHAR (100) UNIQUE NOT NULL
Telefone VARCHAR (100)
Senha VARCHAR (255)
CPF VARCHAR (14)
Endereço TEXT  NOT NULL
Data_nascimento DATE  NOT NULL
);

CREATE TABLE Funcionarios(
    ID_Funcionarios INT PRIMARY KEY auto increment 
    Nome VARCHAR(100) NOT NULL
    Email VARCHAR (100) UNIQUE NOT NULL,
    Telefone VARCHAR (15) NOT NULL,
    Cargo VARCHAR (50) NOT NULL,
    Senha VARCHAR(255)

);

CREATE TABLE Categoria (
    ID_Categoria INT auto_increment PRIMARY KEY
    Nome VARCHAR (50)
    Descrisão TEXT
);

CREATE TABLE Quartos (
    ID_Quarto INT AUTO_INCREMENT PRIMARY KEY,
    ID_Categoria INT,
    Status ENUM('Disponível', 'Ocupado', 'Manutenção') NOT NULL,
    Capacidade INT NOT NULL,
    FOREIGN KEY (ID_Categoria) REFERENCES Categoria
);

CREATE TABLE Reservas (
    ID_Reserva INT AUTO_INCREMENT PRIMARY KEY,
    ID_Cliente INT,
    ID_Quarto INT,
    ID_Funcionario INT,
<<<<<<< HEAD
    ID_Servico INT, 
=======

>>>>>>> 4b8d93aa4ce4afda9d75c2ea684b9d08f10e7f99
    Status ENUM('Confirmada', 'Pendente', 'Cancelada', 'Finalizada') NOT NULL,
    Check_in DATE NOT NULL,
    Check_out DATE NOT NULL,
    Data_Reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Clientes,
    FOREIGN KEY (ID_Quarto) REFERENCES Quartos,
    FOREIGN KEY (ID_Funcionario) REFERENCES Funcionarios,
    FOREIGN KEY (ID_Servico) REFERENCES Servico
);

<<<<<<< HEAD
CREATE TABLE Servicos (
    ID_Servico INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Preco DECIMAL(10,2) NOT NULL,
    Descricao TEXT
);

CREATE TABLE Avaliacoes (
    ID_Avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    ID_Cliente INT,
    ID_Reserva INT,
    Nota INT CHECK (Nota BETWEEN 1 AND 5),
    Comentario TEXT,
    Data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Clientes(ID_Cliente),
    FOREIGN KEY (ID_Reserva) REFERENCES Reservas(ID_Reserva)
);
=======
CREATE TABLE Servico (
ID_Servico INT AUTO_INCREMENT PRIMARY KEY,
Nome VARCHAR(100),
Preço FLOAT 
Descrisao VARCHAR (500),
);

CREATE TABLE Avaliacao




>>>>>>> 4b8d93aa4ce4afda9d75c2ea684b9d08f10e7f99


