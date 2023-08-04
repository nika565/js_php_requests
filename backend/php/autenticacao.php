<?php 
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true'); // Permite o envio de cookies de sessão


// Solicitando o arquivo do banco
require_once('../database/conn.php');

// Função para verificar a sessão
function verificaSessao($chave){

    if(isset($_SESSION['token'])) {

        if ($chave == $_SESSION['token']) {

            echo json_encode(['autenticado' => true]);
    
        } else {
    
            echo json_encode(['autenticado' => false]);
    
        }
    

    } else {
        echo json_encode(['autenticado' => 'Variável de sessão não existe...']);
    }

    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pegando o corpo da requisição
    $json = file_get_contents('php://input');

    // Convertendo o corpo json em objeto
    $token = json_decode($json, true);

    // Verificar se o JSON é válido
    if ($token === null) {
        $resposta = [
            'msgErro' => 'Usuário não autenticado'
        ];

        echo json_encode($resposta);

    } else {
        // salvando dados no banco
        verificaSessao($token['token']);
    }

} else {

    echo json_encode(['msgErro' => 'Algo deu errado...']);

}
?>