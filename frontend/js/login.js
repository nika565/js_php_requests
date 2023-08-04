/*--------------------------------- REQUISIÇÃO BACK-END -----------------------------------------*/


// Parando o evento do formulário
const form = document.querySelector('#formularioLogin');

form.addEventListener('submit', evento => {

    evento.preventDefault();

    // Pegando os dados do formulário
    const email = document.querySelector('#email').value;
    const senha = document.querySelector('#senha').value;

    // Objeto login para ser enviado na requisição
    const login = {
        email: email,
        senha: senha
    };

    // Verificando no back-end se os dados enviados existem
    verificandoLogin(login);

});

async function verificandoLogin(dados) {

    try {

        // Enviando a requisição
        const respostaServidor = await fetch(`http://localhost/study_php/crud_php/backend/php/login.php`, {

            method: 'POST',
            Headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dados)

        });

        // verificando se deu erro na requisição
        if (!respostaServidor.ok) throw new Error(`Erro na requisição. Status Code: ${respostaServidor.status}`);

        // Convertendo os dados em um objeto json comum...
        const objetoRetornadoDoServidor = await respostaServidor.json();

        console.log(objetoRetornadoDoServidor);

        // Tratando os dados enviados
        if (typeof (objetoRetornadoDoServidor.token) === 'undefined') {
            alert(objetoRetornadoDoServidor.msgErro);
        } else {
            confirmaLogin(objetoRetornadoDoServidor);
        }

    } catch (erro) {

        console.log(`ERRO: ${erro}`);

    }

}

function confirmaLogin(respostaServidor) {

    //Salvando os dados no localStorage
    localStorage.setItem('cargo', respostaServidor.cargo);
    localStorage.setItem('token', respostaServidor.token);

    if (respostaServidor.cargo === 'adm') {

        // Levando para a pagina correspondente
        window.location.href = '../pages/adm.html';
        alert(`BEM VINDO: ${respostaServidor.nome}`);

    } else {

        // Levando para a pagina correspondente
        window.location.href = '../pages/funcionario.html';
        alert(`BEM VINDO: ${respostaServidor.nome}`);

    }



}


/*--------------------------------- FIRULA DO FRONT -----------------------------------------*/

// Botão para exibir a senha
const btnExibir = document.querySelector('#exibirSenha');

btnExibir.addEventListener('click', () => {

    // Selecionar o campo de senha
    const campoSenha = document.querySelector('#senha');

    exibirSenha(campoSenha);

});

// Função para exibir a senha
function exibirSenha(campo) {

    // Mudando o tipo do campo
    if (campo.type === 'password') {

        campo.type = 'text'
        btnExibir.innerHTML = 'Ocultar'

    } else {

        campo.type = 'password'
        btnExibir.innerHTML = 'Mostrar'

    }

}