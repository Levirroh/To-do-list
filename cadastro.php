<?php 
include "db_connect.php";
session_start();


if (isset($_POST["cadastrar"])){
    $nome_usuario = $_POST['nome_usuario'];
    $senha_usuario = $_POST['senha_usuario'];
    if ($nome_usuario == null OR $senha_usuario == null){
        echo "Insira os dados corretamente! Não pode haver valores vazios!";
    } else{
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nome_usuario = ?");
        $stmt->bind_param("s", $nome_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo "<br>Um usuário com este nome já existe";
        } else {
            $sql = "INSERT INTO usuarios (nome_usuario, senha_usuario) VALUES ('$nome_usuario', '$senha_usuario');";
            
            if ($conn->query($sql) === TRUE) {
                echo "<div class='popup'>Cadastro realizado com sucesso! Você pode sair desta tela agora.</div>";
            } else {
                echo "<div class='popup'>Erro: " . $conn->error . "</div>";
            }
        }
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
                <input type="submit" name="cadastrar" value="Cadastrar">
            </form>
        </div>
        <div>
            <p>Já possui cadastro?</p>
            <br>
            <a href="login.php"><button>Entre</button></a>
        </div>
    </section>
</body>
</html>