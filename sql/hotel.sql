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


