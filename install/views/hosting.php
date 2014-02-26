<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<?if (!empty(install::$error_msg)):?>
    <br>
    <div class="alert alert-danger"><?=install::$error_msg?></div>
<?endif?>

<?if(!empty(install::$msg)):?>
    <br>
    <div class="alert alert-warning">
        <?=__("We have detected some incompatibilities, installation may not work as expected but you can try.")?> <br>
        <?=install::$msg?>
    </div>
<?endif?>

<div class="jumbotron well">
    <h2>Ups! You need a compatible Hosting</h2>
    <p class="text-danger">Your hosting seems to be not compatible. Check your settings.<p>
    <p>We have partnership with hosting companies to assure compatibility. And we include:
        <ul>
            <li>100% Compatible High Speed Hosting</li>
            <li>1 Premium Theme, of your choice worth $49.99</li>
            <li>Professional Installation and Support worth $89</li>
            <div class="clearfix"></div><br>
        <a class="btn btn-primary btn-large" href="http://open-eshop.com/hosting/">
            <i class=" icon-shopping-cart icon-white"></i> Get Hosting! Less than $5 Month</a>
    </p>
</div>