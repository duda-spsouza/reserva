<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
        <link href='css/stylesheet.css' rel='stylesheet' type='text/css'>

        <title>Gerenciador de Reservas</title>
        <link rel='icon' type='image/png' href='images/favicon.png'>
    </head>
    <body>
        <?php
        if ($errors) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>
        <div id="content" class="logbox" style="max-width:350px;">
        <h2>Login</h2>
        <form method="POST" action="">
            <div class='item' style='max-width: 800px;'>
                <label for="username">Usuario:</label><br/>
                <input required autofocus type="text" name="username"/>
            </div>
            <div class='item' style='max-width: 800px;'>
                <label for="password">Senha:</label><br/>
                <input required type="password" name="password"/>
            </div>
            <input type="hidden" name="form-submitted" value="1" />
            <div class='button-panel'>
                <input type="submit" value="Enviar"/>

                <div style='background:#b0bec5;' title='Campo sem validação'></div>
                <div style='background:#ffab91;' title='Campo Invalido'></div>
                <div style='background:#a5d6a7;' title='Campo correto'></div>
            </div>
        </form>
        </div>
    </body>
</html>
