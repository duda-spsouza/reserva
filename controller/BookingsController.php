<?php

require_once 'model/booking.dao.class.php';

class BookingsController {
    
    private $bookingsService = NULL;
    
    public function redirect($location){
        header('Location: '.$location."?op=Bookings");
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$action || $action == 'listar') {
                $this->listBookings();
            }elseif($action == 'novo' || $action == 'editar') {
                $this->saveBooking();
            }elseif ( $action == 'excluir') {

                $this->deleteBooking();
            }elseif($action == 'exibir') {
                $this->showBooking();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listBookings(){
        $orderBy = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $bookings = Booking::getAll($orderBy);
        include 'view/bookings.php';
    }

    public function saveBooking(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Nova reserva';
            $booking = new Booking();            
        }else{
            $title = 'Editar reserva';
            $booking = new Booking($id);
        }
        $rooms = Room::getAll("idroom");
        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $booking->setUserId($_SESSION['iduser']);
            $booking->setRoomId(     isset($_POST['idroom']      ) ? $_POST['idroom']      : NULL);
            $booking->setDescription(isset($_POST['description'] ) ? $_POST['description'] : NULL);
            $booking->setDateIni(    isset($_POST['dateini']     ) ? $_POST['dateini'] : NULL);
            $booking->setDateFim(date('Y-m-d H:i:s', strtotime($_POST['dateini'])+60*60));

            //echo "<script>alert(\"".$booking->getDateIni()."\n".$booking->getDateFim()."\")</script>";
            
            try {
                $booking->verificaReserva();
                $booking->save();
                $this->redirect('index.php');
                return;
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        
        include 'view/booking-form.php';
    }
    
    public function deleteBooking() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $booking = new Booking($id);
            $booking->erase();
        }catch(Exception $e){
            throw new Exception('Reseva não encontrada.');
        }

        $this->redirect('index.php');
    }
    
    public function showBooking() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $booking = new Booking($id);
        }catch(Exception $e){
            throw new Exception('Reserva não encontrada.');
        }

        include 'view/booking.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
