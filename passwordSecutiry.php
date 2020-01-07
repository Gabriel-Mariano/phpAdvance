<?php
		
		include "conexao.php";

	class Usuario{
		private $usuario;
		private $senha;
		private $senhaSegura;
		private $hash;
		private $conn;
		private $stmt;

		// Método Construtor
		function __construct(){
			$this->conn = new Conexao();
		}

		// Métodos Getters e Setters
		function getUser(){
			return $this->usuario;
		}
		function setUser($user){
			$this->usuario = $user;
		}
		function setPassSecurity($passSecurity){
			
		}

		function getPass(){
			return $this->senha;
		}
		function setPass($pass){
			//Não precisa fazer a conversão aqui, pois se não você não conseguirá comparar um valor com o outro e além disto faz um processamento desnecessário.
			$this->senha = $pass;
		}

		function cadastrar(){
			//Somente na hora de gravar ele faz a conversão 
			$this->senhaSegura = password_hash($this->senha,PASSWORD_DEFAULT);

			$this->stmt = $this->conn->conectar()->prepare("INSERT INTO usuarios_homologacao(email,senha) VALUES (:usuario,:password)");

			$this->stmt->bindParam(":usuario",$this->usuario);
			
			$this->stmt->bindParam(":password",$this->senhaSegura);

			// Verifica se o email e válido
			if(filter_var($this->usuario,FILTER_VALIDATE_EMAIL)){
				$this->stmt->execute();
				echo "Cadastrado com Sucesso";
			}else{
				echo "Email Inválido";
			}	
		

		}

		function logar(){
			try{
				// Executar a query buscando somente pelo usuário
				$this->stmt = $this->conn->conectar()->prepare("SELECT * FROM usuarios_homologacao WHERE email=:usuario");
				$this->stmt->bindParam(":usuario",$this->usuario,PDO::PARAM_STR);
				//$this->stmt->bindParam(":password",$this->senhaSegura,PDO::PARAM_STR);

				//Executa a query
				$this->stmt->execute();
				//Retorna o resultado da pesquisa como um array
				$row = $this->stmt->fetch(PDO::FETCH_ASSOC);
				//Aqui conta as linhas retornadas
				$cont = $this->stmt->rowCount();
				echo "Resultado dos registros encontrados: ".$cont."</br>";

				//Como você já está salvando o valor de linhas retornadas não precisa chamar novamente a função, basta usar o valor
				if($cont > 0){
					//Verifica a senha postada com a senha que está no banco
					if(password_verify($this->senha,$row['senha'])){   
						echo " -- Logado com Sucesso -- </br>";
						session_start();
						$_SESSION['email'] = $this->usuario; 
						echo "User | ".$_SESSION['email'];
					}
				}
				else{
					echo " Verifique seu usuário e senha";
				}		

			}catch(PDOException $e){
				echo "Messagem de erro: ".$e->getMessage();
			}
		}

	}

	$usuario = new Usuario();
	$usuario->setUser("fernanda.loyola.gmail");
	$usuario->setPass("07100710");
	$usuario->cadastrar();
//	$usuario->logar();

?>