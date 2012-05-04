<?=form_open('tt/'.$user_name.'/add', array('id' => 'classicform') )?>
<div class="row">
    <div class="span6">
            <label for="activity">activity</label>
            <input type="text" class="span5" placeholder="activity" name="activity"  id="activity" value="">
            <a class="popclick-trigger" data-content="Use an <code>@</code> to add a categorie <code>activity@categorie</code><br/>Use <code>/</code> for sub-categories <code>activity@categorie/subcategorie</code></span>" rel="popover" href="#" data-original-title="start a new activitie"><i class='icon-question-sign'></i></a>
            <div class='well span5'>
                    will create new categorie .... TODO!
                    <div class='alert alert-success'>Ok all seems ok<br/>Let's go!</div>
                    <div class='alert alert-info'>Will create new categorie: toto<br/>Let's go!</div>
                    <div class='alert alert-error'>Activty truc@toto Already running!<br/>sorry :(</div>
            </div>
    </div>

    <div class="span5">
        <label for="tags">tags</label>
        <input type="text" class="span4" placeholder="tags" name="tags" id="tags" value="">
        <a class="popclick-trigger" data-content="Separate tags by a commas <code>tag1, tag2</code> or click on Tags below<br/>TODO!" rel="popover" href="#" data-original-title="add tags"><i class='icon-question-sign'></i></a>


        <label for="value_name">value name</label>
        <input type="text" class="span2" placeholder="value name" name="value_name" id="value_name" value="">
        =
        <label for="value">value</label>
        <input type="text" class="span2" placeholder="value" name="value" id="value" value="">
        <a class="popclick-trigger" data-content="Create a new value type on select one below<br/>TODO!" rel="popover" href="#" data-original-title="add a value"><i class='icon-question-sign'></i></a>

        <label for="description">description</label>
        <textarea class="span4" placeholder="description..." name="description" id="description"></textarea>
        <a class="popclick-trigger" data-content="TODO!" rel="popover" href="#" data-original-title="add a description"><i class='icon-question-sign'></i></a>
    </div>
    <input type="hidden" name="localtime" value="">
 </div>

        <button type="submit" class="btn btn-large btn-primary span4">Start</button>


</form>
TODO! fluid row problem