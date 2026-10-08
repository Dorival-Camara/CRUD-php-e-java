// Validação no navegador (o PHP valida de novo no servidor)
const formulario = document.getElementById("formEquipamento");

if (formulario) {
    formulario.addEventListener("submit", function (event) {
        const nome = document.getElementById("nome");
        const status = document.getElementById("status");
        const categoria = document.getElementById("categoria");

        if (nome.value.trim() === "") {
            event.preventDefault();
            alert("Informe o nome do equipamento.");
            nome.focus();
            return;
        }

        if (status.value === "") {
            event.preventDefault();
            alert("Escolha o status.");
            status.focus();
            return;
        }

        if (categoria.value === "") {
            event.preventDefault();
            alert("Escolha a categoria.");
            categoria.focus();
            return;
        }
    });
}

// Confirmação antes de excluir
function confirmarExclusao() {
    return confirm("Deseja realmente excluir este equipamento?");
}
