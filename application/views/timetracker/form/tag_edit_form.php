<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user['name'] . '/tag/' . $tag[ 'id' ] . '/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="tag">tag</label>
        <input type="text" placeholder="tag" name="tag"  id="tag" value="<?= $tag[ 'tag' ] ?>">


         <br/>show in menus  <input type="checkbox" name="isshow" id="isshow" value="1"<?php
if ( $tag[ 'isshow' ] )
    echo " CHECKED";
?>>

        <input type="hidden" name="update_tag" value="<?= $tag[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
