<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<div class="page-header">
    <h1><?=__('Welcome')?> </h1>
    <p><?=__('Thanks for using Open eShop.')?> 
        <?=__('Your installation version is')?> <span class="label label-info"><?=install::VERSION?></span> 
    </p>
    
    <div class="clearfix"></div>
    <p><?=__('You need help or you have some questions')?>
        <a class="btn btn-info btn-xs" target="_blank" href="http://market.open-eshop.com/forum/"><i class="glyphicon glyphicon-wrench"></i> <?=__('Forum')?></a>
        <a class="btn btn-info btn-xs" target="_blank" href="http://market.open-eshop.com/faq/"><i class="glyphicon glyphicon-question-sign"></i> <?=__('FAQ')?></a>
        <a class="btn btn-info btn-xs" target="_blank" href="http://open-eshop.com/blog/"><i class="glyphicon glyphicon-pencil"></i> <?=__('Blog')?></a>
    </p>
</div>

<div class="col-md-4 col-sm-12 col-xs-12">
    <div class="panel panel-info">
    <div class="panel-heading"><h3>Open eShop <?=__('Latest News')?></h3>
    </div>
        <div class="panel-body">
            <ul>
                <?foreach (core::rss('http://feeds.feedburner.com/RssBlogOpenEshop')  as $item):?>
                    <li><a target="_blank" href="<?=$item->link?>"><?=$item->title?></a></li>
                    <div class="divider"></div>
                <?endforeach?>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-12 col-xs-12">
        <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fopeneshop&amp;width=350&amp;height=600&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=true&amp;appId=181472118540903" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:600px;" allowTransparency="true"></iframe>
</div>
<div class="col-md-4 col-sm-12 col-xs-12">
       <a target="_blank" class="twitter-timeline" href="https://twitter.com/openeshop" data-widget-id="420496085346299904">Tweets by @openeshop</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>

