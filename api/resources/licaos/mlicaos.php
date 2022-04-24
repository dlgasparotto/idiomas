<?
Class Licao {

	function __construct(){
		
		global $TIPOSTERMOS;

		$this->db = Base::db1();
		$this->tiposTermos = $TIPOSTERMOS;
		
	}

	function get($get) {
		
		$result = new stdClass;

		$sql = "SELECT t.*, 
			p.id as idProgresso, p.qtdTeste, p.acertos, p.sequencia, p.dataUltTeste
			FROM termos t LEFT JOIN progresso p ON t.id = p.idTermo AND p.idPerfil = '$get->idPerfil'
			WHERE t.livro = '$get->idLivro' AND t.licao = '$get->idLicao'
			ORDER BY t.termopt";
		//echo $sql;
		$rs = $this->db->query($sql);
		$result->termos = [];
		while ($termo = $rs->fetchObject()){
			$termo->termopt = utf8_encode($termo->termopt);
			$termo->prepopt = utf8_encode($termo->prepopt);
			$termo->termoidi = utf8_encode($termo->termoidi);
			$termo->obs = utf8_encode($termo->obs);
			array_push($result->termos, $termo);
		}

		$sql = "SELECT t.tipo, 
			COUNT(1) AS  qtd,
			round(AVG(
			coalesce(SUBSTR(p.sequencia, 1, 1), 0) * 50 + 
			coalesce(SUBSTR(p.sequencia, 2, 1), 0) * 35 + 
			coalesce(SUBSTR(p.sequencia, 3, 1), 0) * 15 ), 2) as apr
			FROM termos t LEFT JOIN progresso p ON t.id = p.idTermo AND p.idPerfil = '$get->idPerfil'
			WHERE t.livro = '$get->idLivro' AND t.licao = '$get->idLicao'
			GROUP BY t.tipo";
		$rs = $this->db->query($sql);
				//echo $sql;
		$result->progresso = [];
		while ($tipo = $rs->fetchObject()){
			$tipo->desc = $this->tiposTermos[$tipo->tipo];
			array_push($result->progresso, $tipo);
		}
		$result->apr = Util::calcApr($result->progresso);


		return $result;

	}


}
?>