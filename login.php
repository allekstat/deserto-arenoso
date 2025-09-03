<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);
    exit;
}
else if (empty($_POST['usuario']) || empty($_POST['senha']))
{
    http_response_code(400);
    exit;
}
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
require 'env.php';
try
{
    $dsn = 'mysql:host=' . SERVIDOR_BANCO_DE_DADOS . ';port=' . PORTA_BANCO_DE_DADOS . ';dbname=' . ESQUEMA_BANCO_DE_DADOS;
    $conexao = new PDO($dsn, USUARIO_BANCO_DE_DADOS, SENHA_BANCO_DE_DADOS);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT nome FROM usuarios WHERE login = :usuario AND senha = :senha';
    $busca = $conexao->prepare($sql);
    $busca->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $busca->bindParam(':senha', $senha, PDO::PARAM_STR);
    $busca->execute();
    if ($busca->rowCount())
    {
        http_response_code(400);
        exit;
    }
    $retorno = $busca->fetch(PDO::FETCH_ASSOC);
    session_start();
    $_SESSION['nome'] = $retorno['nome'];
}
catch (PDOException $erro)
{
    if (defined('MOSTRAR_EXCECOES') && MOSTRAR_EXCECOES === 'sim')
    {
        echo 'erro: ' . $erro->getMessage();
    }
}