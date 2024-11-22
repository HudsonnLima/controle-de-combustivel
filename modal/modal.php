<!--VER IMAGEM-->
<script type='text/javascript'>
  $(document).ready(function() {
    $('.foto').click(function() {
      var abast_id = $(this).data('id');
      $.ajax({
        url: 'modal/ver_imagem.php',
        type: 'post',
        data: {
          abast_id: abast_id
        },
        success: function(response) {
          $('.modal-img').html(response);
          $('#ver_img').modal('show');
        }
      });
    });
  });
</script>
</div>

<div class="modal fade" id="ver_img" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-img">

            </div>
        </div>
    </div>

</div>