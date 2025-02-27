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

CREATE TABLE Funcionários(
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
    FOREIGN KEY (ID_Categoria) REFERENCES Categoria(ID_Categoria)
);

CREATE TABLE Reservas (
    ID_Reserva INT AUTO_INCREMENT PRIMARY KEY,
    ID_Cliente INT,
    ID_Quarto INT,
    ID_Funcionario INT,
    Status ENUM('Confirmada', 'Pendente', 'Cancelada', 'Finalizada') NOT NULL,
    Check_in DATE NOT NULL,
    Check_out DATE NOT NULL,
    Data_Reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Clientes(ID_Cliente),
    FOREIGN KEY (ID_Quarto) REFERENCES Quartos(ID_Quarto),
    FOREIGN KEY (ID_Funcionario) REFERENCES Funcionarios(ID_Funcionario)
);

