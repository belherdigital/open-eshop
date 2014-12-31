<!-- ******Panel Section****** --> 
<section class="user-panel user-panel-listing section has-bg-color">
    <div class="container">
        <h2 class="title text-center"><i class="fa fa-shopping-cart"></i> <?=__('Checkout')?></h2>
        <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
                <div class="panel">
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <strong><?=Core::config('general.site_name')?></strong>
                                <br>
                                <?=Kohana::$base_url?>
                                <?if (core::config('general.company_name')!=''):?>
                                <br>
                                <em><?=core::config('general.company_name')?></em>
                                <?endif?>
                                <?if (core::config('general.vat_number')!=''):?>
                                <br>
                                <em><?=core::config('general.vat_number')?></em>
                                <?endif?>
                                <br>
                                <em><?=__('Date')?>: <?= Date::format($order->pay_date, core::config('general.date_format'))?></em>
                                <br>
                                <em><?=__('Order')?> #: <?=$order->id_order?></em>
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                                <strong><?=$user->name?></strong>
                                <br>
                                <?=$user->email?>
                                <?if (strlen($order->VAT_number)>2):?>
                                <br>
                                <em><?=__('VAT')?> <?=$order->VAT_number?></em>
                                <?endif?>
                                <br>
                                <em><?=euvat::country_name($order->country)?>, <?=$order->city?></em>
                                <br>
                                <em><?=$order->address?>, <?=$order->postal_code?></em>
                            </address>
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
                                        <th></th>
                                        <th class="text-center"><?=__('Price')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
                                        <td class="col-md-7">
                                            <?=$product->title?> 
                                        </td>
                                        <td class="col-md-2">
                                        
                                        </td>
                                        <td class="col-md-2 text-center">
                                            <? $product_price = (100*$order->amount)/(100+$order->VAT);?>
                                            <?=i18n::format_currency( $product_price, $order->currency)?>
                                        </td>
                                    </tr>
                                    <?if ($order->coupon->loaded()):?>
                                        <tr>
                                            <td class="col-md-1" style="text-align: center">
                                                <?=$order->id_coupon?>
                                            </td>
                                            <td class="col-md-7">
                                                <?=__('Coupon applied')?> '<?=$order->coupon->name?>'
                                            </td>
                                            <td class="col-md-2">
                                            </td>
                                            <td class="col-md-2 text-center text-danger">
                                            </td>
                                        </tr>  
                                    <?endif?>   

                                    <?if ($order->VAT > 0 OR (euvat::is_eu_country($order->country) 
                                                                AND core::config('general.eu_vat')==TRUE 
                                                                AND Date::mysql2unix($order->pay_date) >= strtotime(euvat::$date_start))
                                            ):?>   
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><h4><strong><?=__('Sub Total')?>: </strong></h4></td>
                                        <td class="text-center">
                                            <h4>
                                                <?=i18n::format_currency($product_price, $order->currency)?>
                                            </h4>
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            <h4><?=__('VAT')?> <?=round($order->VAT,1)?>%</h4>
                                            <small>
                                                <?=euvat::country_name($order->country)?>
                                                <?=(euvat::is_eu_country($order->country) AND strlen($order->VAT_number)>2) ?'VIES':''?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <h4><?=i18n::format_currency($order->amount-$product_price, $order->currency)?></h4>
                                        </td>
                                    </tr>            
                                    <?endif?>       
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><h2><strong><?=__('Total')?>: </strong></h2></td>
                                        <td class="text-center text-danger"><h2><strong><?=i18n::format_currency($order->amount, $order->currency)?></strong></h2></td>
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

