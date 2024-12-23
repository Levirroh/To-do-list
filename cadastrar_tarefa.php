<?php
include "db_connect.php";

session_start();
if (!isset($_SESSION['nome_usuario'])){
    header('location: login.php');
    exit;
}
if (isset($_GET["id_tarefa"])) {
    $id_tarefa = $_GET["id_tarefa"];
    $stmt_tarefa = $conn->prepare("SELECT * FROM tarefas INNER JOIN usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE id_tarefa = ?");
    $stmt_tarefa->bind_param("i", $id_tarefa);
    $stmt_tarefa->execute();
    $dados_tarefa = $stmt_tarefa->get_result();
    $row = $dados_tarefa->fetch_assoc();
    $id_usuario_get = $_SESSION['id_usuario'];
    $prioridade_tarefa_get = $row['prioridade_tarefa'];
    $nome_usuario_get = $_SESSION['nome_usuario'];
    $nome_tarefa_get = $row['nome_tarefa'];
    $get_id_tarefa = true;
    
} else{
    $get_id_tarefa = false;
}
    $sql_usuarios = "SELECT id_usuario, nome_usuario FROM usuarios";
    $stmt_usuarios = $conn->prepare($sql_usuarios);
    $stmt_usuarios->execute();
    $result_usuarios = $stmt_usuarios->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div>
            <h1>Tela de Cadastro de Tarefas</h1>
        </div>
        <div>
            <a href="index.php"><button>Menu Principal</button></a>
            <a href="cadastrar_tarefa.php"><button>Cadastrar Tarefa</button></a>
            <a href="cadastro.php"><button>Cadastrar Usuário</button></a>
        </div>
    </header>
    <section>
        <form method="POST">
            <div>
                <label for="nome_tarefa">Digite o nome da tarefa</label>
                <br>
                <input type="text" name="nome_tarefa" value="<?php if (isset($_GET["id_tarefa"])) {echo $nome_tarefa_get;}; ?>">
            </div>
            <div>
                <label for="descricao">Digite a descrição do tarefa</label>
                <br>
                <textarea name="descricao" rows="4" cols="50"><?php if (isset($_GET["id_tarefa"])) {echo $row['descricao_tarefa'];} ?></textarea>
            </div>
            
            <div>
                <label for="assunto">Digite o assunto tarefa</label>
                <input type="text" name="assunto" value="<?php if (isset($_GET["id_tarefa"])) {echo $row['assunto_tarefa'];} ?>">
            </div>
            <div>
                <label for="prioridade">Digite a urgência do tarefa</label>
                <select name="prioridade">
                <option value="<?php if (isset($_GET["id_tarefa"])) {echo $prioridade_tarefa_get;}; ?>" select><?php if (isset($_GET["id_tarefa"])) {echo $prioridade_tarefa_get;} else {echo "Selecione uma opção";}; ?></option>
                    <option value="Alta">Alta</option>
                    <option value="Média">Média</option>
                    <option value="Baixa">Baixa</option>
                </select>
            </div>
            <input type="submit" value="Cadastrar" name="cadastrar_tarefa">
        </form>
        <a href="index.php"><button>Voltar</button></a>
        <?php
        if (isset($_POST["cadastrar_tarefa"])) {
                $descricao = $_POST['descricao'];
                $prioridade = $_POST['prioridade'];
                $nome_tarefa = $_POST['nome_tarefa'];
                $assunto_tarefa = $_POST['assunto'];
                $data_abertura = date("Ymd"); // ano, mes, dia 
                $fk_usuario = $_SESSION["id_usuario"];
            if ( $descricao == null OR $prioridade == null OR $assunto_tarefa == null OR $fk_usuario == null OR $fk_usuario == 0 OR $nome_tarefa == null) {
                echo "Preencha os dados corretamente!";
            } else{
                if (!isset($_GET["id_tarefa"])) {
                    $sql = "INSERT INTO tarefas (fk_usuario, nome_tarefa,descricao_tarefa, assunto_tarefa, status_tarefa, prioridade_tarefa, data_cadastro_tarefa) VALUES ('$fk_usuario', '$nome_tarefa', '$descricao','$assunto_tarefa', 'A fazer', '$prioridade', '$data_abertura');";
                
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='popup'>Tarefa cadastrada com sucesso!</div>";
                    } else {
                        echo "<div class='popup'>Erro: " . $conn->error . "</div>";
                    }
                } else{
                    $sql = "UPDATE tarefas SET fk_usuario = '$fk_usuario', descricao_tarefa = '$descricao', assunto_tarefa = '$assunto_tarefa', status_tarefa = 'A fazer' , prioridade_tarefa = '$prioridade', data_cadastro_tarefa = '$data_abertura' WHERE id_tarefa = '$id_tarefa'";
                
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='popup'>Tarefa cadastrada com sucesso!</div>";
                        header ("Location: index.php");
                        exit();

                    } else {
                        echo "<div class='popup'>Erro: " . $conn->error . "</div>";
                    }
                }
            }
        }
        ?>
</section>
</body>
</html> 