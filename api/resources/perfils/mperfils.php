<?
Class Perfil {

	function __construct(){
		
		$this->db = Base::db1();
		
	}

	function get($get) {
		
		$afiltros = array();
		if ($get->id <> '') array_push($afiltros, " id = '".$get->id."' ");
		if (count($afiltros) > 0){
			$filtros = ' where ' . implode(' and ', $afiltros);
		} else {
			$filtros = '';
		}

		$sql = "select * from perfils $filtros";

		$rs = $this->db->query($sql);
		$result = array();
		while ($perfil = $rs->fetchObject()){
			array_push($result, $perfil);
		}

		return $result;
	}


	function delete($id){
		$sql = "delete from perfils where id = '$id'";
		$rs = $this->db->query($sql);
		return true;
	}

	function save($app){
		
		$perfil = $app->post;

		if ($perfil->id <> ''){
			$sql = "update perfils set
				nome = ':nome'
				where id = '".$perfil->id."'";
		} else {
			$sql = "insert into perfils (nome) values (:nome)";
		}
		$rs = $this->db->prepare($sql);
		$rs->bindValue(':nome', ucfirst($perfil->nome));
		$rs->execute();
		
		if ($perfil->id == ''){
			$sql = "select * from perfils order by id desc limit 1";
			$rs = $this->db->query($sql);
			$perfil = $rs->fetchObject();
		}

		return $perfil;

	}

}
?>