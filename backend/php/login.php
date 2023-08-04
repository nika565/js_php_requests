<?php
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$_SESSION['seila'] = 'seila';

// Solicitando o arquivo do banco
require_once('../database/conn.php');

function verificaSenha($senhaUsuario, $linhaBanco){
    $verificacao = password_verify($senhaUsuario, $linhaBanco['senha']);

    if ($verificacao) {

        // Gerando um token
        $token = password_hash($linhaBanco['id'], PASSWORD_DEFAULT);
        $_SESSION['token'] = $token;
        $nome = $linhaBanco['nome'];
        $cargo = $linhaBanco['cargo'];

        // Objeto retornado para o cliente
        $cliente = [
            'token' => $token,
            'nome' => $nome,
            'cargo' => $cargo
        ];

        echo json_encode($cliente);

    } else {

        echo json_encode(['msgErro' => 'Senha inválida...']);

    }


}

// Função para verificar se os dados de login estão presentes no banco
function verificaDadosDeLoginNoBanco($dados, $conexao)
{

    $email = $dados['email'];

    //Query
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
            
            verificaSenha($senhaCliente, $linha);

        } else {
            echo json_encode(['msgErro' => 'USUÁRIO INVÁLIDO!']);
        }

    } else {
        echo json_encode(['msgErro' => 'ALGO DEU ERRADO!']);
    }

}

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
        // verificando dados no banco
        verificaDadosDeLoginNoBanco($dados, $conn);
    }

} else {

    echo json_encode(['msgErro' => 'Algo deu errado...']);

}

?>