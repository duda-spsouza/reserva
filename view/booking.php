<?php include('top.html'); ?>
        <h2><?php print $booking->getId(); ?></h2>
        <div>
            <span class="label">Descrição:</span>
            <?php print $booking->getDescription(); ?>
        </div>
        <div>
            <span class="label">Data inicial:</span>
            <?php print Util::SqlDateToBr($booking->getDateIni()); ?>
        </div>
        <div>
            <span class="label">Data final:</span>
            <?php print Util::SqlDateToBr($booking->getDateFim()); ?>
        </div>
<?php include('bottom.html'); ?>