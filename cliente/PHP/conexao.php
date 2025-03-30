<?php 
$host ="localhost";
$user = "root";
$pass = "";
$dbname = "rodeo_hotel";
$port = 3306;

try{

$conn = new PDO(dsn:"mysql:host=$host;port=$port;dbname=". $dbname, username: $user, password: $pass);
echo "Conexão com o banco de dados efetuada com sucesso";
}catch (PDOException $err){
    echo "ERROR: Conexão com o banco de dados efetuada não realizada". $err -> getMessage();

}
?>