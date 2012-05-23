<?= form_open( 'tt/' . $user_name, array(
     'id' => 'classicform'
) ) ?>

<?php
echo validation_errors();
?>

    <div class="row-fluid">
        <label for="activity">activity</label>
        <input type="text" placeholder="activity" name="start"  id="activity" value="">
        <a class="popclick-trigger" data-content="Use an <code>@</code> to add a categorie <code>activity@categorie</code><br/>Use <code>/</code> for sub-categories <code>activity@categorie/subcategorie</code></span>" rel="popover" href="#" data-original-title="start a new activitie"><i class='icon-question-sign'></i></a>
    </div>


    <div class="row-fluid">
        <label for="tags">tags</label>
        <input type="text" placeholder="tags" name="tags" id="tags" value="">
        <a class="popclick-trigger" data-content="Separate tags by a commas <code>tag1, tag2</code> or click on Tags below<br/>TODO!" rel="popover" href="#" data-original-title="add tags"><i class='icon-question-sign'></i></a>
    </div>

    <div class="row-fluid">
        <label for="value_name">value name</label>
        <input type="text" placeholder="value name" name="value_name" id="value_name" value="">
        =
        <label for="value">value</label>
        <input type="text" placeholder="value" name="value" id="value" value="">
        <a class="popclick-trigger" data-content="Create a new value type on select one below<br/>TODO!" rel="popover" href="#" data-original-title="add a value"><i class='icon-question-sign'></i></a>
    </div>

    <label for="description">description</label>
    <textarea class="span4" placeholder="description..." name="description" id="description"></textarea>
    <a class="popclick-trigger" data-content="TODO!" rel="popover" href="#" data-original-title="add a description"><i class='icon-question-sign'></i></a>

    <input type="hidden" name="localtime" value="TODO">
    <button type="submit" class="btn btn-large btn-primary span4">Start</button>


</form>
TODO! fluid row problem/local time