<?php include('top.html'); ?>
        <h2><?php print $user->getName(); ?></h2>
        <div>
            <span class="label">Id:</span>
            <?php print $user->getId();; ?>
        </div>
        <div>
            <span class="label">Usuário:</span>
            <?php print $user->getUsername();; ?>
        </div>
        <div>
            <span class="label">Hash:</span>
            <?php print $user->getHash();; ?>
        </div>
<?php include('bottom.html'); ?>