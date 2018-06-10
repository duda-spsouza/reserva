<?php include('top.html'); ?>
        <h2>Reservas</h2>
        <div class='item' id='grid' style='max-width: 800px;'>
        <table class="users" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style='width:50px;'><a href="?op=Bookings&orderby=idbooking">Id</a></th>
                    <th><a href="?op=Bookings&orderby=room_idroom">Sala</a></th>
                    <th><a href="?op=Bookings&orderby=user_iduser">Usuário</a></th>
                    <th><a href="?op=Bookings&orderby=description">Descrição</a></th>
                    <th style='width:150px;'><a href="?op=Bookings&orderby=date_ini">Data/Hora Inicio</a></th>
                    <th style='width:150px;'><a href="?op=Bookings&orderby=date_fim">Fim</a></th>
                    <th style='width:50px;'colspan=2>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($bookings as $booking){ ?>
                <tr>
                    <td><?php print htmlentities($booking->getId()); ?></td>
                    <td><?php print htmlentities($booking->getRoom()->getLabel()); ?></td>
                    <td><?php print htmlentities($booking->getUser()->getName()); ?></td>
                    <td><a href="index.php?op=Bookings&ac=exibir&id=<?php print $booking->getId(); ?>"><?php print $booking->getDescription(); ?></a></td>
                    <td><?php print htmlentities(Util::SqlDateToBr($booking->getDateIni())); ?></td>
                    <td><?php print htmlentities(Util::SqlDateToBr($booking->getDateFim())); ?></td>
                    <?php if($booking->getUser()->getId() == $_SESSION['iduser']){
                        ?>
                        <td style='width:25px;'><a href="index.php?op=Bookings&ac=excluir&id=<?php print $booking->getId();?>"><img src="images/trashbin.png"></a></td>
                        <td style='width:25px;'><a href="index.php?op=Bookings&ac=editar&id=<?php print $booking->getId();?>"><img src="images/pencil.png"></a></td>
                    <?php }else{ ?>
                        <td style='width:25px;'></td>
                        <td style='width:25px;'></td>
                    <?php } ?>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
<?php include('bottom.html'); ?>