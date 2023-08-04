/*
---------------------------------------------POST---------------------------------------------
*/

// Pegando o evento de "submit" do formulário
const form = document.querySelector('.formulario');
form.addEventListener('submit', evento => {

    // Pausando o evento para pegar os dados do formulário
    evento.preventDefault();

    // Selecionando os valores presentes nos inputs do formulário HTML
    const nome = document.querySelector('.nome').value;
    const email = document.querySelector('.email').value;
    const senha = document.querySelector('.senha').value;
    const cargo = document.querySelector('#cargo').value;

    /* Criando um objetos com esses dados do formulário pois ele vai se tronar um objeto JSON
    para ser enviado na requisição */
    const pessoa = {
        nome: nome,
        email: email,
        cargo: cargo,
        senha: senha
    }

    // Passando o objeto que vai ser enviado para o back-end
    enviaBackEnd(pessoa);
});

// Função assíncrona para enviar os dados para o Back-end
async function enviaBackEnd(dadosEnviados) {
    /* Tratando os erro com try/catch: Como não temos certeza se a requisição vai dar certo
    tratamos os erros dessa forma, caso a Promise presente no fetch retorne um erro
    Esse erro caíra no bloco catch para ser tratado */
    try {

        // Enviando para o back-end
        // *ATENÇÃO* a url tem base no caminho de pastas, então se o caminho de pastas for diferente, é só copiar a url do arquivo PHP E JOGAR NO FETCH
        const resposta = await fetch(`http://localhost/study_php/crud_php/backend/php/post.php`, {
            // Método da requisição
            method: 'POST',
            // Cabeçalho da requisição informando o conteúdo
            headers: {
                "Content-Type": "application/json"
            },
            // Corpo da requisição onde será enviado os dados (O que realmente interessa hahahaha)
            body: JSON.stringify(dadosEnviados)
        });

        // Verificando se deu erro ao fazer a requisição
        if (!resposta.ok) {
            throw new Error('Erro na requisição');
        }

        // Pegando a resposta do servidor e convertendo em um objeto javascript
        const dados = await resposta.json();

        // Resposta do servidor sendo exibida para o cliente
        alert(dados.mensagem);

    } catch (error) {
        // Mostra o possível erro no console
        console.error('Erro', error);
    }
}


/*
---------------------------------------------GET---------------------------------------------
*/

// Selecionando o botão para ativar a função:
const btnPesquisar = document.querySelector('.btnPesquisar');

btnPesquisar.addEventListener('click', () => {

    // Resgatando o valor do input
    const valor = document.querySelector('.pesquisa').value;

    // Eviando o valor do input para o back-end
    buscarBackEnd(valor);

});

// Função assíncrona para resgatar os dados para o Back-end
async function buscarBackEnd(parametro) {

    try {

        // Requisição "GET" enviando um parâmetro para o PHP
        // Fetch faz o "GET" por padrão então a configuração não é obrigatória

        // *ATENÇÃO* a url tem base no caminho de pastas, então se o caminho de pastas for diferente, é só copiar a url do arquivo PHP E JOGAR NO FETCH
        const resposta = await fetch(`http://localhost/study_php/crud_php/backend/php/get.php?nome=${parametro}`);

        // Convertendo a resposta em um objeto Javascript
        const dados = await resposta.json();

        // exibindo dados no console
        console.log(dados);

        // Jogando em uma função para ser exibido na tela
        exibir(dados);

    } catch (erro) {
        // Tratando o possível erro
        console.error(`ERRO: ${erro}`);
    }
}

// Função para exibir os dados na Tela
function exibir(dados) {
    const lista = document.querySelector('.lista');

    const usuario = document.createElement('li');

    if (typeof (dados.nome) === 'undefined') {
        alert(dados.msgErro);
    } else {
        usuario.innerText = ` ${dados.id}: ${dados.nome} -> ${dados.cargo}`
        lista.appendChild(usuario);
    }
}