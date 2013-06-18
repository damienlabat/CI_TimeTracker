<?php echo validation_errors(); ?>
<?= form_open( 'tt/' . $user['name'] . '/categorie_' . $categorie[ 'id' ] . '/edit', array(
     'id' => 'classicform'
) ) ?>

        <label for="categorie">categorie</label>
        <input type="text" placeholder="categorie" name="categorie"  id="categorie" value="<?= $categorie[ 'title' ] ?>">

         <br/><textarea class="" placeholder="description..." name="description" id="description"><?= $categorie[ 'description' ] ?></textarea>

         <br/>show in menus  <input type="checkbox" name="isshown" id="isshown" value="1"<?php
if ( $categorie[ 'isshown' ] )
    echo " CHECKED";
?>>

        <input type="hidden" name="update_categorie" value="<?= $categorie[ 'id' ] ?>">

    <button type="submit" class="btn btn-large btn-primary">Change</button>


</form>
