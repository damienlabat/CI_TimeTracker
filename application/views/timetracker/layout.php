<div id='commandebloc' class="span12"><div class='cadre'>
    <h2>Start a new activity</h2>
    <a href='#' class='configbtn btn-mini'><i class='icon-cog'></i></a>
    <?php $this->load->view('timetracker/form/classicform'); ?>
</div></div>

<div class="span6"><div class='cadre'>
    <h2>Running Activities</h2>
    <?php $this->load->view('timetracker/runningactivities'); ?>
</div></div>


<div class="span6"><div class='cadre'>
    <h2>TODO</h2>
    <?php $this->load->view('timetracker/todos'); ?>
</div></div>

<div class="span12"><div class='cadre'>
    <h2>Last actions</h2>
    <?php $this->load->view('timetracker/lastactions'); ?>
</div></div>