<?php

require_once 'model/user.dao.class.php';

class UsersController {
    
    public function redirect($location){
        header('Location: '.$location."?op=Users");
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$action || $action == 'listar') {
                $this->listUsers();
            }elseif($action == 'novo' || $action == 'editar') {
                $this->saveUser();
            }elseif ( $action == 'excluir') {

                $this->deleteUser();
            }elseif($action == 'exibir') {
                $this->showUser();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listUsers(){
        $orderBy = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $users = User::getAll($orderBy);
        include 'view/users.php';
    }

    public function saveUser(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Novo usuário';
            $user = new User();            
        }else{
            $title = 'Editar usuário';
            $user = new User($id);
        }

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $user->setName(    isset($_POST['name']    ) ? $_POST['name']     : NULL);
            $user->setUsername(isset($_POST['username']) ? $_POST['username'] : NULL);

            if($user->getId()!=NULL){
                if($user->getHash() != Util::createHash($_POST['oldpassword'])){
                    $errors[] = "A senha anterior não corresponde.";
                    include 'view/user-form.php';
                    return;
                }
            }

            if($_POST['password'] != $_POST['passwordconf']){
                $errors[] = "A confirmação não corresponde.";
                include 'view/user-form.php';
                return;
            }
            
            $user->setPassword(isset($_POST['password']) ? $_POST['password'] : NULL);

            try {
                $user->save();
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/user-form.php';
    }
    
    public function deleteUser() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $user = new User($id);
            $user->erase();
        }catch(Exception $e){
            throw new Exception('Usuário não encontrado.');
        }

        $this->redirect('index.php');
    }
    
    public function showUser() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $user = new User($id);
        }catch(Exception $e){
            throw new Exception('Usuário não encontrado.');
        }

        include 'view/user.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
