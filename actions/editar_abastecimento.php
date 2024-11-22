<?php
session_start(); //Iniciar a sessao
ob_start();
require_once "../_app/conf.php";
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);



//VERIFICA SE O USUÁRIO CLICOU NO BOTÃO
if (!empty($dados['submitBtn'])) {




    if (in_array('', $dados)) :
        $_SESSION['msg'] = '<div class="trigger alert">Preencha todos os campos!</div>';
        header("Location: ");
        die;
    endif;

   
    // Verifique se o $dados['veiculo_id'] foi definido e é um número válido

    $sql = "SELECT * FROM abastecimento WHERE veiculo_id = :veiculo_id AND abast_id < :abast_id ORDER BY abast_id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':veiculo_id', $dados['veiculo_id'], PDO::PARAM_INT);
    $stmt->bindParam(':abast_id', $dados['id'], PDO::PARAM_INT);
    $stmt->execute();

    $last_id = $stmt->fetch();

    $sql = "SELECT * FROM abastecimento WHERE abast_placa =:placa ORDER BY abast_id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':placa', $dados['abast_placa']);
    $stmt->execute();
    $count = $stmt->rowCount();
    $lastRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($count <= 0) {
        $lastRecord['abast_id'] = 0;
        $km_anterior = 0;
    } elseif ($count >= 1) {
        $km_anterior = $dados['abast_km_anterior'];
    }

    $km_abast = $dados['abast_km'];
    $km_abast_caracteres = mb_strlen($km_abast, 'UTF-8');

    $km_abast_anterior_caracteres = mb_strlen($km_anterior, 'UTF-8');
    $valor_abast = $dados['abast_valor'];
    $valor_abast_caracteres = mb_strlen($valor_abast, 'UTF-8');
    $litro_abast = $dados['abast_total_litro'];
    $litro_abast_caracteres = mb_strlen($litro_abast, 'UTF-8');

    $km_atual = str_replace(',', '', str_replace('.', '', $dados['abast_km']));
    $km_ant = str_replace(',', '', str_replace('.', '', $km_anterior));

    $km_atu = number_format($dados['abast_km'], 0, ",", "") . '<br/>';

    $kms_rod = $km_atual - $km_anterior;
    $km_rod = number_format($kms_rod, 0, ".", ".");
    $km_rodado = $km_atual - $km_ant;

    $abast_lit = str_replace(',', '.', str_replace('.', '.', $dados['abast_total_litro']));
    $val_abast =  str_replace(',', '.', str_replace('.', '', $dados['abast_valor']));

    //APENAS PARA CALCULOS
    $valor_litro = $val_abast / $dados['abast_total_litro'];
    $media_km = $km_rodado / $abast_lit;
    $media = floatval($media_km);
    $km_rodado = $km_atual - $km_ant;
    $litro = number_format($dados['abast_total_litro'], 3, '.', '');

    //EDITA O KM RODADO E MÉDIA DE CONSUMO DO ABASTECIMENTO ANTERIOR
    $editabast = "UPDATE abastecimento SET abast_km_rodado=:abast_km_rodado, abast_media=:abast_media WHERE abast_id =:abast_id_anterior";
    $data = $pdo->prepare($editabast);
    $data->bindParam('abast_id_anterior', $last_id['abast_id']);
    $data->bindParam(':abast_km_rodado', $km_rodado);
    $data->bindParam(':abast_media', $media_km);
    $data->execute();

    //EDITA O KM RODADO E MÉDIA DE CONSUMO DO ABASTECIMENTO
    $editabast1 = "UPDATE abastecimento SET abast_valor=:abast_valor, abast_valor_litro=:abast_valor_litro, abast_total_litro=:abast_total_litro, abast_km=:abast_km, abast_combustivel=:abast_combustivel, abast_data=:abast_data, cad_autor=:cad_autor WHERE abast_id = {$dados['id']}";
    $data = $pdo->prepare($editabast1);
    $data->bindParam(':abast_valor', $val_abast);
    $data->bindParam(':abast_valor_litro', $valor_litro);
    $data->bindParam(':abast_total_litro', $litro);
    $data->bindParam(':abast_km', $dados['abast_km']);
    $data->bindParam(':abast_combustivel', $dados['abast_combustivel']);
    $data->bindParam(':abast_data', $dados['abast_data']);
    $data->bindParam(':cad_autor', $dados['cad_autor']);
    $data->execute();


    // Verifica se há imagem no upload
    if (empty($_FILES['image']['name'][0])) :
        $_SESSION['msg'] = '<div class="trigger alert">Erro, campo imagem não pode estar vazio!</div>';
        header("Location: ");
    else :
        $extensoesDeImagem = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif'];
        $maxsize = 3 * 1024 * 1024;

        foreach ($_FILES['image']['tmp_name'] as $chave => $arquivo) {
            $name = $_FILES['image']['name'][$chave];
            $size = $_FILES['image']['size'][$chave];
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            // Verifica o tamanho da imagem enviada
            if ($size > $maxsize) {
                $_SESSION['msg'] = "<div class='trigger error'>Erro: Tamanho do arquivo " . $name . " excede o limite de 5 MB!</div>";
                header("Location: ");
                die;
            }

            $ano = date('Y');
            $mes = date('m');
            // Diretório onde o arquivo será salvo
            $diretorio = "../imagens/$ano/$mes/";
            // Verifica se o diretório existe, se não, cria
            if (!file_exists($diretorio) && !is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            if (in_array(strtolower($extension), $extensoesDeImagem)) {
                $nome_arquivo = 'placa-' . $dados['abast_placa'] . '-data-' . $dados['abast_data'] . '-id-' . rand() . $dados['id'] . '-' . $chave . '.' . $extension;

                $target_file = $diretorio . $nome_arquivo;
                $imagem = $ano . '/' . $mes . '/' . $nome_arquivo;

                // Move o arquivo para o diretório de destino
                if (move_uploaded_file($_FILES['image']['tmp_name'][$chave], $target_file)) {
                    require_once '../app/resize_image.php';
                    resize_image($target_file, 1280, 720);

                    

                    // Cadastra a imagem no banco de dados
                    $up_image = "INSERT INTO abastecimento_img (abast_id, img_path) VALUES (:abast_id, :img_path)";
                    $image = $pdo->prepare($up_image);
                    $image->bindParam(':abast_id', $dados['id']);
                    $image->bindParam(':img_path', $imagem);
                    $image->execute();


                } else {
                    $_SESSION['msg'] = "<div class='trigger error'>Erro ao mover o arquivo ' . $name . '.</div>";
                    header("Location: ./");
                    die;
                }
            } else {
                $_SESSION['msg'] = "<div class='trigger error'>Erro: Extensão do arquivo " . $name . " não permitida.</div>";
                header("Location: ./");
                die;
            }
        }
    endif;






    $_SESSION['msg'] = '<div class="trigger accept">Abastecimento atualizado com sucesso!</div>';
    header("Location:  ../editar_abastecimento?id=" . $dados['id']);
} else {
    $_SESSION['msg'] = '<div class="trigger alert">Erro, dados não atualizados!</div>';
    header("Location:  ../editar_abastecimento?id=" . $dados['id']);
}
