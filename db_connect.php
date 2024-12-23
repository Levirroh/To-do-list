<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "to_do_list_01";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>