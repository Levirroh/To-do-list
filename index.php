<?php 
include "db_connect.php";
session_start();

if (!$_SESSION['nome_usuario']){
    header("location: login.php");
    exit;
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas</title>
</head>
<body>
    
</body>
</html>