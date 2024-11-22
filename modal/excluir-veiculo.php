<!-- Modal Exclusão -->
<div class="modal fade myModalExclusao" tabindex="-1" aria-labelledby="myModalLabelExclusao" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabelExclusao">Excluir Veículo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-exclusao">
        <!-- Conteúdo será carregado via AJAX -->
      </div>
    </div>
  </div>
</div>



<script>
  // Modal Exclusão
  document.querySelector('.myModalExclusao').addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget; // Botão que acionou o modal
    var veiculoId = button.getAttribute('data-veiculo-id'); // ID do veículo

    // Requisição AJAX
    $.ajax({
      url: 'modal/excluir_veiculo.php',
      type: 'POST',
      data: { veiculo_id: veiculoId },
      success: function(response) {
        document.querySelector('.modal-body-exclusao').innerHTML = response;
      },
      error: function() {
        document.querySelector('.modal-body-exclusao').innerHTML = '<p>Ocorreu um erro ao processar a solicitação.</p>';
      }
    });
  });

 
</script>
