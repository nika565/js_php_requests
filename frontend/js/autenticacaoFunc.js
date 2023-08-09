// Pega o token armazenado no localStorage
const token = localStorage.getItem('token');

// Quando a pagina for carregada, dispara uma função para verificar se o token é válido
window.addEventListener('load', () => {

    verificaToken(token);

});

async function verificaToken(token) {

    // objeto a ser enviado para o servidor
    objToken = {
        token: token
    }

    try {

        const resposta = await fetch(`http://localhost/js_php_requests/backend/php/autenticacaoFunc.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(objToken)
        });

        if(!resposta.ok) throw new Error(`Erro na requisição. Status Code: ${resposta.status}`);

        const objetoRetornado = await resposta.json();

        // Verificando a resposta do servidor
        console.log(objetoRetornado);

        verificaPermissao(objetoRetornado);

    } catch (erro) {
        console.error(`ERRO: ${erro}`);
    }

}

// Função para saber se a autenticação é falsa
function verificaPermissao (objeto) {

    if (objeto.autenticado === false) {

        // Ocultar o body
        document.body.style.display = "none";

        window.location.href = '../pages/index.html'

        alert(`NÃO É POSSÍVEL ACESSAR ESSA PÁGINA`);

    }

}
