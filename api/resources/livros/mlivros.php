<?
Class Livro {

	function __construct(){
		
		global $TIPOSTERMOS;

		$this->db = Base::db1();
		$this->tiposTermos = $TIPOSTERMOS;

	}

	function get() {
		
		$sql = "select distinct livro as id from termos order by livro";
		$rs = $this->db->query($sql);
		$result = [];
		while ($livro = $rs->fetchObject()){
			array_push($result, $livro);
		}

		return $result;

	}


	function getLivroPerfil($get){

		

		$sql = "SELECT t.tipo, 
			COUNT(1) AS  qtd,
			round(AVG(
			coalesce(SUBSTR(p.sequencia, 1, 1), 0) * 50 + 
			coalesce(SUBSTR(p.sequencia, 2, 1), 0) * 35 + 
			coalesce(SUBSTR(p.sequencia, 3, 1), 0) * 15 ), 2) as apr
			FROM termos t LEFT JOIN progresso p ON t.id = p.idTermo AND p.idPerfil = '$get->idperfil'
			WHERE t.livro = '$get->idlivro' 
			GROUP BY t.tipo";

		$rs = $this->db->query($sql);
		$result = new stdClass;

		$result->id = $get->idlivro;
		$result->progresso = [];
		
		while ($tipo = $rs->fetchObject()){

			$tipo->desc = $this->tiposTermos[$tipo->tipo];
			array_push($result->progresso, $tipo);
		}
		$result->apr = Util::calcApr($result->progresso);



		$sql = "SELECT t.licao, t.tipo, 
			COUNT(1) AS  qtd,
			round(AVG(
			coalesce(SUBSTR(p.sequencia, 1, 1), 0) * 50 + 
			coalesce(SUBSTR(p.sequencia, 2, 1), 0) * 35 + 
			coalesce(SUBSTR(p.sequencia, 3, 1), 0) * 15 ), 2) as apr
			FROM termos t LEFT JOIN progresso p ON t.id = p.idTermo AND p.idPerfil = '$get->idperfil'
			WHERE t.livro = '$get->idlivro'
			GROUP BY t.licao, t.tipo
			ORDER BY t.licao, t.tipo";
		$rs = $this->db->query($sql);
		while ($item = $rs->fetchObject()){
			$item->desc = $this->tiposTermos[$item->tipo];
			$licao = $item->licao;
			if (!isSet($dicLicoes[$licao])) $dicLicoes[$licao] = [];
			array_push($dicLicoes[$licao], $item);
		}
		
		$result->licoes = [];
		foreach ($dicLicoes as $numLicao => $dados){
			$licao = new stdClass;
			$licao->licao = $numLicao;
			$licao->dados = $dados;
			$licao->apr = Util::calcApr($dados);

			array_push($result->licoes, $licao);
		}

		return $result;


	}


}
?>