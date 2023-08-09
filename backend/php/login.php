<?php
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


// Solicitando o arquivo do banco
require_once('../database/conn.php');

// Função para verificar a senha
function verificaSenha($senhaUsuario, $linhaBanco){
    /*
        Usando o Password_verify para verificar se o hash da senha salva no banco corresponde a senha que o usuário mandou no login
    */
    $verificacao = password_verify($senhaUsuario, $linhaBanco['senha']);

    // Caso a verificação seja "true"
    if ($verificacao) {

        /*
            Criando uma variável "token" que vai armazenar o ID retornado do banco com criptografia  
        */
        $token = password_hash($linhaBanco['id'], PASSWORD_DEFAULT);

        // Armazenando o token criptografado para possível validação
        $_SESSION['token'] = $token;

        // Salvando o cargo retornado do banco
        $cargo = $linhaBanco['cargo'];

        // Salvando o cargo  em uma sessão para possível validação
        $_SESSION['cargo'] = $cargo;

        // Objeto com o token de validação que será retornado para o cliente
        $cliente = [
            'token' => $token,
        ];

        echo json_encode($cliente);

    } else {

        echo json_encode(['msgErro' => 'Senha inválida...']);

    }


}

// Função para verificar se os dados de login estão presentes no banco
function verificaDadosDeLoginNoBanco($dados, $conexao)
{

    // Como o email é único, vamos verificar se ele existe no banco
    $email = $dados['email'];

    //Query para retornar o usuário com o email correspondente
    $query = "SELECT * FROM clientes WHERE email = '$email'";

    // Executando a query
    $resultado = mysqli_query($conexao, $query);

    // Verificando se a query deu certo
    if ($resultado) {

        // Armazenando o resultado retornado pela query
        $linha = mysqli_fetch_assoc($resultado);

        // Verificando se algum resultado foi retornado
        if ($linha) {

            // Senha que o usuário digitou
            $senhaCliente = $dados['senha'];
            
            /*
                A senha será verificada separadamente, pois com os resultados retornados da
                query acima, faremos uma comparação se a senha que o usuário mandou
                corresponde ao hash salvo no banco.

                Para isso mandaremos a senha que o usuário digitou e o objeto retornado da 
                query do banco de dados, pois uma das propriedades presentes é a senha 
                presente no banco de dados.

                Será mandado todos os resultados da query pois, se a senha for válida será 
                usado outros registros também retornados da query, por isso não estou mandando 
                somente a senha... 
            */
            verificaSenha($senhaCliente, $linha);

        } else {
            echo json_encode(['msgErro' => 'USUÁRIO INVÁLIDO!']);
        }

    } else {
        echo json_encode(['msgErro' => 'ALGO DEU ERRADO!']);
    }

}


// Verificando o método da requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pegando o corpo da requisição
    $json = file_get_contents('php://input');

    // Convertendo o corpo json em objeto
    $dados = json_decode($json, true);

    // Verificar se o JSON é válido
    if ($dados === null) {
        $resposta = [
            'msgErro' => 'JSON inválido'
        ];

        echo json_encode($resposta);

    } else {
        /*
            Essa função é responsável por verificar se os dados enviados são correspondentes aos
            que estão salvos no banco.

            é passado dois parâmetros:
            1. Objeto que contém os dados enviados.
            2. A variável que representa a conexão com o banco de dados.
        */
        verificaDadosDeLoginNoBanco($dados, $conn);
    }

} else {

    echo json_encode(['msgErro' => 'Algo deu errado...']);

}

?>