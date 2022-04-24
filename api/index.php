<?php
header('Access-Control-Allow-Origin: *');
include 'init.php';

$app = new Aplicacao;
$reuter = new Reuter;

if ($reuter->hasRoutes()) {
	include $reuter->resourceRoutes;
}

if (!$reuter->hasController()) {
	$app->response(400,  'Controlador[c' . $app->rotasol.'] não encontrado');
}

// Middewares

if (!$reuter->setRota()) {
	$app->response(400, 'Rota não encontrada');
}

include $reuter->resourceController;

if (!function_exists($reuter->rota->funcao)) {
	$app->response(400, 'Funcao['.$reuter->rota->funcao.'] inexistente no controlador');
}

$get = clone $app->get;
foreach($reuter->get AS $var => $value){
	$get->$var = $value;
}

$prepare = new stdClass;
$prepare->get = $get;
$prepare->post = $app->post;
$prepare->body = $app->body;

call_user_func($reuter->rota->funcao, $prepare);

?>
