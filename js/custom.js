
function getkm(abastData) {
    const veiculoId = document.getElementById('veiculo_id').value;

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

            if (data === 'Sem Histórico' || data === 'Formato de data inválido' || data === 'Entrada Inválida') {
                kmAnteriorSelect.innerHTML = '<option value="0">Sem Histórico</option>';
                return;
            }

            const kmList = data.split(',');
            kmList.forEach(function (km) {
                var option = document.createElement('option');
                option.value = km;
                option.text = km;
                kmAnteriorSelect.appendChild(option);
            });
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

    // Verifica se o input2 está preenchido e aplica a validação existente
    if (input2.value !== '') {
        validateKmInput(input2);
    }
});

$(document).ready(function () {
    const veiculoId = document.getElementById('veiculo_id');
    const input2 = document.getElementById('input2');
    const input3 = document.getElementById('input3');
    const input4 = document.getElementById('input4');
    const dateInput = document.getElementById('input5');
    const abastKmAnterior = document.getElementById('abast_km_anterior');
    const submitBtn = document.getElementById('submitBtn');

    function validateInputWithDot(input, pattern) {
        const value = input.value.replace(/\./g, ''); // Remove pontos para validação
        if (value === '') {
            input.classList.remove('error', 'is-invalid', 'is-valid');
            return false;
        }
        if (pattern.test(value)) {
            input.classList.remove('error');
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            return true;
        } else {
            input.classList.add('error');
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        }
    }

    function validateMoneyInput(input) {
        const value = input.value.replace(/[^0-9]/g, ''); // Remove qualquer coisa que não seja número
        if (value === '') {
            input.classList.remove('error', 'is-invalid', 'is-valid');
            return false;
        }
        if (value.length >= 4 && value.length <= 6) {
            input.classList.remove('error');
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            return true;
        } else {
            input.classList.add('error');
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        }
    }

    function validateKmInput(input) {
        const value = input.value.replace(/\./g, ''); // Remove pontos para validação
        const currentKm = parseInt(value, 10);
        const previousKm = parseInt(abastKmAnterior.value.replace(/\./g, ''), 10);

        if (value === '') {
            input.classList.remove('error', 'is-invalid', 'is-valid');
            return false;
        }

        if (currentKm < previousKm || value.length < 3) {
            input.classList.add('error');
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            submitBtn.disabled = true;
            return false;
        } else {
            input.classList.remove('error');
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            submitBtn.disabled = false; // Enable submit button if valid
            return true;
        }
    }

    function validateForm() {
        const isValid2 = validateKmInput(input2);
        const isValid3 = validateMoneyInput(input3);
        const isValid4 = input4.value.length >= 4; // Validate input4 length
        const selectedDate = new Date(dateInput.value);
        const currentDate = new Date();
        const isDateValid = selectedDate <= currentDate;

        submitBtn.disabled = !(isValid2 && isValid3 && isValid4 && isDateValid);
    }

    dateInput.addEventListener('change', function () {
        const selectedDate = new Date(dateInput.value);
        const currentDate = new Date();

        if (selectedDate > currentDate) {
            dateInput.style.borderColor = 'red';
            submitBtn.disabled = true;
        } else {
            dateInput.style.borderColor = '';
            validateForm(); // Revalidate form on date change
        }

        // Verifica se o input2 está preenchido e aplica a validação existente
        if (input2.value !== '') {
            validateKmInput(input2);
        }
    });

    input2.addEventListener('blur', function () {
        validateKmInput(input2);
        validateForm();
    });

    input3.addEventListener('blur', function () {
        validateMoneyInput(input3);
        validateForm();
    });

    input4.addEventListener('blur', function () {
        validateForm();
    });

    input2.addEventListener('input', function () {
        let value = input2.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        let maskedValue = value;

        if (value.length <= 3) {
            maskedValue = value; // 000
        } else if (value.length === 4) {
            maskedValue = value.replace(/(\d)(\d{3})/, '$1.$2'); // 0.000
        } else if (value.length === 5) {
            maskedValue = value.replace(/(\d{2})(\d{3})/, '$1.$2'); // 00.000
        } else if (value.length === 6) {
            maskedValue = value.replace(/(\d{3})(\d{3})/, '$1.$2'); // 000.000
        }

        input2.value = maskedValue;

        // Validação da borda vermelha e botão de submit
        const currentKm = parseInt(value, 10);
        const previousKm = parseInt(abastKmAnterior.value.replace(/\./g, ''), 10);
        if (value.length < 3 || currentKm < previousKm) {
            input2.classList.add('is-invalid');
            input2.classList.remove('is-valid');
            submitBtn.disabled = true;
        } else {
            input2.classList.add('is-valid');
            input2.classList.remove('is-invalid');
            submitBtn.disabled = false;
        }
    });

    input3.addEventListener('input', validateForm);
    input4.addEventListener('input', validateForm);

    // Aplicar máscara de moeda ao input3
    $('#input3').mask('000.000,00', { reverse: true });

    // Limitar a entrada de caracteres no campo de moeda
    input3.addEventListener('input', function () {
        const value = input3.value.replace(/[^0-9]/g, '');
        if (value.length > 6) {
            input3.value = input3.value.substring(0, input3.value.length - 1);
        }

        // Validação da borda verde
        validateMoneyInput(input3);
        if (input3.classList.contains('is-valid')) {
            input3.style.borderColor = 'green';
        }
    });

    // Aplicar máscara dinâmica ao input4
    input4.addEventListener('input', function () {
        let value = input4.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        let maskedValue = value;

        if (value.length <= 3) {
            maskedValue = value; // Sem máscara
        } else if (value.length === 4) {
            maskedValue = value.replace(/(\d)(\d{3})/, '$1.$2'); // 0.000
        } else if (value.length === 5) {
            maskedValue = value.replace(/(\d{2})(\d{3})/, '$1.$2'); // 00.000
        } else if (value.length === 6) {
            maskedValue = value.replace(/(\d{3})(\d{3})/, '$1.$2'); // 000.000
        } else if (value.length === 7) {
            maskedValue = value.replace(/(\d)(\d{3})(\d{3})/, '$1.$2.$3'); // 0.000.000
        }

        input4.value = maskedValue;

        // Validação da borda vermelha e botão de submit
        if (value.length < 4) {
            input4.style.borderColor = 'red';
            input4.classList.add('is-invalid');
            input4.classList.remove('is-valid');
            submitBtn.disabled = true;
        } else {
            input4.style.borderColor = 'green';
            input4.classList.add('is-valid');
            input4.classList.remove('is-invalid');
            submitBtn.disabled = false;
        }
    });
});