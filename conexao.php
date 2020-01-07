<?php
	class Conexao{
		private $servidor;
		private $base;
		private $usuario;
		private $senha;
		protected static $pdo;

		function __construct(){
			$this->servidor = "localhost";
			$this->base = "simplestore";
			$this->usuario = "root";
			$this->senha = "";
		}

		function conectar(){
			try{
				if(is_null(self::$pdo)){
					self::$pdo = new PDO("mysql:host=".$this->servidor.";dbname=".$this->base.";charset=utf8",$this->usuario,$this->senha,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
				}
				return self::$pdo;
			}
			catch(PDOException $e){	
				echo "Mensagem de erro: ".$e->getMessage();
				echo "Código de erro: ".$e->getCode();	
			}
		}
	}

	$conexao = new Conexao();
	$conexao->conectar();

?>