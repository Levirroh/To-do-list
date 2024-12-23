<?php
include "db_connect.php";
session_start();

if (!isset($_SESSION['nome_usuario'])){
    header('location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
// tarefa a fazer
    $stmt_aFazer = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'A fazer' AND fk_usuario = '$id_usuario'");
    $stmt_aFazer->execute();
    $resultado_aFazer = $stmt_aFazer->get_result();
// tarefa fazendo
    $stmt_fazendo = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'Fazendo' AND fk_usuario = '$id_usuario'");
    $stmt_fazendo->execute();
    $resultado_fazendo = $stmt_fazendo->get_result();
// tarefa concluida
    $stmt_pronto = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'Pronto' AND fk_usuario = '$id_usuario'");
    $stmt_pronto->execute();
    $resultado_pronto = $stmt_pronto->get_result();

if (isset($_POST["deletar"])){
    $id_tarefa = $_POST['id_tarefa'];
    $sql = "DELETE FROM Tarefas WHERE id_tarefa = ?";

    $stmt_deletar = $conn->prepare($sql);
    $stmt_deletar->bind_param('i', $id_tarefa);
    $stmt_deletar->execute();

    if ($stmt_deletar->affected_rows > 0) {
        echo "Registro deletado com sucesso!";
        echo "<br>Talvez seja necessário atualizar a página!";
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao deletar o registro.";
    }
};



$sql_usuarios = "SELECT id_usuario, nome_usuario FROM Usuarios";
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
            <h1>Tela de Gerenciamento de Tarefas</h1>
        </div>
        <div>
            <a href="cadastrar_tarefa.php"><button>Cadastrar tarefa</button></a>
            <a href="login.php"><button>Trocar de conta</button></a>
            <a href="cadastro.php"><button>Criar nova conta</button></a>
        </div>
    </header>
    <section>
        <?php
    echo "<section class ='grid'>
                <div class='status'>
                    <h2>A FAZER</h2>
                ";
                if ($resultado_aFazer->num_rows > 0) {
                    while ($row = $resultado_aFazer->fetch_assoc()) {
                        echo "<div class='tarefa'>
                                <p>Descrição: {$row['descricao_tarefa']}</p> 
                                <p>assunto: {$row['assunto_tarefa']}</p> 
                                <p>Prioridade: {$row['prioridade_tarefa']}</p> 
                                <p>Vinculado a: {$row['nome_usuario']}</p> 
                                <div class='opcoes'>
                                    <form method='GET' action='cadastrar_tarefa.php'>
                                        <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                        <input type='submit' name='editar' value='Editar'>
                                    </form>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                        <input type='submit' name='deletar' value='Excluir'>
                                    </form>
                                </div>
                                <div>
                                    <form method='POST' action='' class='alterar-status'>
                                        <div> 
                                            <input type='radio' name='aFazer' value='{$row['id_tarefa']}'>
                                            <label for='aFazer'>A Fazer<label>
                                            
                                            <input type='radio' name='fazendo' value='{$row['id_tarefa']}'>
                                            <label for='fazendo'>Fazendo<label>

                                            <input type='radio' name='pronto' value='{$row['id_tarefa']}'>
                                            <label for='pronto'>Pronto<label>
                                        </div>
                                        <input type='submit' name='alterar_status' value='Alterar Status'>
                                    </form>
                                </div>
                            </div>";
                }
            };
            echo"</div>
                <div class='status'>
                    <h2>FAZENDO</h2>";
                    if ($resultado_fazendo->num_rows > 0) {
                        while ($row = $resultado_fazendo->fetch_assoc()) {
                            echo "<div class='tarefa'>
                                    <p>Descrição: {$row['descricao_tarefa']}</p> 
                                    <p>assunto: {$row['assunto_tarefa']}</p> 
                                    <p>Prioridade: {$row['prioridade_tarefa']}</p> 
                                    <p>Vinculado a: {$row['nome_usuario']}</p> 
                                    <div class='opcoes'>
                                        <form method='GET' action='cadastrar_tarefa.php'>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='editar' value='Editar'>
                                        </form>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='deletar' value='Excluir'>
                                        </form>
                                        
                                    </div>
                                    <div>
                                        <form method='POST' action='' class='alterar-status'>
                                            <div> 
                                                <input type='radio' name='aFazer' value='{$row['id_tarefa']}'>
                                                <label for='aFazer'>A Fazer<label>
                                                
                                                <input type='radio' name='fazendo' value='{$row['id_tarefa']}'>
                                                <label for='fazendo'>Fazendo<label>
    
                                                <input type='radio' name='pronto' value='{$row['id_tarefa']}'>
                                                <label for='pronto'>Pronto<label>
                                            </div>
                                            <input type='submit' name='alterar_status' value='Alterar Status'>
                                        </form>
                                    </div>
                                </div>";
                    }
                };
            echo"</div>
                <div class='status'>
                    <h2>PRONTO</h2>";
                    if ($resultado_pronto->num_rows > 0) {
                        while ($row = $resultado_pronto->fetch_assoc()) {
                            echo "<div class='tarefa'>
                                    <p>Descrição: {$row['descricao_tarefa']}</p> 
                                    <p>assunto: {$row['assunto_tarefa']}</p> 
                                    <p>Prioridade: {$row['prioridade_tarefa']}</p> 
                                    <p>Vinculado a: {$row['nome_usuario']}</p> 
                                    <div class='opcoes'>
                                        <form method='GET' action='cadastrar_tarefa.php'>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='editar' value='Editar'>
                                        </form>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='deletar' value='Excluir'>
                                        </form>
                                        
                                    </div>
                                    <div>
                                        <form method='POST' action='' class='alterar-status'>
                                            <div> 
                                                <input type='radio' name='aFazer' value='{$row['id_tarefa']}>
                                                <label for='aFazer'>A Fazer<label>
                                                
                                                <input type='radio' name='fazendo' value='{$row['id_tarefa']}>
                                                <label for='fazendo'>Fazendo<label>
    
                                                <input type='radio' name='pronto' value='{$row['id_tarefa']}>
                                                <label for='pronto'>Pronto<label>
                                            </div>
                                            <input type='submit' name='alterar_status' value='Alterar Status'>
                                        </form>
                                    </div>
                                </div>";
                    }
                };
            echo"</div>
            </section>";
        ?>
        <?php
                    
            if (isset($_POST["alterar_status"])){
                if (isset($_POST['aFazer']) AND !isset($_POST['fazendo']) AND !isset($_POST['pronto'])){
                    $id_tarefa = $_POST["aFazer"];
                    $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'A fazer' WHERE id_tarefa = ?";
                    $stmt_alterar_status = $conn->prepare($sql_alterar_status);
                    $stmt_alterar_status->bind_param('i', $id_tarefa);
                    $stmt_alterar_status->execute();
                    header("Refresh: 0");
                    exit();
                } elseif (isset($_POST['fazendo']) AND !isset($_POST['aFazer']) AND !isset($_POST['pronto'])){
                    $id_tarefa = $_POST["fazendo"];
                    $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'Fazendo' WHERE id_tarefa = ?";
                    $stmt_alterar_status = $conn->prepare($sql_alterar_status);
                    $stmt_alterar_status->bind_param('i', $id_tarefa);
                    $stmt_alterar_status->execute();
                    header("Refresh: 0");
                    exit();
                }elseif (isset($_POST['pronto']) AND !isset($_POST['fazendo']) AND !isset($_POST['aFazer'])){
                    $id_tarefa = $_POST["pronto"];
                    $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'Pronto' WHERE id_tarefa = ?";
                    $stmt_alterar_status = $conn->prepare($sql_alterar_status);
                    $stmt_alterar_status->bind_param('i', $id_tarefa);
                    $stmt_alterar_status->execute();
                    header("Refresh: 0");
                    exit();
                } else {
                    echo "Selecione uma opção antes de atualizar os dados";
                }
            };
        ?>
   
</body>
</html> 