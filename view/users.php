<?php include('top.html'); ?>
        <h2>Usuários</h2>
        <div class='item' id='grid' style='max-width: 800px;'>
        <table class="users" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style='width:50px;'><a href="?op=Users&orderby=iduser">Id</a></th>
                    <th><a href="?op=Users&orderby=name">Name</a></th>
                    <th style='width:80px;'><a href="?op=Users&orderby=username">Username</a></th>
                    <th ><a href="?op=Users&orderby=hash">Hash</a></th>
                    <th style='width:50px;'colspan=2>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user){ ?>
                <tr>
                    <td><?php print htmlentities($user->getId()); ?></td>
                    <!--<td><a href="index.php?op=Users&ac=exibir&id=<?php //print $user->getId(); ?>"><?php //print htmlentities($user->getName()); ?></a></td>-->
                    <td><?php print htmlentities($user->getName()); ?></td>
                    <td><?php print htmlentities($user->getUsername()); ?></td>
                    <td><?php print htmlentities($user->getHash()); ?></td>
                    <td style='width:25px;'><a href="index.php?op=Users&ac=excluir&id=<?php print $user->getId();?>"><img src="images/trashbin.png"></a></td>
                    <td style='width:25px;'><a href="index.php?op=Users&ac=editar&id=<?php print $user->getId();?>"><img src="images/pencil.png"></a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
<?php include('bottom.html'); ?>