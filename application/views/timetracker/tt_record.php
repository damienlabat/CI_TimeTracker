<?php if ( $record ): ?>

<h2><?= $record[ 'start_time' ] ?></h2>
<ul class='records'>
<?= record_div( $record, $user['name'] ) ?>
</ul>

<?php endif;
