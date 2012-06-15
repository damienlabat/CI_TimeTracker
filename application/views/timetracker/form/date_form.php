<form method='get'>

            <label for="datefrom">Start date</label>
            <div data-date-format="dd-mm-yyyy" data-date="<?=$current['datefrom']?>" class="input-append date datepicker">
                <input type="date" class="span2" readonly="" value="<?=$current['datefrom']?>" name="datefrom">
                <button class="btn datepickertrigger" type="button"><i class="icon-th"></i></button>
            </div>

            <label for="dateto">To date</label>
            <div data-date-format="dd-mm-yyyy" data-date="<?=$current['dateto']?>" class="input-append date datepicker">
                <input type="date" class="span2" readonly="" value="<?=$current['dateto']?>" name="dateto">
                <button class="btn datepickertrigger" type="button"><i class="icon-th"></i></button>
            </div>

            <button class="btn btn-primary" type="submit">go</button>
</form>