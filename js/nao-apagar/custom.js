function getkm(abastData) {
    const veiculoId = document.getElementById('veiculo_id').value;

    $.ajax({
        type: "POST",
        url: "../admin/system/logistica/app/busca_km.php",
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
    const input1 = document.getElementById('input1');
    const input2 = document.getElementById('input2');
    const input3 = document.getElementById('input3');
    const input4 = document.getElementById('input4');
    const input6 = document.getElementById('input6');
    const dateInput = document.getElementById('input5');
    const abastKmAnterior = document.getElementById('abast_km_anterior');
    const submitBtn = document.getElementById('submitBtn');

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

    function calculateAndSetInput1() {
        const value3 = parseInt(input3.value.replace(/[^\d]/g, '') + '0', 10); // Adiciona um zero ao final
        const value4 = parseInt(input4.value.replace(/[^\d]/g, ''), 10);

        if (!isNaN(value3) && !isNaN(value4) && value4 !== 0) {
            const result = value3 / value4;
            input1.value = result.toFixed(2); // Armazena o resultado no input1
        } else {
            input1.value = '';
        }

        validateInput1And6(); // Chama a validação de input1 e input6 após o cálculo
    }

    function validateInput1And6() {
        const value1 = parseFloat(input1.value);
        const value6 = parseFloat(input6.value);

        if (isNaN(value1) || isNaN(value6)) {
            return;
        }

        const minAllowed = value6 * 0.8;
        const maxAllowed = value6 * 1.2;

        if (value1 < minAllowed || value1 > maxAllowed) {
            input4.style.borderColor = 'red';
            submitBtn.disabled = true;
        } else {
            input4.style.borderColor = 'green';
            submitBtn.disabled = false;
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
        calculateAndSetInput1(); // Calcula e define o valor do input1
    });

    input4.addEventListener('blur', function () {
        validateForm();
        calculateAndSetInput1(); // Calcula e define o valor do input1
    });

    function validateInput4() {
        const value4 = input4.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
        const value1 = parseFloat(input1.value);
        const value6 = parseFloat(input6.value);
    
        if (value4.trim() === '') {
            // Se input4 estiver vazio, remover estilos e habilitar botão
            input4.style.borderColor = '';
            input4.classList.remove('is-invalid', 'is-valid');
            submitBtn.disabled = false;
            return;
        }
    
        // Verifica a margem de 20% entre input1 e input6
        const minAllowed = value6 * 0.8;
        const maxAllowed = value6 * 1.2;
        const isMarginValid = value1 >= minAllowed && value1 <= maxAllowed;
    
        // Verifica o comprimento do valor de input4
        const isLengthValid = value4.length >= 4;
    
        if (!isLengthValid || !isMarginValid) {
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
    }
    
    // Chama validateInput4 nas situações apropriadas
    input4.addEventListener('input', function () {
        validateInput4();
        calculateAndSetInput1(); // Calcula e define o valor do input1
    });
    
    input6.addEventListener('input', function () {
        validateInput4(); // Valida input4 e input6 quando o input6 recebe entrada
    });
    
    // Chamada adicional de validateInput4 no cálculo de input1
    function calculateAndSetInput1() {
        const value3 = parseInt(input3.value.replace(/[^\d]/g, '') + '0', 10); // Adiciona um zero ao final
        const value4 = parseInt(input4.value.replace(/[^\d]/g, ''), 10);
    
        if (!isNaN(value3) && !isNaN(value4) && value4 !== 0) {
            const result = value3 / value4;
            input1.value = result.toFixed(2); // Armazena o resultado no input1
        } else {
            input1.value = '';
        }
    
        validateInput4(); // Chama a validação de input4 após o cálculo
    }
    
    // Outras partes do código permanecem inalteradas
    

    input6.addEventListener('blur', function () {
        validateInput1And6(); // Valida input1 e input6 quando o input6 perde o foco
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

    input3.addEventListener('input', function () {
        validateForm();
        calculateAndSetInput1(); // Calcula e define o valor do input1
    });

    input4.addEventListener('input', function () {
        validateForm();
        calculateAndSetInput1(); // Calcula e define o valor do input1
    });

    input6.addEventListener('input', function () {
        validateInput1And6(); // Valida input1 e input6 quando o input6 recebe entrada
    });

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
            maskedValue = value.replace(/(\d{1})(\d{3})(\d{3})/, '$1.$2.$3'); // 0.000.000
        } else if (value.length === 8) {
            maskedValue = value.replace(/(\d{2})(\d{3})(\d{3})/, '$1.$2.$3'); // 00.000.000
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

        calculateAndSetInput1(); // Calcula e define o valor do input1 quando o input4 recebe entrada
    });
});
