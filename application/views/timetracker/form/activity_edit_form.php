<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user['name'] . '/'.$activity[ 'type_of_record' ].'/' . $activity[ 'id' ] . '/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="activity">activity</label>
        <input type="text" placeholder="activity" name="activity"  id="activity" value="<?= $activity[ 'activity_path' ] ?>" autocomplete="off">

         <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $activity[ 'description' ] ?></textarea>

        <input type="hidden" name="update_activity" value="<?= $activity[ 'id' ] ?>">
        <input type="hidden" name="type_of_record" id="type_of_record" value="<?=$activity[ 'type_of_record' ]?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
