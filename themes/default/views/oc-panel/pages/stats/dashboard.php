<div class="page-header">
    <h1><?=$title?></h1>   
</div>
<h3><?=__('Charts')?></h3>

<form id="edit-profile" class="form-inline" method="post" action="">
    <fieldset>
        <?=__('From')?>
        <input  type="text" class="col-md-2" size="16"
                id="from_date" name="from_date"  value="<?=$from_date?>"  
                data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
        <?=__('To')?>
        <input  type="text" class="col-md-2" size="16"
                id="to_date" name="to_date"  value="<?=$to_date?>"  
                data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">

    <button type="submit" class="btn btn-primary"><?=__('Filter')?></button> 
    
    </fieldset>
</form>
<div class="clearfix"></div><br><hr>

<ul class="nav nav-pills" id="statsTabs">
    
    <li class="active"><a href="#visits" data-toggle="tab"><?=__('Visits')?></a></li>
    <li><a href="#sales" data-toggle="tab"><?=__('Sales')?></a></li>
    <li><a href="#products" data-toggle="tab"><?=__('Products')?></a></li>
</ul>

<div class="tab-content">
    <!-- VISITS TAB -->
    1
    <div class="tab-pane" id="visits">
        <div class="clearfix"></div><br>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th><?=__('Today')?> <?=date('d-m')?></th>
                    <th><?=__('Yesterday')?> <?=date('d-m',strtotime('-1 day'))?></th>
                    <th><?=__('Month')?> <?=date('M Y')?></th>
                    <th><?=__('Year')?> <?=date('Y')?></th>
                    <th><?=__('Total')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?=__('Visits')?></b></td>
                    <td><?=$visits_today?></td>
                    <td><?=$visits_yesterday?></td>
                    <td><?=$visits_month?></td>
                    <td><?=$visits_year?></td>
                    <td><?=$visits_total?></td>
                </tr>
            </tbody>
        </table>
            <?=Chart::column($stats_daily,array('title'=>__('Visits per day'),
                                        'height'=>400,
                                        'width'=>'100%',
                                        ))?> 

            <?=Chart::column($stats_by_month,array('title'=>__('Visits per month'),
                                        'height'=>400,
                                        'width'=>'100%'))?> 
    </div>
    <!-- SALES TAB -->
    2
    <div class="tab-pane active" id="sales">
        <div class="clearfix"></div><br>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th><?=__('Today')?> <?=date('d-m')?></th>
                    <th><?=__('Yesterday')?> <?=date('d-m',strtotime('-1 day'))?></th>
                    <th><?=__('Month')?> <?=date('M Y')?></th>
                    <th><?=__('Year')?> <?=date('Y')?></th>
                    <th><?=__('Total')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?=__('Sales')?></b></td>
                    <td>$<?=$amount_today?> (<?=$orders_today?>)</td>
                    <td>$<?=$amount_yesterday?> (<?=$orders_yesterday?>)</td>
                    <td>$<?=$amount_month?> (<?=$orders_month?>)</td>
                    <td>$<?=$amount_year?> (<?=$orders_year?>)</td>
                    <td>$<?=$amount_total?> (<?=$orders_total?>)</td>
                </tr>
            </tbody>
        </table>

        <?=Chart::column($stats_orders,array('title'=>__('Sales statistics per day'),
                                    'height'=>400,
                                    'width'=>'100%',
                                    'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>       
        <?=Chart::column($stats_orders_by_month,array('title'=>__('Sales statistics per month'),
                                    'height'=>400,
                                    'width'=>'100%',
                                    'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>          
    </div>
   
    3
    <div class="tab-pane" id="products">
        <div class="col-md-9">
            <h3><?=__('Totals products')?></h3>
            <table class="table table-bordered table-condensed sortable">
                <thead>
                    <tr>
                        <th><?=__('Product')?></th>
                        <th>$$$</th>
                        <th><?=__('Orders')?></th>
                        <th><?=__('Views')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?foreach ($products as $p):?>
                    <tr>
                        <td><a href="<?=Route::url('oc-panel', array('id'=>$p->seotitle,'controller'=>'stats','action'=>'index')) ?>">
                            <?=$p->title?></a></td>
                        <td><?=(isset($orders_product[$p->id_product]))?round($orders_product[$p->id_product]['total'],2):0?></td>
                        <td><?=(isset($orders_product[$p->id_product]))?$orders_product[$p->id_product]['count']:0?></td>
                        <td><?=(isset($visits_product[$p->id_product]))?$visits_product[$p->id_product]['count']:0?></td
                    </tr>
                    <?endforeach?>
                </tbody>
            </table>

        </div> 
        <div class="col-md-9">
        aaaa
            <?=Chart::pie($products_total,array(
                                        'height'=>600,
                                        'width'=>'100%'))?> 
        </div>
    </div>
</div>


