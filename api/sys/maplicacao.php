<?
Class Aplicacao {

	function __construct() {
		
		$this->hide400 = false;
		$this->verbo 	= strtoupper($_SERVER["REQUEST_METHOD"]);
		$this->prefixo = str_replace("index.php", "", $_SERVER['PHP_SELF']); 

		$uriori	= str_replace($this->prefixo, '', $_SERVER["REQUEST_URI"]);
		$uri = explode('?', $uriori)[0];
		$uria  	= explode('/', $uri) ;
		
		$this->rotasol = $uria[0];
		$this->rotasolpars = array_slice($uria, 1);

		$this->get 		= (object) $_GET;
		$this->post 	= (object) $_POST;
		$bodyreq = file_get_contents('php://input');
		$this->body = json_decode($bodyreq);

	}


	


	function response($status = 200, $info = ''){

		$ret = new stdClass;
		$bloqInfo = false;
		$bloqInfo = ($status == 400 and $this->hide400);
		if ($info <> '' and !$bloqInfo) $ret->message = $info;
		
		http_response_code($status);
		header('Content-Type: application/json');
		die(json_encode($ret));

	}

	

	

}


//*********************************************************************************** */




?>