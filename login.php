<?php 
include "db_connect.php";
session_start();


if (isset($_POST['login'])){
    $nome_usuario = $_POST['nome_usuario'];
    $senha_usuario = $_POST['senha_usuario'];
    
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nome_usuario = ?");
    $stmt->bind_param("s", $nome_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        // achou um usuário com o nome
        if ($senha_usuario == $row['senha_usuario']){
            $_SESSION['nome_usuario'] = $row['nome_usuario'];
            $_SESSION['id_usuario'] = $row['id_usuario'];
            header('location: index.php');
            exit;
        } else{
            echo "<br>Senha incorreta";
        };
    } else {
        echo "<br>Nome de usuário não encontrado.";
    }

}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<header>

</header>
<body>
    <section>
        <div>
            <form method="POST">
                <div>
                    <label for="nome_usuario">Nome de usuário:</label>
                    <br>
                    <input type="text" name="nome_usuario">
                </div>
                <div>
                    <label for="senha_usuario">Senha:</label>
                    <br>
                    <input type="text" name="senha_usuario">
                </div>
                <input type="submit" name="login" value="Entrar">
            </form>
        </div>
        <div>
            <p>Não possui cadastro?</p>
            <br>
            <a href="cadastro.php"><button>Cadastre-se</button></a>
        </div>
    </section>
</body>
</html>