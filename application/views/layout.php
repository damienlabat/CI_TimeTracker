<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?= @$headtitle ?></title>
    <meta name="description" content="<?= @$headdesc ?>">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet/less" href="<?= less_url('style.less') ?>">
    <script src="<?= libs_url('less-1.3.0.min.js') ?>"></script>

    <!-- Use SimpLESS (Win/Linux/Mac) or LESS.app (Mac) to compile your .less files
    to style.css,
        ie. lessc style.less > style.css
            lessc style.less --yui-compress > style.yui.css
     and replace the 2 lines above by this one:-->

    <!--link rel="stylesheet" href="<?= less_url('style.css') ?>"-->
    <!--link rel="stylesheet" href="<?= less_url('style.yui.css') ?>"-->

     <!---->

    <script src="<?= libs_url('modernizr-2.5.3-respond-1.1.0.min.js') ?>"></script>



</head>
<body>

<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<a href="https://github.com/damienl/CI_TimeTracker"><img style="position: absolute; top: 40px; right: 0; border: 0" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>

    <?php $this->load->view('bloc/navbar'); ?>


    <div id="main" class="container">
        <div class="row">
            <div class="span12">
                <div id="content" class='clearfix'>

                    <?php $this->load->view('bloc/breadcrumb'); ?>
                    <?php $this->load->view('bloc/alerts'); ?>
                    <?=@$content?>

                </div>
                <?php $this->load->view('bloc/footer'); ?>
            </div>
        </div>
    </div> <!-- /container -->







<script>
    var BASE_URL='<?=site_url()?>';
    var mysql_time='<?=@$server_time?>';
    var loading_time=new Date();
    var username='<?=@$user['name']?>';

</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?= libs_url('jquery-1.7.2.min.js') ?>"><\/script>')</script>

<!--script src="<?= libs_url('jquery-1.7.2.min.js') ?>"></script-->

<script src="http://d3js.org/d3.v2.js"></script>

<script src="<?= libs_url('bootstrap/bootstrap.min.js') ?>"></script>
<script src="<?= libs_url('bootstrap/bootstrap-datepicker.js') ?>"></script>


<script src="<?= js_url('script.js') ?>"></script>

</body>
</html>
