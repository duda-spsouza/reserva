<?php include('top.html'); ?>
        <?php
        if ($errors) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>
        <h2><?php print htmlentities($title) ?></h2>
        <form method="POST" action="">
            <div class='item' style='max-width: 430px;'>
                <label for="name">Nome</label><br/>
                <input required autofocus type="text" name="name" value="<?php print htmlentities($user->getName()) ?>"/>
            </div>
            <div class='item' style='max-width: 430px;'>
                <label for="username">Usuario</label><br/>
                <input required type="text" name="username" value="<?php print htmlentities($user->getUsername()) ?>"/>
            </div>
            <?php if($user->getId() != NULL){ ?>
            <div class='item' style='max-width: 430px;'>
                <label for="oldpassword">Senha antiga</label><br/>
                <input required type="password" name="oldpassword" value="<?php print htmlentities("")?>" />
            </div>
            <?php } ?> 
            <div class='item' style='max-width: 430px;'>
                <label for="password">Senha</label><br/>
                <input required type="password" name="password" value="<?php print htmlentities("")?>" />
            </div>
            <div class='item' style='max-width: 430px;'>
                <label for="passwordconf">Confirme a senha</label><br/>
                <input required type="password" name="passwordconf" value="<?php print htmlentities("")?>" />
            </div>
            <br/>
            <input type="hidden" name="form-submitted" value="1" />
            <div class='button-panel'>
                <input type="submit" value="Gravar"/>
                <input type="reset" value="Limpar"/>

                <div style='background:#b0bec5;' title='Campo sem validação'></div>
                <div style='background:#ffab91;' title='Campo Invalido'></div>
                <div style='background:#a5d6a7;' title='Campo correto'></div>
            </div>
        </form>
<?php include('bottom.html'); ?>