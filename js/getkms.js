function getkm(abastData) {
    const veiculoId = document.getElementById('veiculo_id').value;   
    const submitBtn = document.getElementById('submitBtn');   

    $.ajax({
        type: "POST",
        url: "app/busca_km.php",
        data: {
            abast_data: abastData,
            veiculo_id: veiculoId
        },
        success: function (data) {
            const kmAnteriorSelect = document.getElementById('abast_km_anterior');
            kmAnteriorSelect.innerHTML = '';

            if (data === 'Sem dados cadastrados' || data === 'Formato de data inválido' || data === 'Entrada Inválida') {
                kmAnteriorSelect.innerHTML = '<option value="0">Sem dados cadastrados</option>';
                return;
            }

            const kmList = data.split(',');
            kmList.forEach(function (km) {
                var option = document.createElement('option');
                option.value = km;
                option.text = km;
                kmAnteriorSelect.appendChild(option);
            });

            //checkValue();
        },
        error: function (xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}
    // Adiciona um listener para o campo de data
    document.getElementById('input5').addEventListener('change', function () {
        const abastData = this.value;
        getkm(abastData);
    });









