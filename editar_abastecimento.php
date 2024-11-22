<?php
session_start();
require_once "_app/conf.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);




if (!isset($id)) :
  $_SESSION['msg'] = '<div class="trigger error">Erro, você está tentando editar um abastecimento que não existe ou foi apagado!</div>';
  header("Location:  ./");
  die;
endif;



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
  $delete_id = $_POST['delete_id'];


  $stmt = $pdo->prepare("SELECT img_path FROM abastecimento_img WHERE img_id = :id");
  $stmt->execute(['id' => $delete_id]);
  $filename = $stmt->fetchColumn(); // Fetch the single column result


  if ($filename && file_exists('imagens/' . $filename)) {
    unlink('imagens/' . $filename);
  }

  // Delete the record from the database
  $stmt = $pdo->prepare("DELETE FROM abastecimento_img WHERE img_id = :id");
  $stmt->execute(['id' => $delete_id]);

  $_SESSION['msg'] = '<div class="trigger accept">Imagem excluída com sucesso!</div>';
  header("Location: editar_abastecimento&id=" . $id);
  exit();
}

$abastecimento = "SELECT * FROM abastecimento WHERE abast_id =:id";
$abast_veiculo = $pdo->prepare($abastecimento);
$abast_veiculo->bindParam(':id', $id);
$abast_veiculo->execute();
$count_id = $abast_veiculo->rowCount();
$abast = $abast_veiculo->fetch(PDO::FETCH_ASSOC);


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

    <label class="titulo">Editar dados do abastecimento:</label>
    <hr>

    <?php
    if (isset($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="actions/editar_abastecimento.php" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $id ?>" />
      <input type="hidden" name="veiculo_id" value="<?php if (isset($abast['veiculo_id'])) echo $abast['veiculo_id']; ?>" />

      <div class="row g-2">
        <div class="col-md-3">
          <div class="form-floating">
            <input readonly type="text" class="form-control" name="abast_placa" id="placa" value="<?php if (isset($abast['abast_placa'])) echo $abast['abast_placa']; ?>" />
            <label for="floatingInputGrid">Placa:</label>
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-floating">
            <input readonly type="text" class="form-control" name="abast_veiculo" id="abast_veiculo" value="<?php if (isset($abast['abast_veiculo'])) echo $abast['abast_veiculo']; ?>" />
            <label for="floatingInputGrid">Modelo:</label>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" class="maskMoney form-control" maxlength="9" name="abast_valor" id="input3" value="<?php if (isset($abast['abast_valor'])) echo $abast['abast_valor']; ?>" required />
            <label for="floatingInputGrid">Valor:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="maskMoney form-control" maxlength="9" name="abast_total_litro" id="input4" value="<?php if (isset($abast['abast_total_litro'])) echo $abast['abast_total_litro']; ?>" required />
            <div class="invalid-feedback"></div>
            <label for="floatingInputGrid">Total de Litro:</label>
          </div>
        </div>

        <?php
        $sql = "SELECT * FROM abastecimento WHERE abast_id < :id AND abast_placa = :abast_placa ORDER BY abast_id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':abast_placa', $abast['abast_placa']);
        $stmt->execute();
        $count = $stmt->rowCount();
        $lastRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="col-md-3">
          <div class="form-floating">
            <select name="abast_km_anterior" id="abast_km_anterior" class="form-select">
              <option selected value="<?php if (isset($lastRecord['abast_km'])) : echo $lastRecord['abast_km'];
                                      else : echo '0';
                                      endif; ?>">
                <?php if (isset($lastRecord['abast_km'])) : echo $lastRecord['abast_km'];
                else : echo 'Sem Histórico';
                endif; ?>
              </option>
            </select>
            <label for="abast_km_anterior">Último KM</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" maxlength="7" name="abast_km" id="input2" value="<?php if (isset($abast['abast_km'])) echo $abast['abast_km']; ?>" required />
            <label for="floatingInputGrid">KM:</label>
          </div>
        </div>

        <input readonly type="hidden" class="form-control" maxlength="7" name="" id="input1" value="" required />
        <input readonly type="hidden" class="form-control" maxlength="7" name="" id="input6" value="" required />

        <div class="col-md-3">
          <div class="form-floating">
            <select class="form-select" name="abast_combustivel" id="abast_combustivel">
              <?php
              if ($abast['abast_combustivel'] == 0) :
                echo "<option selected value='0'> Gasolina </option>";
              elseif ($abast['abast_combustivel'] == 1) :
                echo "<option selected disabled='disabled' value=''> Selecione o combustível </option>";
                echo "<option value='0'> Gasolina </option>";
                echo "<option value='3'> Etanol </option>";
              elseif ($abast['abast_combustivel'] == 2) :
                echo "<option selected disabled='disabled' value=''> Selecione o combustível </option>";
                echo "<option value='0'> Gasolina </option>";
                echo "<option value='3'> Etanol </option>";
                echo "<option value='5'> GNV </option>";
              elseif ($abast['abast_combustivel'] == 3) :
                echo "<option selected value='3'> Etanol </option>";
              elseif ($abast['abast_combustivel'] == 4) :
                echo "<option selected value='4'> Diesel </option>";
              endif;
              ?>
            </select>
            <label for="floatingSelectGrid">Combustível:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="date" class="form-control" name="abast_data" id="input5" value="<?= $abast['abast_data']; ?>" required />
            <label for="floatingInputGrid">Data:</label>
          </div>
        </div>

      </div>


      <br />

      <div class="col-md-12">
        <input type="file" class="form-control form-control-lg" name="image[]" multiple id="upload-img" />
        <div data-id='<?php $abast['abast_id']; ?>' class="" class="img-thumbs img-thumbs-hidden" id="img-preview"></div>
        <label id="foto" for="upload-img"><i class="fa fa-upload" aria-hidden="true"></i> &nbsp; Selecionar imagem</label>
      </div>
      <br />


      <input type="submit" name="submitBtn" id="submitBtn" value="Editar Abastecimento" class="btn-orange">
    </form>

    <?php
    $imagens = "SELECT * FROM abastecimento_img WHERE abast_id = :abast_id";
    $imgs = $pdo->prepare($imagens);
    $imgs->bindParam(':abast_id', $id);
    $imgs->execute();
    $count_img = $imgs->rowCount();
    $images = $imgs->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container mt-5">
      <div id="image-gallery" class="row">
        <?php foreach ($images as $img) : ?>
          <div class="col-md-3 position-relative">
            <div class="wrapper-thumb">
              <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?');">
                <input type="hidden" name="delete_id" value="<?= $img['img_id'] ?>">
                <span data-id='<?= $img['abast_id']; ?>' data-img-id='<?= $img['img_id']; ?>' class="remove-btn remove-img">&times;</span>
              </form>
              <a data-id='<?= $img['abast_id']; ?>' data-img-id='<?= $img['img_id']; ?>' class="foto" title="Ver Fotos">
                <img src="imagens/<?= $img['img_path'] ?>" class="img-preview-thumb">
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </article>
</body>

</html>

<?php require_once('modal/modal.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/imagepreview.js"></script>