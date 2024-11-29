<?php
//conexao com o banco para nao precisar incluir em todas paginas
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "realsbet";
$conn = mysqli_connect($servername, $username, $password, $dbname); //or print (mysql_error()); 
?>