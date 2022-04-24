<?

$TIPOSTERMOS = array("V"=>"Verbos", "F"=>"Frases", "S"=>"Substantivos", "E"=>"Expressões");

Class Util {

  // -------------------------
  // Padrao
  
  static function resSuccess($data){
    echo json_encode($data);
  }
	
  static function resInvalidReq($message){
    $ret = new stdClass;
    $ret->message = $message;
    http_response_code(400);
    echo json_encode($ret);
  }

	static function trataApo($string){
		return str_replace("'","''",$string);
	}

	static function fnum($txt){
		return preg_replace('/\D/', '', $txt);
	}

  // App - Colocar a partir daqui as funções do app

  static function calcApr($listaTipos){

    $qtd = 0;
    $soma = 0;
    foreach($listaTipos as $tipo){
      $qtd += $tipo->qtd;
      $soma += $tipo->qtd * $tipo->apr;
    }
    if ($qtd == 0) {
      return 0;
    } else {

      return round(($soma / $qtd), 2);
    }

  }

}