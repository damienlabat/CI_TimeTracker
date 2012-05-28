<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user_name . '/valuetype/' . $valuetype[ 'id' ] . '/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="tag">value type</label>
        <input type="text" placeholder="tag" name="valuetype"  id="valuetype" value="<?= $valuetype[ 'title' ] ?>">

        <label for="typedata">type</label>
        <input type="text" placeholder="type of data" name="typedata"  id="typedata" value="<?= $valuetype[ 'type' ] ?>">
        TODO change to select

        <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $valuetype[ 'description' ] ?></textarea>


         <br/>show in menus  <input type="checkbox" name="isshow" id="isshow" value="1"<?php
if ( $valuetype[ 'isshow' ] )
    echo " CHECKED";
?>>

        <input type="hidden" name="update_valuetype" value="<?= $valuetype[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
