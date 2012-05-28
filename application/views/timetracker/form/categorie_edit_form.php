<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user_name . '/categorie/' . $categorie[ 'id' ] . '/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="categorie">categorie</label>
        <input type="text" placeholder="categorie" name="categorie"  id="categorie" value="<?= $categorie[ 'title' ] ?>">

         <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $categorie[ 'description' ] ?></textarea>

         <br/>show in menus  <input type="checkbox" name="isshow" id="isshow" value="1"<?php
if ( $categorie[ 'isshow' ] )
    echo " CHECKED";
?>>

        <input type="hidden" name="update_categorie" value="<?= $categorie[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
TODO! JS to manage running/stop at/duration/ping
+ local time