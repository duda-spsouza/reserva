<?php
require_once "idao.interface.php";
class User implements IDao{

	private $id;
	private $name;
	private $username;
	private $hash;

    public function __construct($id = -1){
    	if(($id != -1)){
    		$this->load($id);
    	}
    }

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setPassword($password){
		$this->hash = Util::createHash($password);
	}

	public function setHash($hash){
		$this->hash = $hash;
	}

	public function getHash(){
		return $this->hash;
	}

	public function load($id){
		$database = new Database();
		$database->query("SELECT iduser, name, username, hash FROM user WHERE iduser = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["iduser"])){
			throw new Exception("Usuário não encontrado.");
		}

		$this->id       = $cols["iduser"];
		$this->name     = utf8_decode($cols["name"]);
		$this->hash     = $cols["hash"];
		$this->username = $cols["username"];
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE user SET name = :name, username = :username, hash = :hash WHERE iduser = :id");
			$database->bind(":id",$this->id);
			$database->bind(":name",utf8_encode($this->name));
			$database->bind(":username",$this->username);
			$database->bind(":hash",$this->hash);
			
			$database->execute();

		}else{
			$database->query("INSERT INTO user(name, username, hash)VALUES(:name,:username,:hash)");
			$database->bind(":name",utf8_encode($this->name));
			$database->bind(":username",$this->username);
			$database->bind(":hash",$this->hash);
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM user WHERE iduser = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->name);
		unset($this->username);
		unset($this->hash);
	}

	public static function isRegistered($username, $password){

		$database = new Database();
		$database->query("SELECT iduser, name, username, hash FROM user WHERE username = :username");
		$database->bind(":username",$username);

		$cols = $database->single();

		if($database->RowCount() == 0){
			throw new Exception("Usuário não cadastrado.");
		}elseif($cols["hash"] <> Util::createHash($password)){
			throw new Exception("Senha incorreta.");
		}else{
			return $cols["iduser"];
		}
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "iduser";
		}
		$database->query("SELECT iduser, name, username, hash FROM user ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$users = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$user = new User();
				$user->setId($rows[$i]['iduser']);
				$user->setName(utf8_decode($rows[$i]['name']));
				$user->setUsername($rows[$i]['username']);
				$user->setHash($rows[$i]['hash']);
				$users[$i] = $user;
			}
		}
		return $users;
	}
}
?>