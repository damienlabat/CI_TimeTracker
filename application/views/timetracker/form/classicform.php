    <?=form_open('timetracker')?>

        <label>activity</label>
        <input type="text" class="span4" placeholder="" name="activity" value="">
        <span class="help-block"><code>activity@categorie/subcategorie</code></span>

        <label>tags</label>
        <input type="text" class="span4" placeholder="" name="tags" value="">
        <span class="help-block">Separate tags by a commas <code>tag1, tag2</code></span>

        <label>description</label>
        <textarea class="span4" placeholder="" name="description"></textarea><br/>

        <input type="hidden" name="localtime" value="">

        <button type="submit" class="btn">Start</button>

    </form>