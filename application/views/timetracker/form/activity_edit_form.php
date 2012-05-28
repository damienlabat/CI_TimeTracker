<?= form_open( 'tt/' . $user_name, array(
     'id' => 'classicform'
) ) ?>

        <label for="activity">activity</label>
        <input type="text" placeholder="activity" name="activity"  id="activity" value="<?= $activity[ 'activity_path' ] ?>">

         <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $activity[ 'description' ] ?></textarea>

        <input type="hidden" name="update_activity" value="<?= $activity[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
TODO! JS to manage running/stop at/duration/ping
+ local time