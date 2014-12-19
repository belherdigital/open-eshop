<!-- ******Panel Section****** --> 
<section class="user-panel user-panel-listing section has-bg-color">
    <div class="container">
        <h2 class="title text-center"><i class="fa fa-shopping-cart"></i> <?=__('Order').' #'.$order->id_order?></h2>
    <div class="row">
        <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
            <div class="panel">
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                            <strong><?=Core::config('general.site_name')?></strong>
                            <br>
                            <?=Kohana::$base_url?>
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p>
                            <em><?=__('Date')?>: <?= Date::format($order->created, core::config('general.date_format'))?></em>
                            <br>
                            <em><?=__('Order')?> #: <?=$order->id_order?></em>
                        </p>
                    </div>
                </div><!--//row-->
                <div class="row">
                    <h3 class="text-center"><?=__('Summary')?></h3>
                    <div class="col-xs-12">
                        <table class="table table-striped table-user-panel" id="checkout-table">
                            <thead>
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th><?=__('Product')?></th>
                                    <th class="text-center"><?=__('Price')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
                                    <td class="col-md-9"><?=$order->product->title?></td>
                                    <td class="col-md-2 text-center">
                                        <?if ($order->coupon->loaded()):?>
                                            <?=i18n::format_currency($order->product->price, $order->currency)?>
                                        <?else:?>
                                            <?=i18n::format_currency($order->amount, $order->currency)?>
                                        <?endif?> 
                                    </td>
                                </tr>
                                <?if ($order->coupon->loaded()):?>
                                    <tr>
                                        <td class="col-md-1" style="text-align: center">
                                            <?=$order->id_coupon?>
                                        </td>
                                        <td class="col-md-9">
                                            <?=__('Coupon')?> '<?=$order->coupon->name?>'
                                            <?=__('valid until')?> <?=Date::format($order->coupon->valid_date)?>.
                                        </td>
                                        <td class="col-md-2 text-center text-danger">
                                            -<?=i18n::format_currency(($order->coupon->discount_amount==0)?($order->product->price - $order->amount):$order->coupon->discount_amount)?>
                                        </td>
                                    </tr>                    
                                <?endif?>       
                                <tr>
                                    <td></td>
                                    <td class="text-right"><h4><strong><?=__('Total')?>: </strong></h4></td>
                                    <td class="text-center text-danger"><h4><strong><?=i18n::format_currency($order->amount, $order->currency)?></strong></h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!--//col-*-->
                </div><!--//row-->
            </div><!--//panel-->
        </div><!--//col-*-->
    </div><!--//row-->
    <?if( ! core::get('print')):?>
        <div class="pull-right">
            <a target="_blank" class="btn btn-xs btn-success" title="<?=__('Print this')?>" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'order','id'=>$order->id_order)).URL::query(array('print'=>1))?>"><i class="glyphicon glyphicon-print"></i><?=__('Print this')?></a>
        </div>
    <?endif;?>
    </div><!--//container-->        
</section><!--//user-panel-->
