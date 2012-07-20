<?= form_open( 'tt/' . $user['name'] . '/' . $current['cat'] . '/new', array(
     'id' => 'ajaxform',
     'class' => 'form-inline'
) ) ?>


    <input type="text" placeholder="activity" name="start"  id="activity" value="" autocomplete="off">
    <input type="hidden" name="type_of_record" id="type_of_record" value="<?=$current['cat']?>">
    <button type="submit" class="btn btn-primary">Start</button>


</form>
