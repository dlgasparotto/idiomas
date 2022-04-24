<?
header('Content-Type: application/json');

// ---------------------------------------------

function get($app){
  
  $livro = new Livro;
  $result = $livro->get();
  Util::resSuccess($result);

}

// ---------------------------------------------

function getLivroPerfil($app){
  
  $livro = new Livro;
  $result = $livro->getLivroPerfil($app->get);

  Util::resSuccess($result);

}

?>