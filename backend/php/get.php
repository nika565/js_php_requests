<?php
// Definindo qual domínio pode acessar esse arquivo
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

// Definindo quais métodos podem ser usados
header('Access-Control-Allow-Methods: GET');

// Definindo quais cabeçalhos serão permitidos na requisição
header('Access-Control-Allow-Headers: Content-Type');

// Cabeçalho informando para o navegador que será retornado um JSON
header("Content-Type: application/json");

// Buscando o arquivo do banco
require_once('../database/conn.php');

// Função para buscar os dados no banco
function buscaNoBanco($dado, $conexao) {

    //Query
    $query = "SELECT * FROM clientes WHERE nome = '$dado'";
    
    // Executando a query
    $resultado = mysqli_query($conexao, $query);

    // Em caso de sucesso da query...
    if ($resultado) {

        // Buscando o resultado
        $linha = mysqli_fetch_assoc($resultado);

        if ($linha) {

            // Caso tenha encontrado, esses dados são salvos em um objeto PHP
            $usuario = [
                "id" => $linha['id'],
                "nome" => $linha['nome'],
                "email" => $linha['email'],
                "cargo" => $linha['cargo']
            ];

            // Transformando em objeto JSON e mandando para o front-end 
            echo json_encode($usuario);

        } else {

            // Caso não encontrou nenhum registro, devolve essa mensagem de erro para o front-end
            echo json_encode(['msgErro' => 'Nenhum registro encontrado']);

        }
    } else {

        // Caso a query não dê certo
        echo json_encode(['mensagem' => 'Não deu certo']);

    }


}

// Verificação do tipo de requisição
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Pegando parâmetros da URL
    $nome = $_GET['nome'];

    buscaNoBanco($nome, $conn);

} else {
    echo json_encode(['mensagem' => 'Não deu certo']);
    exit;
}

?>