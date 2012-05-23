<?= form_open( 'tt/' . $user_name, array(
     'id' => 'classicform'
) ) ?>

        <label for="activity">activity</label>
        <input type="text" placeholder="activity" name="activity"  id="activity" value="<?= $record['activity'][ 'activity_path' ] ?>">
        <a class="popclick-trigger" data-content="Use an <code>@</code> to add a categorie <code>activity@categorie</code><br/>Use <code>/</code> for sub-categories <code>activity@categorie/subcategorie</code></span>" rel="popover" href="#" data-original-title="start a new activitie"><i class='icon-question-sign'></i></a>
        <br/>


        <label for="tags">tags</label>
        <input type="text" placeholder="tags" name="tags" id="tags" value="<?= $record[ 'tag_path' ] ?>">
        <a class="popclick-trigger" data-content="Separate tags by a commas <code>tag1, tag2</code> or click on Tags below<br/>TODO!" rel="popover" href="#" data-original-title="add tags"><i class='icon-question-sign'></i></a>
        <br/>


        <label for="value_name">value name</label>
        <input type="text" placeholder="value name" name="value_name" id="value_name" value="">
        =
        <label for="value">value</label>
        <input type="text" placeholder="value" name="value" id="value" value="">
        <a class="popclick-trigger" data-content="Create a new value type on select one below<br/>TODO!" rel="popover" href="#" data-original-title="add a value"><i class='icon-question-sign'></i></a>
        <br/>


        <textarea class="span4" placeholder="description..." name="description" id="description"><?= $record[ 'description' ] ?></textarea>
        <a class="popclick-trigger" data-content="TODO!" rel="popover" href="#" data-original-title="add a description"><i class='icon-question-sign'></i></a>
        <br/>

        <label for="start_time">started at</label>
            <input type="text" placeholder="started at" name="start_time" id="start_time" value="<?= $record[ 'start_time' ] ?>">
        <br/>

        <label for="stop_at">stop at</label>
            <input type="text" placeholder="stop at" name="stop_at" id="stop_at" value="<?= $record[ 'stop_at' ] ?>">
        <br/>

        <label for="duration">duration</label>
            <input type="text" placeholder="duration" name="duration" id="duration" value="<?= $record[ 'duration' ] ?>">
        <br/>

        <label for="running">running</label>
            <input type="checkbox" name="running" id="running" value="1"<?php
if ( $record[ 'running' ] )
    echo " CHECKED";
?>>

        <input type="hidden" name="localtime" value="TODO!">
        <input type="hidden" name="update_record" value="<?= $record[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary span4">Change</button>


</form>
TODO! JS to manage running/stop at/duration/ping
+ local time