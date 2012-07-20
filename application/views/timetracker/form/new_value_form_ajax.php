<?= form_open( 'tt/' . $user['name'] . '/' . $current['cat'] . '/new', array(
     'id' => 'ajaxform',
     'class' => 'form-inline'
) ) ?>


    <input type="text" placeholder="value name" name="start"  id="activity" value="" autocomplete="off">    
   = <input type="text" placeholder="value" name="value" id="value" class="input-small" value="">
   <input type="hidden" name="type_of_record" id="type_of_record" value="<?=$current['cat']?>">
    <button type="submit" class="btn btn-success">Save</button>


</form>
