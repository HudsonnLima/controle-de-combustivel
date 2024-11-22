<?php
session_start(); //Iniciar a sessao
ob_start();
require_once("_app/conf.php");
$placa = filter_input(INPUT_GET, 'placa', FILTER_VALIDATE_INT);

?>
<?php
$pdos = "SELECT * FROM abastecimento WHERE abast_placa =:placa";
$data = $pdo->prepare($pdos);
$data->bindParam(':placa', $_GET['placa']);
$data->execute();
foreach ($data as $veiculos);



?>

<!doctype html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciamento de Combustível</title>
    <link href="css/style.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css">
    <script src="https://kit.fontawesome.com/7305dbe23e.js" crossorigin="anonymous"></script>

</head>

<body>
    <article class="container">
        <label class="titulo">Cadastrar novo abastecimento:</label>
        <hr>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <form method="POST" id="" action="actions/abastecimento.php" enctype="multipart/form-data">

            <div class="row g-2">

                <input type="hidden" name="veiculo_id" id="veiculo_id" value="<?php if (isset($veiculos['veiculo_id'])) echo $veiculos['veiculo_id']; ?>" />


                <div class="col-md-3">
                    <div class="form-floating">
                        <input readonly type="text" class="form-control" name="abast_placa" maxlength="8" id="placa" value="<?= $_GET['placa']; ?>" required />
                        <label for="floatingInputGrid">Placa:</label>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-floating">
                        <input readonly type="text" class="form-control" name="abast_veiculo" id="" value="<?php if (isset($veiculos['abast_veiculo'])) echo $veiculos['abast_veiculo']; ?>" required />
                        <label for="floatingInputGrid">Veículo:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" maxlength="9" name="abast_valor" id="input3" value="<?php if (isset($dados['abast_valor'])) echo $dados['abast_valor']; ?>" required />

                        <label for="input3">Valor:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" maxlength="9" id="input4" name="abast_total_litro" value="<?php if (isset($dados['abast_total_litro'])) echo $dados['abast_total_litro']; ?>" required />

                        <label for="input4">Total de Litro:</label>
                    </div>
                </div>

                <?php
                $sql = "SELECT * FROM abastecimento WHERE abast_placa =:placa ORDER BY abast_id DESC LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':placa', $_GET['placa']);
                $stmt->execute();
                $count = $stmt->rowCount();
                $lastRecord = $stmt->fetch(PDO::FETCH_ASSOC);
                //$lastRecord['abast_km'].'<br/>';
                ?>
                <div class="col-md-2">
                    <div class="form-floating">
                        <select name="abast_km_anterior" id="abast_km_anterior" class="form-select">
                            <option selected value="<?php if (isset($lastRecord['abast_km'])) : echo $lastRecord['abast_km'];
                                                    else : echo '';
                                                    endif; ?>"><?php if (isset($lastRecord['abast_km'])) : echo $lastRecord['abast_km'];
                                                                else : echo 'Sem Histórico';
                                                                endif; ?></option>
                        </select>
                        <label for="abast_km_anterior">Último KM</label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" maxlength="7" name="abast_km" id="input2" value="" required />
                        <label for="input2">KM:</label>
                    </div>
                </div>


                <input readonly type="hidden" class="form-control" maxlength="7" name="" id="input1" value="" required />
                <input readonly type="hidden" class="form-control" maxlength="7" name="" id="input6" value="<?php if (isset($lastRecord['abast_valor_litro'])) : echo  $lastRecord['abast_valor_litro'];
                                                                                                            endif; ?>" required />
                <input readonly type="hidden" class="form-control" maxlength="7" name="" id="input7" value="<?php if (isset($lastRecord['abast_combustivel'])) : echo  $lastRecord['abast_combustivel'];
                                                                                                            endif; ?>" required />



                <div class="col-md-3">
                    <div class="form-floating ">
                        <select class="form-select" name="abast_combustivel" id="8">
                            <?php
                            if (isset($veiculos['veiculo_combustivel']) AND $veiculos['veiculo_combustivel'] == 0) :
                                echo "<option selected value='0'> Gasolina </option>";

                            elseif (isset($veiculos['veiculo_combustivel']) AND $veiculos['veiculo_combustivel'] == 1) :
                                echo "<option selected disabled='disabled' value=''> Selecione o combustível </option>";
                                echo "<option value='0'> Gasolina </option>";
                                echo "<option value='3'> Etanol </option>";

                            elseif (isset($veiculos['veiculo_combustivel']) AND $veiculos['veiculo_combustivel'] == 2) :
                                echo "<option selected disabled='disabled' value=''> Selecione o combustível </option>";
                                echo "<option value='0'> Gasolina </option>";
                                echo "<option value='3'> Etanol </option>";
                                echo "<option value='5'> GNV </option>";

                            elseif (isset($veiculos['veiculo_combustivel']) AND $veiculos['veiculo_combustivel'] == 3) :
                                echo "<option selected value='3'> Etanol </option>";

                            elseif (isset($veiculos['veiculo_combustivel']) AND $veiculos['veiculo_combustivel'] == 4) :
                                echo "<option selected value='4'> Diesel </option>";

                            endif;
                            ?>
                            
                        </select>
                        <label for="floatingSelectGrid">Combustível:</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                        <input onChange="getkm(this.value)" type="date" class="form-control" name="abast_data" id="input5" value="<?php if (isset($dados['abast_data'])) : echo $dados['abast_data'];
                                                                                                                                    else : echo date('Y-m-d');
                                                                                                                                    endif; ?>" required />
                        <label for="floatingInputGrid">Data:</label>
                    </div>
                </div>


            </div>
            <div class="col-md-12">
                <input type="file" class="form-control form-control-lg" name="image[]" multiple id="upload-img" />
                <div data-id='<?php if(isset($veiculos['abast_id'])); ?>' class="" class="img-thumbs img-thumbs-hidden" id="img-preview"></div>
                <label id="foto" for="upload-img"><i class="fa fa-upload" aria-hidden="true"></i> &nbsp; Selecionar imagem</label>
            </div>
            <br/>

            <input type="hidden" class="form-control" name="cad_autor" id="cad_autor" value="<?php if (isset($dados['cad_autor'])): echo $dados['cad_autor']; else: $dados['cad_autor'] = 'AUTOR'; echo $dados['cad_autor']; endif; ?>"/>
            <br />
            <input type="submit" id="submitBtn" name="abastecimento" value="Confirmar" class="btn-orange">

        </form>

        <?php
        $busca = "SELECT * FROM abastecimento WHERE abast_placa =:placa ORDER BY abast_km DESC";
        $data = $pdo->prepare($busca);
        $data->bindParam(':placa', $_GET['placa']);
        $data->execute();
        $count = $data->rowCount();

        ?>

        <?php
        if ($count <= 0) {
            echo "<label class='titulo'>Veículo sem histórico de abastecimento</label>";
        } else {

        ?>
            <label class="titulo">Histórico de abastecimento:</label>
            <hr>
            <table id="search_table" class="table table-striped">
                <tr class="table-dark">
                    <td scope="col" align="left" style="width:30px;">ID</td>
                    <td scope="col" align="left" style="width:70px;">Placa</td>
                    <td scope="col" align="left" style="width:330px;">Modelo</td>
                    <td scope="col" align="center" style="width:100px;">Valor</td>
                    <td scope="col" align="left" style="width:100px;">Litro</td>
                    <td scope="col" align="left" style="width:100px;">Consumo</td>
                    <td scope="col" align="left" style="width:100px;">KM Atual</td>
                    <td scope="col" align="left" style="width:100px;">KM Rodado</td>
                    <td scope="col" align="center" style="width:200px;">Combustível</td>
                    <td scope="col" align="left" style="width:120px;">Data</td>
                    <td scope="col" align="center" style="width:120px;" colspan="3">Fotos</td>

                </tr>

                <?php foreach ($data as $veiculo) : ?>
                    <?php
                    $media = "SELECT abast_total_litro FROM abastecimento WHERE abast_placa =:placa";
                    $data = $pdo->prepare($media);
                    $data->bindParam(':placa', $_GET['placa']);
                    $data->execute();
                    $count = $data->rowCount();
                    ?>

                    <tr class="table-light">
                        <td align="left" style="">#<?= $veiculo['abast_id']; ?></td>
                        <td align="left" style=""><?= $_GET['placa']; ?></td>

                        <td style="">

                            <a href="editar_abastecimento?id=<?= $veiculo['abast_id']; ?>" class="link">

                                <?= $veiculo['abast_veiculo']; ?>
                        </td>

                        <td align="left" style="">R$: <?php echo number_format($veiculo['abast_valor'], 2, ",", "."); ?></td>
                        <td align="left" style=""><?php echo number_format($veiculo['abast_total_litro'], 3, ".", "."); ?></td>



                        <?php if (isset($veiculo['abast_media'])) { ?>
                            <td align="left" style=""><?= number_format($veiculo['abast_media'], 2, ".", "."); ?> - KM/L </td>
                        <?php } else { ?>
                            <td class="wait" align="left" style=""> Aguardando... </td>
                        <?php } ?>
                        </td>
                        <td align="left" style=""><?= number_format($veiculo['abast_km'], 3, ".", "."); ?></td>


                        <?php if (isset($veiculo['abast_km_rodado'])) { ?>
                            <td align="left" style=""><?= number_format($veiculo['abast_km_rodado'], 0, ".", "."); ?> - KM</td>
                        <?php } else { ?>
                            <td class="wait" align="left" style=""> Aguardando... </td>
                        <?php } ?>
                        </td>



                        <?php
                        if ($veiculo['abast_combustivel'] == 0) :
                            echo "<td align='center'>Gasolina</td>";
                        elseif ($veiculo['abast_combustivel'] == 3) :
                            echo "<td align='center'>Etanol</td>";
                        elseif ($veiculo['abast_combustivel'] == 4) :
                            echo "<td align='center'>Diesel</td>";
                        elseif ($veiculo['abast_combustivel'] == 5) :
                            echo "<td align='center'>GNV</td>";

                        endif;
                        ?>





                        <td align="left" style=""><?= date('d/m/Y', strtotime($veiculo['abast_data'])); ?> </td>

                        <?php
                        $sql = "SELECT * FROM abastecimento_img WHERE abast_id = :abast_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':abast_id', $veiculo['abast_id'], PDO::PARAM_INT);
                        $stmt->execute();
                        $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // Contar o número de registros retornados
                        $count_id = count($imagens);
                        ?>


                        <?php if ($count_id <= 0) { ?>
                            <td align="center">
                                <img src="images/no-img.png" alt="Sem imagem cadastrada" title="Sem imagem cadastrada" width="20" height="20">
                            </td>
                        <?php } else { ?>
                            <td align="center">
                                <a data-id='<?= $veiculo['abast_id']; ?>' class="foto" title="Ver Fotos"><img src="images/imagem.png" alt="<?php if ($count_id == 1) : echo "Imagem cadastrada";
                                                                                                                                            elseif ($count_id >= 2) : echo "Imagens cadastradas";
                                                                                                                                            endif; ?>" title="<?= $count_id ?> imagens cadastradas " width="20" height="20"></a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php
                endforeach;

                require_once('modal/modal.php');
                ?>
            </table>
        <?php }; ?>


    </article>

    <div class="clear"></div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script type="text/javascript" src="js/custom.js"></script>
    <script type="text/javascript" src="js/imagepreview.js"></script>
    </div>