<?php
require_once 'util/database.class.php';
require_once 'util/util.class.php';
date_default_timezone_set("America/Sao_Paulo");
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
	if(!isset($_GET["op"])){
		$operator = "UsersController";
	}else{
		$operator = $_GET["op"]."Controller";
	}
}else{
	$operator = "LoginController";
}


require_once 'controller/'.$operator.'.php';

$controller = new $operator();
$controller->handleRequest();

?>
