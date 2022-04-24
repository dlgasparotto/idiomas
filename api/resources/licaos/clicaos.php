<?

header('Content-Type: application/json');

function get($app){
  
  $licao = new Licao;
  $result = $licao->get($app->get);
  Util::resSuccess($result);

}

?>