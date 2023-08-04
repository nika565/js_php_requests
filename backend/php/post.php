<?php
// Definindo qual domínio podde acessar esse arquivo
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

// Definindo Quais métodos podem ser usados
header('Access-Control-Allow-Methods: POST');

// Definindo quais cabeçalhos serão permitidos na requisição
header('Access-Control-Allow-Headers: Content-Type');

// Definindo o cabeçalho: informando para o navegador que será retornado um JSON
header("Content-Type: application/json");

// Solicitando o arquivo de conexão com o banco de dados
require_once('../database/conn.php');

function guardaBanco($dados, $conexao)
{

    // Pegando os dados pessoais do array de dados para salvar no banco
    $nome = $dados['nome'];
    $email = $dados['email'];
    $cargo = $dados['cargo'];
    $senha = $dados['senha'];

    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Salvar no banco
    $query = "INSERT INTO clientes(nome, email, cargo, senha) VALUES('$nome', '$email', '$cargo', '$senhaCriptografada')";

    // Resultado da inserção
    $resultado = mysqli_query($conexao, $query);

    if ($resultado) {
        $resposta = [
            'mensagem' => 'Deu certo'
        ];

        echo json_encode($resposta);

    } else {
        $resposta = [
            'mensagem' => 'NÃO deu certo'
        ];
        echo json_encode($resposta);

    }

}

// Função para verificar ja existe os registros antes de salvar
function verificaRegistro($dados, $conexao)
{
    // Pegando os dados pessoais do array de dados para salvar no banco
    $nome = $dados['nome'];
    $email = $dados['email'];

    $query = "SELECT * FROM clientes WHERE nome = '$nome' AND email = '$email'";
    $resultado = mysqli_query($conexao, $query);

    if (mysqli_num_rows($resultado) > 0) {
        echo json_encode(['mensagem' => 'Esse usuário já existe no banco']);
    } else {
        guardaBanco($dados, $conexao);
    }
}


// Verificando o tipo de requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegar o corpo da requisição
    $json = file_get_contents('php://input');

    // Tranformar o Corpo JSON em um objeto PHP
    $dados = json_decode($json, true);

    // Verificar se o JSON é válido
    if ($dados === null) {
        $resposta = [
            'msgErro' => 'JSON inválido'
        ];

        echo json_encode($resposta);

    } else {
        // salvando dados no banco
        verificaRegistro($dados, $conn);
    }

} else {
    echo json_encode(['msgErro' => 'ALGO DEU ERRADO']);
}
?>