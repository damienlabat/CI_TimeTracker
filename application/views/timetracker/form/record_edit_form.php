<?php echo validation_errors();
?>
<?= form_open( 'tt/' . $user['name'] . '/record_'.$record[ 'id' ].'/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="activity">activity</label>
        <input type="text" placeholder="activity" name="activity"  id="activity" value="<?= $record['activity'][ 'activity_path' ] ?>">



        <label for="tags">tags</label>
        <input type="text" placeholder="tags" name="tags" id="tags" value="<?= $record[ 'tag_path' ] ?>">

<?php if ($record['activity']['type_of_record']=='value'): ?>


        <label for="value">value</label>
        <input type="text" placeholder="value" name="value" id="value" value="<?= value($record,TRUE) ?>">


<?php endif; ?>
        <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $record[ 'description' ] ?></textarea>


        <label for="start_time">started at</label>
            <input type="text" placeholder="started at" name="start_time" id="start_time" value="<?= $record[ 'start_time' ] ?>">


<?php if ( !$record[ 'running' ] ) : ?>

        <label for="stop_at">stop at</label>
            <input type="text" placeholder="stop at" name="stop_at" id="stop_at" value="<?= $record[ 'stop_at' ] ?>">


        <label for="duration">duration</label>
            <input type="text" placeholder="duration" name="duration" id="duration" value="<?= $record[ 'duration' ] ?>">


        <label for="running">running</label>
            <input type="checkbox" name="running" id="running" value="1"<?php
if ( $record[ 'running' ] )
    echo " CHECKED";
?>>

<?php endif; ?>

        <input type="hidden" name="type_of_record"  value="<?=$record['activity']['type_of_record']?>">
        <input type="hidden" name="update_record" value="<?= $record[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
TODO! JS to manage running/stop at/duration/ping
+ local time