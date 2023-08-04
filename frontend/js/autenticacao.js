// Somente ADM´s podem abrir essa página
const cargo = localStorage.getItem('cargo');
const token = localStorage.getItem('token');

window.addEventListener('load', () => {

    verificaToken(token);

});

async function verificaToken(token) {

    // objeto a ser enviado para o servidor
    objToken = {
        token: token
    }

    try {

        const resposta = await fetch(`http://localhost/study_php/crud_php/backend/php/autenticacao.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(objToken)
        });

        if(!resposta.ok) throw new Error(`Erro na requisição. Status Code: ${resposta.status}`);

        const objetoRetornado = await resposta.json();

        console.log(objetoRetornado);

        verificaPermissao(objetoRetornado);

    } catch (erro) {
        console.error(`ERRO: ${erro}`);
    }

}

function verificaPermissao (objeto) {

    if (cargo !== 'adm' || objeto.autenticado !== true) {

        // Ocultar o body
        document.body.style.display = "none";

        window.location.href = '../pages/index.html'

        alert(`NÃO É POSSÍVEL ACESSAR ESSA PÁGINA`);

    }

}
