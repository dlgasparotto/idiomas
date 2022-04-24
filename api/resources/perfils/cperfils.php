<?
header('Content-Type: application/json');

// ---------------------------------------------

function get($app){
  
  $perfil = new Perfil;
  $result = $perfil->get($app->get);
  Util::resSuccess($result);

}

// ---------------------------------------------

function delete($app){
  
  $perfil = new Perfil;
  $id = $app->get->id;
  $result = $perfil->delete($id);

  Util::resSuccess(true);

}

// ---------------------------------------------

function save($app){

  $perfil = new Perfil;
  $result = $perfil->save($app);
  Util::resSuccess($result);

}

// ---------------------------------------------

?>