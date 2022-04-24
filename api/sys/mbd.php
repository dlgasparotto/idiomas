<?php

Class Base {

	static function db1(){

		$db_host = "localhost";
		$db_nome = "ingles";
		$db_usuario = "root";
		$db_senha = "";

		return new PDO("mysql:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha);

	}


}