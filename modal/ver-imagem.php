<!-- Modal Inativo -->
<div class="modal fade ModalInativo" tabindex="-1" aria-labelledby="myModalLabelInativo" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabelInativo">Veículo Inativo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-inativo">
        <!-- Conteúdo será carregado via AJAX -->
      </div>
    </div>
  </div>
</div>


<script>
   // Modal Inativo
   document.querySelector('.ModalInativo').addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget; // Botão que acionou o modal
    var inativoId = button.getAttribute('data-inativo-id'); // ID inativo

    // Requisição AJAX
    $.ajax({
      url: 'modal/veiculo_inativo.php',
      type: 'POST',
      data: { inativo_id: inativoId },
      success: function(response) {
        document.querySelector('.modal-inativo').innerHTML = response;
      },
      error: function() {
        document.querySelector('.modal-inativo').innerHTML = '<p>Ocorreu um erro ao processar a solicitação.</p>';
      }
    });
  });
</script>