<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>
<br>
<div class="alert alert-success"><?=__('Congratulations');?></div>
<div class="jumbotron well">
    <h1><?=__('Installation done');?></h1>
    <p>
        <?=__('Please now erase the folder');?> <code>/install/</code><br>
    
        <a class="btn btn-success btn-large" href="<?=core::request('SITE_URL')?>"><?=__('Go to Your Website')?></a>
        
        <a class="btn btn-warning btn-large" href="<?=core::request('SITE_URL')?>oc-panel/home/">Admin</a> 
        <?if(core::request('ADMIN_EMAIL')):?>
            <span class="help-block">user: <?=core::request('ADMIN_EMAIL')?> pass: <?=core::request('ADMIN_PWD')?></span>
        <?endif?>
        <hr>
        <a class="btn btn-primary btn-large" href="http://j.mp/thanksdonate"><?=__('Make a donation')?></a>
        <?=__('We really appreciate it')?>.
    </p>
</div>