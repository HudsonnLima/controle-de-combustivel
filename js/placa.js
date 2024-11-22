document.addEventListener("DOMContentLoaded", function () {
  const placaInput = document.getElementById("placa");
  const submitBtn = document.getElementById("submitBtn");

  placaInput.addEventListener("input", function () {
    // Remove caracteres inválidos com regex
    let value = placaInput.value.toUpperCase(); // Força letras maiúsculas
    value = value.replace(/[^A-Z0-9]/g, ""); // Remove caracteres que não sejam letras ou números

    // Aplica o formato esperado
    if (value.length > 0) {
      value = value.substring(0, 8); // Limita a 8 caracteres

      let formattedValue = "";
      for (let i = 0; i < value.length; i++) {
        if (i < 3) {
          // Os 3 primeiros caracteres devem ser letras
          formattedValue += value[i].match(/[A-Z]/) ? value[i] : "";
        } else if (i === 3) {
          // O quarto caractere deve ser um número
          formattedValue += value[i].match(/[0-9]/) ? value[i] : "";
        } else if (i === 4) {
          // O quinto caractere pode ser letra ou número
          formattedValue += value[i];
        } else if (i > 4) {
          // Os demais caracteres devem ser números
          formattedValue += value[i].match(/[0-9]/) ? value[i] : "";
        }
      }

      // Atualiza o valor do input
      placaInput.value = formattedValue;
    }
  });

  placaInput.addEventListener("blur", function () {
    const value = placaInput.value.toUpperCase().trim();

    // Se o campo estiver vazio
    if (!value) {
        // Aplica a borda vermelha
        placaInput.classList.add("is-invalid");

        // Adiciona a classe alerta-exclamacao para exibir o alerta ao lado direito da div
        placaInput.classList.add(".alerta-exclamacao");

        // Desabilita o botão de submit
        submitBtn.disabled = true;
    } else {
        // Verifica a existência da placa (simulação)
        fetch(`app/verificar_placa?placa=${value}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    // Se a placa já existir, aplica a borda vermelha e alerta
                    placaInput.classList.add('is-invalid');
                    placaInput.classList.add('.alerta-exclamacao');

                    // Desabilita o botão de submit
                    submitBtn.disabled = true;
                } else {
                    // Caso contrário, remove a borda vermelha e alerta, e habilita o botão
                    placaInput.classList.remove('is-invalid');
                    placaInput.classList.remove('.alerta-exclamacao');

                    submitBtn.disabled = false;
                }
            })
            .catch(error => console.error("Erro ao verificar placa:", error));
    }
});




});
