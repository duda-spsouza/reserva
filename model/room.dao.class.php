<?php
require_once "idao.interface.php";
class Room implements IDao{

	private $id;
	private $label;
	private $description;

    public function __construct($id = -1){
    	if($id!=-1){
    		$this->load($id);
    	}
    }

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setLabel($label){
		$this->label = $label;
	}

	public function getLabel(){
		return $this->label;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getDescription(){
		return $this->description;
	}

	public function load($id){
		$database = new Database();
		$database->query("SELECT idroom, label, description FROM room WHERE idroom = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["idroom"])){
			throw new Exception("Sala não encontrada.");
		}

		$this->id          = $cols["idroom"];
		$this->label       = utf8_decode($cols["label"]);
		$this->description = utf8_decode($cols["description"]);
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE room SET label = :label, description = :description WHERE idroom = :id");
			$database->bind(":id",$this->id);
			$database->bind(":label",utf8_encode($this->label));
			$database->bind(":description",utf8_encode($this->description));
			
			$database->execute();
		}else{
			$database->query("INSERT INTO room(label, description)VALUES(:label,:description)");
			$database->bind(":label",utf8_encode($this->label));
			$database->bind(":description",utf8_encode($this->description));
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM room WHERE idroom = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->label);
		unset($this->description);
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "idroom";
		}
		$database->query("SELECT idroom, label, description FROM room ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$rooms = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$room = new Room();
				$room->setId($rows[$i]['idroom']);
				$room->setLabel(utf8_decode($rows[$i]['label']));
				$room->setDescription(utf8_decode($rows[$i]['description']));
				$rooms[$i] = $room;
			}
		}
		return $rooms;
	}

}
?>