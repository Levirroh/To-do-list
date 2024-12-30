<?php
include "db_connect.php";
session_start();

if (!isset($_SESSION['nome_usuario'])){
    header('location: login.php');
    exit;
}

        
if (isset($_POST["alterar_status"])){
    if (isset($_POST['aFazer']) AND !isset($_POST['fazendo']) AND !isset($_POST['pronto'])){
        $id_tarefa = $_POST["aFazer"];
        $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'A fazer' WHERE id_tarefa = ?";
        $stmt_alterar_status = $conn->prepare($sql_alterar_status);
        $stmt_alterar_status->bind_param('i', $id_tarefa);
        $stmt_alterar_status->execute();
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['fazendo']) AND !isset($_POST['aFazer']) AND !isset($_POST['pronto'])){
        $id_tarefa = $_POST["fazendo"];
        $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'Fazendo' WHERE id_tarefa = ?";
        $stmt_alterar_status = $conn->prepare($sql_alterar_status);
        $stmt_alterar_status->bind_param('i', $id_tarefa);
        $stmt_alterar_status->execute();
        header("Location: index.php");
        exit();
    }elseif (isset($_POST['pronto']) AND !isset($_POST['fazendo']) AND !isset($_POST['aFazer'])){
        $id_tarefa = $_POST["pronto"];
        $sql_alterar_status = "UPDATE tarefas SET status_tarefa = 'Pronto' WHERE id_tarefa = ?";
        $stmt_alterar_status = $conn->prepare($sql_alterar_status);
        $stmt_alterar_status->bind_param('i', $id_tarefa);
        $stmt_alterar_status->execute();
        header("Location: index.php");
        exit();
    } else {
        echo "Selecione uma opção antes de atualizar os dados";
    }
};
$id_usuario = $_SESSION['id_usuario'];
// tarefa a fazer
    if (isset($_POST['filtrar_assunto'])){
        $assunto_filtro = $_POST['assunto'];
        $stmt_aFazer = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'A fazer' AND fk_usuario = '$id_usuario' AND assunto_tarefa = '$assunto_filtro'");
        $stmt_aFazer->execute();
        $resultado_aFazer = $stmt_aFazer->get_result();
        $assunto = true;    
    } else {
        $assunto = false;
    };
    
    if (isset($_POST['filtrar_prioridade'])){
        $prioridade_filtro = $_POST['prioridade'];
        $stmt_aFazer = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'A fazer' AND fk_usuario = '$id_usuario' AND prioridade_tarefa = '$prioridade_filtro'");
        $stmt_aFazer->execute();
        $resultado_aFazer = $stmt_aFazer->get_result();
        $prioridade = true;
    } else {
        $prioridade = false;
    };
        
    if ($assunto == false AND $prioridade == false OR isset($_POST['nenhum_filtro'])){
        $stmt_aFazer = $conn->prepare("SELECT * FROM Tarefas INNER JOIN Usuarios ON usuarios.id_usuario = tarefas.fk_usuario WHERE status_tarefa = 'A fazer' AND fk_usuario = '$id_usuario'");
        $stmt_aFazer->execute();
        $resultado_aFazer = $stmt_aFazer->get_result();
    } 


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
if (isset($_POST["terminar"])){
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


$sql_filtros= "SELECT DISTINCT assunto_tarefa FROM Tarefas";
$stmt_filtros = $conn->prepare($sql_filtros);
$stmt_filtros->execute();
$result_filtros = $stmt_filtros->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="stylesheet" href="styles.css">
</head>
<body class="horizontal" id="index">
    <header>
        <div>
            <h2>Menu</h2>
        </div>
        <div>
            <a href="cadastrar_tarefa.php"><button>Cadastrar tarefa</button></a>
            <a href="login.php"><button>Trocar de conta</button></a>
            <a href="cadastro.php"><button>Criar nova conta</button></a>
        </div>
    </header>

<?php
    echo "<section class ='grid'>   
            <div>  
                <div>
                    <h2>A FAZER</h2>
                </div>
                <div class='status'>
                ";
                if ($resultado_aFazer->num_rows > 0) {
                    echo "<div class='filtros'>
                            <form method='POST'>
                                <div>
                                    <label for='prioridade'>Filtrar por prioridade
                                    <select name='prioridade'>
                                        <option value='nenhum' selected disabled>Filtrar</option>
                                        <option value='Alta'>Alta</option>
                                        <option value='Média'>Média</option>
                                        <option value='baixa'>Baixa</option>
                                    </select>
                                </div>
                                    <input type='submit' value='Filtrar' name='filtrar_prioridade'>
                            </form>
                            <form method='POST'>
                                <div>
                                    <label for='assunto'>Filtrar por assunto
                                    <select name='assunto'>";
                                        while ($filtro = $result_filtros->fetch_assoc()): ?>
                                            <option value="<?= $filtro['assunto_tarefa']; ?>">
                                                <?= $filtro['assunto_tarefa']; ?>
                                            </option>
                                        <?php endwhile;
                                        echo "
                                    </select>
                                </div>
                                <input type='submit' value='Filtrar' name='filtrar_assunto'>
                            </form>
                            <form method='POST'>                                    
                                <input type='submit' value='Retirar Filtros' name='nenhum_filtro'>
                            </form>
                        </div>";
                    while ($row = $resultado_aFazer->fetch_assoc()) {
                            $cor = $row['prioridade_tarefa'];
                        echo "<div class='tarefa $cor'>
                                <h3>Nome: {$row['nome_tarefa']}</h3>
                                <p>Descrição: {$row['descricao_tarefa']}</p> 
                                <p>assunto: {$row['assunto_tarefa']}</p> 
                                <p>Prioridade: {$row['prioridade_tarefa']}</p> 
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
                                        <div class='mudar-prioridade'> 
                                            <div>
                                                    <input type='radio' name='aFazer' value='{$row['id_tarefa']}>
                                                    <label for='aFazer'>A Fazer<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='fazendo' value='{$row['id_tarefa']}>
                                                    <label for='fazendo'>Fazendo<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='pronto' value='{$row['id_tarefa']}>
                                                    <label for='pronto'>Pronto<label>
                                                </div>
                                        </div>
                                        <input type='submit' name='alterar_status' value='Alterar Status'>
                                    </form>
                                </div>
                            </div>";
                }
            };
            echo "<a href='cadastrar_tarefa.php' class='nova-tarefa'>
                        <div>
                            <img src='imagens/plus-square-solid.svg' class='imagem-nova-tarefa'>
                        </div>
                    </a>
                </div>";
            echo"</div>
                    <div>
                        <div>
                            <h2>FAZENDO</h2>
                        </div>
                        <div class='status'>";
                    if ($resultado_fazendo->num_rows > 0) {
                        while ($row = $resultado_fazendo->fetch_assoc()) {
                            $cor = $row['prioridade_tarefa'];
                        echo "<div class='tarefa $cor'>
                                    <h3>Nome: {$row['nome_tarefa']}</h3>
                                    <p>Descrição: {$row['descricao_tarefa']}</p> 
                                    <p>assunto: {$row['assunto_tarefa']}</p> 
                                    <p>Prioridade: {$row['prioridade_tarefa']}</p> 
                                    <div class='opcoes'>
                                        <form method='GET' action='cadastrar_tarefa.php'>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='editar' value='Editar'>
                                        </form>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='deletar' value='Excluir'>
                                        </form>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_tarefa' value='{$row['id_tarefa']}'>
                                            <input type='submit' name='terminar' value='Terminar Tarefa'>
                                        </form>
                                    </div>
                                    <div>
                                        <form method='POST' action='' class='alterar-status'>
                                            <div class='mudar-prioridade'> 
                                                <div>
                                                    <input type='radio' name='aFazer' value='{$row['id_tarefa']}>
                                                    <label for='aFazer'>A Fazer<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='fazendo' value='{$row['id_tarefa']}>
                                                    <label for='fazendo'>Fazendo<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='pronto' value='{$row['id_tarefa']}>
                                                    <label for='pronto'>Pronto<label>
                                                </div>
                                            </div>
                                            <input type='submit' name='alterar_status' value='Alterar Status'>
                                        </form>
                                    </div>
                                </div>";
                    }
                };
            echo '</div>';
            echo"</div>
                    <div>
                        <div>
                            <h2>PRONTO</h2>
                        </div>
                        <div class='status'>";
                    if ($resultado_pronto->num_rows > 0) {
                        while ($row = $resultado_pronto->fetch_assoc()) {
                            $cor = $row['prioridade_tarefa'];
                        echo "<div class='tarefa $cor'>
                                    <h3>Nome: {$row['nome_tarefa']}</h3>
                                    <p>Descrição: {$row['descricao_tarefa']}</p> 
                                    <p>assunto: {$row['assunto_tarefa']}</p> 
                                    <p>Prioridade: {$row['prioridade_tarefa']}</p> 
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
                                            <div class='mudar-prioridade'> 
                                                <div>
                                                    <input type='radio' name='aFazer' value='{$row['id_tarefa']}>
                                                    <label for='aFazer'>A Fazer<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='fazendo' value='{$row['id_tarefa']}>
                                                    <label for='fazendo'>Fazendo<label>
                                                </div>
                                                <div>
                                                    <input type='radio' name='pronto' value='{$row['id_tarefa']}>
                                                    <label for='pronto'>Pronto<label>
                                                </div>
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
        
   
</body>
</html> 