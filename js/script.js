document.addEventListener("DOMContentLoaded", function() {
    const formProposta = document.getElementById("formProposta");
    const beneficiariosInput = document.getElementById("beneficiariosInput");
    const calcularPrecoButton = document.getElementById("calcularPreco");
    const precoTotal = document.getElementById("precoTotal");
    const registroPlanoSelect = document.getElementById("registro_plano");

    formProposta.addEventListener("submit", function(event) {
        event.preventDefault();

        const dadosProposta = {
            registro_plano: registroPlanoSelect.value,
            quantidade_beneficiarios: parseInt(formProposta.quantidade_beneficiarios.value),
            beneficiarios: []
        };

        const benefInputs = beneficiariosInput.querySelectorAll('div');
        benefInputs.forEach(benefDiv => {
            const nome = benefDiv.querySelector('input[name="nome_beneficiario"]').value;
            const idade = parseInt(benefDiv.querySelector('input[name="idade_beneficiario"]').value);
            dadosProposta.beneficiarios.push({ nome, idade });
        });

        fetch("api/PlanosAPI.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(dadosProposta)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro na requisição da API");
            }
            return response.text();
        })
        .then(data => {
            if (!data) {
                throw new Error("Resposta da API vazia.");
            }

            try {
                const jsonData = JSON.parse(data);
                if (jsonData && jsonData.precoTotal !== undefined) {
                    precoTotal.textContent = `Preço Total do Plano: R$ ${jsonData.precoTotal.toFixed(2)}`;
                } else {
                    throw new Error("Resposta da API inválida ou preço não encontrado.");
                }
            } catch (error) {
                throw new Error("Erro ao fazer parsing da resposta da API: " + error);
            }
        })
        .catch(error => {
            console.error("Erro ao calcular preço:", error);
            precoTotal.textContent = "Erro ao calcular o preço. Verifique os dados informados.";
        });
    });

    formProposta.addEventListener("change", function(event) {
        if (event.target.matches('input[name="registro_plano"]') || event.target.matches('input[name="quantidade_beneficiarios"]')) {
            beneficiariosInput.innerHTML = '';
            const quantidadeBeneficiarios = parseInt(formProposta.quantidade_beneficiarios.value);
            for (let i = 1; i <= quantidadeBeneficiarios; i++) {
                const beneficiarioDiv = document.createElement("div");
                beneficiarioDiv.innerHTML = `
                    <input type="text" name="nome_beneficiario" placeholder="Nome Beneficiário ${i}" required>
                    <input type="number" name="idade_beneficiario" placeholder="Idade Beneficiário ${i}" required>
                `;
                beneficiariosInput.appendChild(beneficiarioDiv);
            }
        }
    });

    // Adicionar as opções do select baseadas no arquivo plans.json
    fetch("api/plans.json")
        .then(response => response.json())
        .then(plans => {
            plans.forEach(plan => {
                const option = document.createElement("option");
                option.value = plan.registro;
                option.textContent = plan.nome;
                registroPlanoSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Erro ao carregar os planos:", error));
});

