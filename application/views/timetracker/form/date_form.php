<div class='well clearfix'>
    <div class='span12'>
    <form method='get' id='date_form' class='form-inline'>
        <div class="btn-toolbar">
            <div class="btn-group">
                <label for="datefrom">From </label>
                <input type="date" name="datefrom" id="datefrom" class="span2 dp_input" data-date-format="yyyy-mm-dd" data-date-weekStart="1" value="<?=$current['datefrom']?>" data-date="<?=$current['datefrom']?>">

                <label for="dateto"> to </label>
                <input type="date" name="dateto"   id="dateto"   class="span2 dp_input" data-date-format="yyyy-mm-dd" data-date-weekStart="1" value="<?=$current['dateto']?>"   data-date="<?=$current['dateto']?>">
                <input type="hidden"  value="<?=$current['tab']?>" name="tab">
            </div>
            <div class="btn-group">
                <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">select <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href='#' onclick='alert("TODO")'>today</a></li>
                    <li><a href='#' onclick='alert("TODO")'>this week</a></li>
                    <li><a href='#' onclick='alert("TODO")'>last week</a></li>
                    <li><a href='#' onclick='alert("TODO")'>this month</a></li>
                    <li><a href='#' onclick='alert("TODO")'>last month</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <button class="btn" type="submit">go</button>
            </div>
        </div>
    </form>
    </div>
</div>



