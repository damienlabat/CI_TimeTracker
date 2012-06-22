<br/>
<div class='clearfix'>
    <form method='get' id='date_form' class='form-inline pull-left'>

        <label for="datefrom">From </label>
        <input type="date" name="datefrom" id="datefrom" class="input-small dp_input" data-date-format="yyyy-mm-dd" data-date-weekStart="1" value="<?=$current['datefrom']?>" data-date="<?=$current['datefrom']?>">

        <label for="dateto"> to </label>
        <input type="date" name="dateto"   id="dateto"   class="input-small dp_input" data-date-format="yyyy-mm-dd" data-date-weekStart="1" value="<?=$current['dateto']?>"   data-date="<?=$current['dateto']?>">

        <input type="hidden"  value="<?=$current['tab']?>" name="tab">

        <button class="btn" type="submit">go</button> or&nbsp;

    </form>

    <div class="btn-group pull-left " >
       <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">select <b class="caret"></b></a>
        <ul class="dropdown-menu" id='datepicker_select'>
            <li><a href='#' data-fromdate='<?=$server_date?>'  data-todate='<?=$server_date?>'>today</a></li>
            <?php  $d=getWeek($server_date,0);  ?>
            <li><a href='#' data-fromdate='<?=$d[0]?>'  data-todate='<?=$d[1]?>'>this week</a></li>
            <?php  $d=getWeek($server_date,-1);  ?>
            <li><a href='#' data-fromdate='<?=$d[0]?>'  data-todate='<?=$d[1]?>'>last week</a></li>
            <?php  $d=getMonth($server_date,0);  ?>
            <li><a href='#' data-fromdate='<?=$d[0]?>'  data-todate='<?=$d[1]?>'>this month</a></li>
            <?php  $d=getMonth($server_date,-1);  ?>
            <li><a href='#' data-fromdate='<?=$d[0]?>'  data-todate='<?=$d[1]?>'>last month</a></li>
            <li><a href='#' data-fromdate='<?=$firstdata_date?>'  data-todate='<?=$server_date?>'>all</a></li>
        </ul>
    </div>
</div>
