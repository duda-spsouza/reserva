<?php include('top.html'); ?>
        <h2><?php print $room->getLabel(); ?></h2>
        <div>
            <span class="label">Id:</span>
            <?php print $room->getId();; ?>
        </div>
        <div>
            <span class="label">Descrição:</span>
            <?php print $room->getDescription(); ?>
        </div>
<?php include('bottom.html'); ?>