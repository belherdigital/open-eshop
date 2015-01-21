<?if (Valid::url($product->url_buy)):?>
    <a target="_blank" class="btn btn-success btn-large full-w"
        href="<?=$product->url_buy?>">
<?elseif (!Auth::instance()->logged_in()):?>
    <a class="btn btn-success btn-large full-w" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
<?else:?>
    <a target="_blank" class="btn btn-success btn-large full-w"
        href="<?=Route::url('default',array('controller'=>'product','action'=>'buy','id'=>$product->id_product))?>">
<?endif?>
    <span class="fa fa-shopping-cart"></span>
    <?if ($product->final_price()>0):?>
        <?=__('Buy now')?>
    <?elseif($product->has_file()==TRUE):?>
        <?=__('Free Download')?>
    <?else:?>
        <?=__('Get it for Free')?>
    <?endif?>
</a> 