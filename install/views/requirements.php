<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<div class="page-header">
    <a class="btn btn-primary pull-right" id="phpinfobutton" >phpinfo()</a>
    <h1><?=__("Software Requirements")?>  v.<?=install::VERSION?></h1>
    <p><?=__('In this page you can see the requirements checks we do before we install.')?></p>
    <div class="clearfix"></div>
</div>

<?foreach (install::requirements() as $name => $values):
    $color = ($values['result'])?'success':'danger';?>
    <div class="pull-left <?=$color?>" style=" width: 100px; height: 56px; text-align: center;">
        <h4><i class="glyphicon glyphicon-<?=($values['result'])?"ok":"remove"?>"></i>
        <div class="clearfix"></div> 
        <?printf ('<span class="label label-%s">%s</span>',$color,$name);?> </h4>
    </div>   
<?endforeach?>

<div class="clearfix"></div><br>

<div class="hidden" id="phpinfo">
    <?=str_replace('<table', '<table class="table table-striped table-bordered"', install::phpinfo())?>
</div>