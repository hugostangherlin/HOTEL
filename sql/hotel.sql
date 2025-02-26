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
)

CREATE TABLE Funcionários(
    ID_Funcionarios INT PRIMARY KEY auto increment 
    
)