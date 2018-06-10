<?php

require_once 'model/user.dao.class.php';

class LoginController {
    
    public function redirect($location){
        header('Location: '.$location);
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if(!$action) {
                $this->showLogin();
            }elseif ( $action == 'logout') {
                $this->logout();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }

    public function logout(){
        unset($_SESSION['user']);
        unset($_SESSION['loggedin']);
        session_destroy();
        $this->redirect('index.php');
        return;
    }
    
    public function showLogin(){

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            try{
                $id = User::isRegistered($_POST['username'],$_POST['password']);
            }catch(Excpetion $e){
                $errors = $e->getErrors();
                include 'view/login.php';
                return;
            }

            $user = new User($id);
            $_SESSION['iduser'] = $user->getId();
            $_SESSION['loggedin'] = true;
            $this->redirect('index.php');
            return;

        }
        
        include 'view/login.php';
    }

    public function showError($title, $message) {
        include 'view/error.php';
    }
}
?>
