<?

header('Content-Type: application/json');

function save($app){
  

  /* $p = new stdClass;
  $p->idPerfil = 1;
  $p->termos = [];

  $t = new stdClass;
  $t->idTermo = 2;
  $t->idProgresso = 1;
  $t->result = 0;

  array_push($p->termos, $t);

  $app->body = $p; */

  $prova = new Prova;
  $result = $prova->save($app->body);
  Util::resSuccess($result);

}

?>