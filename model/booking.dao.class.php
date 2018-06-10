<?php
require_once "idao.interface.php";
require_once "user.dao.class.php";
require_once "room.dao.class.php";
class Booking implements IDao{

	private $id;
	private $iduser;
	private $idroom;
	private $user;
	private $room;
	private $dateini;
	private $datefim;
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

	public function setDescription($description){
		$this->description = $description;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getUser(){
		if(!isset($this->user)){
			if(isset($iduser)){
				$this->user = new User($iduser);
			}else{
				$this->user = new User();
			}
		}
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
		$this->iduser = $this->user->getId();
	}

	public function getRoom(){
		if(!isset($this->room)){
			if(isset($idroom)){
				echo 'setou';
				$this->room = new Room($idroom);
			}else{
				$this->room = new Room();
			}
		}
		return $this->room;
	}

	public function setRoom($room){
		$this->room = $room;
		$this->idroom = $this->room->getId();
	}

	public function getDateIni(){
		return $this->dateini;
	}

	public function setDateIni($dateini){
		$this->dateini = $dateini;
	}

	public function getDateFim(){
		return $this->datefim;
	}

	public function setDateFim($datefim){
		$this->datefim = $datefim;
	}


	public function load($id){
		unset($this->user);
		unset($this->room);

		$database = new Database();
		$database->query("SELECT idbooking, user_iduser, room_idroom, description, date_ini, date_fim FROM booking WHERE idbooking = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["idbooking"])){
			throw new Exception("Reserva não encontrada.");
		}

		$this->id          = $cols["idbooking"];
		$this->user        = $cols["user_iduser"];
		$this->idroom      = $cols["room_idroom"];
		$this->description = utf8_decode($cols["description"]);
		$this->dateini     = $cols["date_ini"];
		$this->datefim     = $cols["date_fim"];
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE booking SET user_iduser = :iduser, room_idroom = :idroom, description = :description, date_ini = :date_ini, date_fim = :date_fim WHERE idbooking = :id");
			$database->bind(":id",$this->id);
			$database->bind(":iduser",$this->iduser);
			$database->bind(":idroom",$this->idroom);
			$database->bind(":description",utf8_encode($this->description));
			$database->bind(":date_ini",$this->dateini);
			$database->bind(":date_fim",$this->datefim);
			
			$database->execute();
		}else{
			$database->query("INSERT INTO booking(user_iduser, room_idroom, description, date_ini, date_fim)VALUES(:iduser,:idroom,:description,:date_ini, :date_fim)");
			$database->bind(":iduser",$this->iduser);
			$database->bind(":idroom",$this->idroom);
			$database->bind(":description",utf8_encode($this->description));
			$database->bind(":date_ini",$this->dateini);
			$database->bind(":date_fim",$this->datefim);
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM booking WHERE idbooking = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->iduser);
		unset($this->idroom);
		unset($this->user);
		unset($this->room);
		unset($this->date);
	}

	public function setUserId($id){
		$this->iduser = $id;
		$this->user = new User($id);
	}

	public function setRoomId($id){
		$this->idroom = $id;
		$this->room = new Room($id);
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "idbooking";
		}
		$database->query("SELECT idbooking, description, room_idroom, user_iduser, date_ini, date_fim FROM booking ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$bookings = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$booking = new Booking();
				$booking->setId($rows[$i]['idbooking']);
				$booking->setDescription(utf8_decode($rows[$i]['description']));
				$booking->setRoomId($rows[$i]['room_idroom']);
				$booking->setUserId($rows[$i]['user_iduser']);
				$booking->setDateIni($rows[$i]['date_ini']);
				$booking->setDateFim($rows[$i]['date_fim']);
				$bookings[$i] = $booking;
			}
		}
		return $bookings;
	}

	public function verificaReserva(){
		$database = new Database();
		$database->query("SELECT * FROM booking WHERE user_iduser = :iduser AND ((date_fim >= :date_ini AND date_ini <= :date_ini) OR (date_ini <= :date_fim AND date_fim >= :date_fim))");
		//$database->query("SELECT * FROM booking WHERE user_iduser = :iduser AND ((date_fim >= :date_ini) OR (date_ini <= :date_fim))");
		$database->bind(":iduser",$this->iduser);
		$database->bind(":date_ini",$this->dateini);
		$database->bind(":date_fim",$this->datefim);
		$database->execute();
		if($database->rowCount() > 0){
			throw new Exception('Existe conflito de reservas para o usuário.');
		}

		//$database->query("SELECT * FROM booking WHERE room_idroom = :idroom AND ((date_fim >= :date_ini) OR (date_ini <= :date_fim))");
		$database->query("SELECT * FROM booking WHERE room_idroom = :idroom AND ((date_fim >= :date_ini AND date_ini <= :date_ini) OR (date_ini <= :date_fim AND date_fim >= :date_fim))");
		$database->bind(":idroom",$this->idroom);
		$database->bind(":date_ini",$this->dateini);
		$database->bind(":date_fim",$this->datefim);
		$database->execute();
		if($database->rowCount() > 0){
			throw new Exception('Existe conflito de reservas para a sala selecionada.');
		}


	}
}
?>