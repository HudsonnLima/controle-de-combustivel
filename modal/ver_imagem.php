<?php
//require_once "../_app/Config.inc.php";
require_once "../_app/conf.php";

$abast_id = $_POST['abast_id'];
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$read = "SELECT * FROM abastecimento_img WHERE abast_id = {$abast_id}";
$stmt = $pdo->prepare($read);
$stmt->execute();
$count = $stmt->rowCount();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
  .modal-content {
    -webkit-box-shadow: none;
    box-shadow: none;
    background: transparent;
    border: none;
  }
</style>


<div id="slide" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-wrap="false">
  <div class="carousel-indicators">
    <?php
    $controle = 0;
    while ($controle < $count) {
      $ativo = "";
      if ($controle == 0) {
        $ativo = "active";
      }
      echo "<button type='button' data-bs-target='#slide' data-bs-slide-to='$controle' class='$ativo'
                aria-current='true' aria-label='Slide $controle'></button>";
      $controle++;
    }
    ?>
  </div>

  <div class="carousel-inner">

    <?php

    foreach ($data as $index => $values) {
    ?>

      <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" class="d-block w-100" alt="">
        <img src="imagens/<?= $values['img_path'] ?>" width="1024" height="640" class="d-block w-100 img_abast" alt="...">
      </div>
    <?php
    }
    ?>

  </div>


  <button class="carousel-control-prev" type="button" data-bs-target="#slide" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#slide" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>


</div>


<script>
  $(document).ready(function() {
    $('.slide').carousel({
      interval: 550 * 10
    });
  });


  $(document).ready(function() { // on document ready
    checkitem();
  });

  $('#slide').on('slid.bs.carousel', checkitem);

  function checkitem() // check function
  {
    var $this = $('#slide');
    if ($('.carousel-inner .carousel-item:first').hasClass('active')) {
      // Hide left arrow
      $('.carousel-control-prev', $this).hide();
      // But show right arrow
      $('.carousel-control-next', $this).show();
    } else if ($('.carousel-inner .carousel-item:last').hasClass('active')) {
      // Hide right arrow
      $('.carousel-control-prev', $this).show();
      // But show left arrow
      $('.carousel-control-next', $this).hide();
    } else {
      $('.carousel-control-prev, .carousel-control-next', $this).show();
    }
  }
</script>