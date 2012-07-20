<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user['name'] . '/' . $current['cat'] . '/new', array(
     'id' => 'classicform'
) ) ?>

<?php
echo validation_errors();
?>

    <div class="row-fluid">
        <label for="activity">todo</label>
        <input type="text" placeholder="todo" name="start"  id="activity" value="" autocomplete="off">
    </div>


    <!--div class="row-fluid">
        <label for="tags">tags</label>
        <input type="text" placeholder="tags" name="tags" id="tags" value="">
    </div-->

    <label for="description">description</label>
    <textarea class="" placeholder="description..." name="description" id="description"></textarea><br/>

    <input type="hidden" name="type_of_record" id="type_of_record" value="<?=$current['cat']?>">

    <button type="submit" class="btn btn-large btn-warning">start</button>


</form>
