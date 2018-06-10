<?php include('top.html'); ?>
        <h2>Salas</h2>
        <div class='item' id='grid' style='max-width: 800px;'>
        <table class="users" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style='width:50px;'><a href="?op=Rooms&orderby=idroom">Id</a></th>
                    <th><a href="?op=Rooms&orderby=label">Sala</a></th>
                    <th><a href="?op=Rooms&orderby=description">Descrição</a></th>
                    <th style='width:50px;'colspan=2>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rooms as $room){ ?>
                <tr>
                    <td><?php print htmlentities($room->getId()); ?></td>
                    <!--<td><a href="index.php?op=Rooms&ac=exibir&id=<?php //print $room->getId(); ?>"><?php //print htmlentities($room->getLabel()); ?></a></td>-->
                    <td><?php print htmlentities($room->getLabel()); ?></td>
                    <td><?php print $room->getDescription(); ?></td>
                    <td style='width:25px;'><a href="index.php?op=Rooms&ac=excluir&id=<?php print $room->getId();?>"><img src="images/trashbin.png"></a></td>
                    <td style='width:25px;'><a href="index.php?op=Rooms&ac=editar&id=<?php print $room->getId();?>"><img src="images/pencil.png"></a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
<?php include('bottom.html'); ?>