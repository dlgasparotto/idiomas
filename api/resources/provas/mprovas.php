<?
Class Prova {

	function __construct(){
		
		$this->db = Base::db1();
		
	}

	function save($prova) {
		
    foreach($prova->termos as $termo){

      if ($termo->idProgresso <> ''){
        $sql = "update progresso set
          qtdTeste = qtdTeste + 1,
          acertos = acertos + $termo->result,
          sequencia = substr(CONCAT('$termo->result', sequencia), 1, 5),
          dataUltTeste = current_date()
        where id = '$termo->idProgresso'";
      } else {
        $sql = "insert into progresso(idPerfil, idTermo, qtdTeste, acertos, sequencia, dataUltTeste)
        values ('$prova->idPerfil','$termo->idTermo',1,'$termo->result','$termo->result', CURRENT_DATE())";
        
        
      }
      $rs = $this->db->query($sql);
    }


		return true;

	}


}
?>