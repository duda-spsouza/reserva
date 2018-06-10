<?php

require_once 'model/room.dao.class.php';

class RoomsController {
    
    public function redirect($location){
        header('Location: '.$location."?op=Rooms");
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$action || $action == 'listar') {
                $this->listRooms();
            }elseif($action == 'novo' || $action == 'editar') {
                $this->saveRoom();
            }elseif ( $action == 'excluir') {

                $this->deleteRoom();
            }elseif($action == 'exibir') {
                $this->showRoom();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listRooms(){
        $orderBy = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $rooms = Room::getAll($orderBy);
        include 'view/rooms.php';
    }

    public function saveRoom(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Nova sala';
            $room = new Room();            
        }else{
            $title = 'Editar sala';
            $room = new Room($id);
        }

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $room->setLabel(    isset($_POST['label']    ) ? $_POST['label']     : NULL);
            $room->setDescription(isset($_POST['description']) ? $_POST['description'] : NULL);

            try {
                $room->save();
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/room-form.php';
    }
    
    public function deleteRoom() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $room = new Room($id);
            $room->erase();
        }catch(Exception $e){
            throw new Exception('Sala não encontrado.');
        }

        $this->redirect('index.php');
    }
    
    public function showRoom() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $room = new Room($id);
        }catch(Exception $e){
            throw new Exception('Sala não encontrado.');
        }

        include 'view/room.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
