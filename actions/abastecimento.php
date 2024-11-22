<?php
session_start(); //Iniciar a sessao
ob_start();
require_once "../_app/conf.php";
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

var_dump($dados);

var_dump($_FILES['image']['name'][0]);


//verificar se o usuario clicou no botao
if (!empty($dados['abastecimento'])) {

    if (in_array('', $dados) OR empty($_FILES['image']['name'][0])) :
        $_SESSION['msg'] = '<div class="trigger alert">Preencha todos os campos!</div>';
        header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
        die;
    endif;


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

    /*AJUSTAR VALIDAÇÃO DO KM ANTERIOR*/

    $km_abast = $dados['abast_km'];
    $km_abast_caracteres = mb_strlen($km_abast, 'UTF-8');

    //$km_abast_anterior = $lastRecord['abast_km'];
    $km_abast_anterior_caracteres = mb_strlen($km_anterior, 'UTF-8');
    $valor_abast = $dados['abast_valor'];
    $valor_abast_caracteres = mb_strlen($valor_abast, 'UTF-8');
    $litro_abast = $dados['abast_total_litro'];
    $litro_abast_caracteres = mb_strlen($litro_abast, 'UTF-8');

    $km_atual = str_replace(',', '', str_replace('.', '', $dados['abast_km']));
    $km_ant = str_replace(',', '', str_replace('.', '', $km_anterior));


    $km_atu = number_format($dados['abast_km'], 0, ",", "") . '<br/>';

    //$km_ant = number_format($km_anterior, 0, ".", ".");
    $kms_rod = $km_atual - $km_anterior;
    $km_rod = number_format($kms_rod, 0, ".", ".");

    $abast_lit = str_replace(',', '.', str_replace('.', '.', $dados['abast_total_litro']));
    $val_abast =  str_replace(',', '.', str_replace('.', '', $dados['abast_valor']));

    //APENAS PARA CALCULOS
    $valor_litro = $val_abast / $dados['abast_total_litro'];
    $media_km = $km_rod / $abast_lit;
    $media = floatval($media_km);
    $km_rodado = $km_atual - $km_ant;
    $litro = number_format($dados['abast_total_litro'], 3, '.', '');
    $valor = str_replace(',', '.', $dados['abast_valor']);
    $abast_litro = str_replace(',', ' ', $dados['abast_total_litro']);

    $vl_litro =  $val_abast / $abast_lit;
    $valor_litro = number_format($vl_litro, 2, ".", ".");

    //CALCULO MEDIA DE CONSUMO
    $media_km = $km_rodado / $abast_lit;


    echo 'Veículo ID: ' . $dados['veiculo_id'] . '<br/>';
    echo 'Placa: ' . $dados['abast_placa'] . '<br/>';
    echo 'Veiculo: ' . $dados['abast_veiculo'] . '<br/>';
    echo 'R$: ' . $val_abast . '<br/>';
    echo 'R$/Litro: ' . $valor_litro . '<br/>';
    echo 'Litro: ' . $litro . '<br/>';
    echo 'KM Anterior: ' . $dados['abast_km_anterior'] . '<br/>';
    echo 'KM Atual: ' . $dados['abast_km'] . '<br/>';
    echo 'KM Rodado:' . $km_rodado . '<br/>';
    echo 'Média: ' . $media_km . '<br/>';
    echo 'Combustível: ' . $dados['abast_combustivel'] . '<br/>';
    echo 'Data: ' . $dados['abast_data'] . '<br/>';
    echo 'Cadastro: ' . $dados['cad_autor'] . '<br/>';




    //VALIDA OS DADOS CASO PASSE PELA VALIDAÇÃO NO FRONT PELO JAVASCRIPT
    if ($km_atu < $km_anterior) :
        $_SESSION['msg'] = '<div class="trigger error">KM atual não pode ser menor que o último KM registrado</div>';
        header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
        die;
    elseif ($valor_abast_caracteres < 5) :
        $_SESSION['msg'] = '<div class="trigger error">Campo Valor com quantidade de caracteres inválida!</div>';
        header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
        die;
    elseif ($litro_abast_caracteres < 5) :
        $_SESSION['msg'] = '<div class="trigger error">Campo Litros com quantidade de caracteres inválida!</div>';
        header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
        die;
    endif;

    //REGISTRA A MÉDIA DE KM E OS KM RODADOS ENTRE ESSE E O ÚLTIMO ABASTECIMENTO
    $media = "UPDATE abastecimento SET abast_km_rodado =:abast_km_rodado, abast_media=:media WHERE abast_id = :id";
    $data = $pdo->prepare($media);
    $data->bindParam(':id', $lastRecord['abast_id']);
    $data->bindParam(':media', $media_km);
    $data->bindParam(':abast_km_rodado', $km_rodado);
    $data->execute();

    //SALVAR NO BANCO DE DADOS
    $veiculos = "INSERT INTO abastecimento (veiculo_id, abast_placa, abast_veiculo, abast_valor, abast_valor_litro, abast_total_litro, abast_km_anterior, abast_km, abast_combustivel, abast_data, cad_autor) 
    VALUE (:veiculo_id, :abast_placa, :abast_veiculo, :abast_valor, :abast_valor_litro, :abast_total_litro, :abast_km_anterior, :abast_km, :abast_combustivel, :abast_data, :cad_autor)";
    $data = $pdo->prepare($veiculos);
    $data->bindParam(':veiculo_id', $dados['veiculo_id']);
    $data->bindParam(':abast_placa', $dados['abast_placa']);
    $data->bindParam(':abast_veiculo', $dados['abast_veiculo']);
    $data->bindParam(':abast_valor', $val_abast);
    $data->bindParam(':abast_valor_litro', $valor_litro);
    $data->bindParam(':abast_total_litro', $litro);
    $data->bindParam(':abast_km_anterior', $dados['abast_km_anterior']);
    $data->bindParam(':abast_km', $dados['abast_km']);
    $data->bindParam(':abast_combustivel', $dados['abast_combustivel']);
    $data->bindParam(':abast_data', $dados['abast_data']);
    $data->bindParam(':cad_autor', $dados['cad_autor']);
    $data->execute();


    $id = $pdo->lastInsertId();

    // Verifica se há imagem no upload
    if (empty($_FILES['image']['name'][0])) :
        $_SESSION['msg'] = '<div class="trigger alert">Erro, campo imagem não pode estar vazio!</div>';
        header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
        die;
    else :
        $extensoesDeImagem = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif'];
        $maxsize = 5 * 1024 * 1024;

        foreach ($_FILES['image']['tmp_name'] as $chave => $arquivo) {
            $name = $_FILES['image']['name'][$chave];
            $size = $_FILES['image']['size'][$chave];
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            // Verifica o tamanho da imagem enviada
            if ($size > $maxsize) {
                echo "Erro: Tamanho da imagem acima do permitido!";
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
                $nome_arquivo = 'placa-' . $dados['abast_placa'] . '-data-' . $dados['abast_data'] . '-id-' . $id . '-' . $chave . '.' . $extension;

                $target_file = $diretorio . $nome_arquivo;
                $imagem = $ano . '/' . $mes . '/' . $nome_arquivo;

                // Move o arquivo para o diretório de destino
                if (move_uploaded_file($_FILES['image']['tmp_name'][$chave], $target_file)) {
                    require_once '../app/resize_image.php';
                    resize_image($target_file, 1280, 720);

                    // Cadastra a imagem no banco de dados
                    $up_image = "INSERT INTO abastecimento_img (abast_id, img_path) VALUES (:abast_id, :img_path)";
                    $image = $pdo->prepare($up_image);
                    $image->bindParam(':abast_id', $id);
                    $image->bindParam(':img_path', $imagem);
                    $image->execute();
                } else {
                    $_SESSION['msg'] = '<div class="trigger error">Erro ao mover o arquivo " . $name . ".</div>';
                    header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
                    die;
                }
            } else {
                $_SESSION['msg'] = "<div class='trigger error'>Erro: Extensão do arquivo " . $name . " não permitida.</div>";
                header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
                die;
            }
        }
    endif;


    $_SESSION['msg'] = '<div class="trigger accept">Abastecimento cadastrado com sucesso!</div>';
    header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
} else {
    $_SESSION['msg'] = '<div class="trigger alert">Erro, abastecimento não cadastrado!</div>';
    header("Location: ../abastecimento.php?placa={$dados['abast_placa']}");
}
