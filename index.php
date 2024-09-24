<?php
session_start();
include_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Auditoria - Passaporte Industrial</title>
</head>
<body>

    <?php
        //Receber os dados
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); //Variável que recebe os dados que vem do formulário
        //var_dump($dados);

        if(!empty($dados['valResposta'])) {
            
            //pesquisar resposta
            $query_val_resposta = "SELECT id as id_resposta, resposta, pergunta_id, val_resposta FROM alternativas WHERE id=:id_resposta LIMIT 1";
            $result_val_resposta = $conn->prepare($query_val_resposta);
            $result_val_resposta->bindParam(':id_resposta', $dados['id_resposta'], PDO::PARAM_INT);
            $result_val_resposta->execute();
            $row_val_resposta = $result_val_resposta->fetch(PDO::FETCH_ASSOC); 
            if($row_val_resposta['val_resposta'] == 0){
                $_SESSION['msg'] = "<p> Valor 0 </p>";
            } elseif($row_val_resposta['val_resposta'] == 1){
                $_SESSION['msg'] = "<p> Valor 1 </p>";
            } elseif ($row_val_resposta['val_resposta'] == 2) {
                $_SESSION['msg'] = "<p> Valor 2 </p>";
            }
        
            //pesquisar pergunta
            $query_pergunta = "SELECT id, questao FROM perguntas WHERE id=:id LIMIT 1";
            $result_pergunta = $conn->prepare($query_pergunta);
            $result_pergunta->bindParam(':id', $dados['id_pergunta'], PDO::PARAM_INT);
            $result_pergunta->execute();
        } else{
            //pesquisar pergunta randomica
            $query_pergunta = "SELECT id, questao FROM perguntas ORDER BY RAND() LIMIT 1";
            $result_pergunta = $conn->prepare($query_pergunta);
            $result_pergunta->execute();
        }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($$_SESSION['msg']);
    }

    ?>
    <form method="POST" action="">
        <?php
            if((@$result_pergunta) AND $result_pergunta->rowCount() != 0) {
                $row_pergunta = $result_pergunta->fetch(PDO::FETCH_ASSOC);
                extract($row_pergunta);
                echo $questao;
                echo "<label>Alternativas</label><br><br>";
                echo "<input type='hidden' name='id_pergunta' value='$id'>";
            
            //busca as alternartivas
                $query_resposta= "SELECT id as id_resposta, resposta FROM alternativas WHERE pergunta_id = $id ORDER BY id ASC";
                $result_resposta = $conn->prepare($query_resposta);
                $result_resposta->execute();

                while($row_resposta = $result_resposta->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_resposta);
                    #echo $resposta . "<br>";
                    if(isset($dados['id_resposta']) AND (!empty($dados['id_resposta'])) AND $id_resposta == $dados['id_resposta']){
                        $selecionado = "checked"; //deixa pré-selecionado o que o usuário digitou
                    }else {
                        $selecionado = "";
                    }

                    echo "<input type='radio' name='id_resposta' value='$id_resposta'>$resposta<br>";
                }            
            } else {
                echo "Não foi encontrada nenhuma pergunta";
            }

        ?>
        <br>
        <input type="submit" name="valResposta" value="Enviar">
    </form>

    <hr>
    <a href="index.php"> Próxima </a>
    
</body>
</html>