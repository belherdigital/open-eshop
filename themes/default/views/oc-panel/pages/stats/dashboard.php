<div class="page-header">
    <h1><?=$title?></h1>   
</div>
<h3><?=__('Charts')?></h3>

<form id="edit-profile" class="form-inline" method="post" action="">
    <fieldset>
    <div class="col-md-3 pl-0">
        <label><?=__('From')?></label>
        <input  type="text" class="col-md-2" size="16"
                id="from_date" name="from_date"  value="<?=$from_date?>"  
                data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
        </div>
        <div class="col-md-3 pl-0">
        <label><?=__('To')?></label>
        <input  type="text" class="col-md-2" size="16"
                id="to_date" name="to_date"  value="<?=$to_date?>"  
                data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
        </div>
        <div class="col-md-3 pl-0">
        <label for=""></label>
        <button type="submit" class="btn btn-primary mt25"><?=__('Filter')?></button>
        </div> 
    
    </fieldset>
</form>

<div class="clearfix"></div><br><hr>

<ul class="nav nav-pills" id="statsTabs">
    
    <li class="active"><a href="#sales" data-toggle="tab"><?=__('Sales')?></a></li>
    <li><a href="#visits" data-toggle="tab"><?=__('Visits')?></a></li>
    <li><a href="#downloads" data-toggle="tab"><?=__('Downloads')?></a></li>
    <li><a href="#licenses" data-toggle="tab"><?=__('Licenses')?></a></li>
    <li><a href="#products" data-toggle="tab"><?=__('Products')?></a></li>
    
</ul>

<div class="tab-content">
    <!-- SALES TAB -->
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
    <!-- VISITS TAB -->
    <div class="tab-pane active" id="visits">
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
    <!-- DOWNLOADS TAB -->
    <div class="tab-pane active" id="downloads">
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
                    <td><b><?=__('Downloads')?></b></td>
                    <td><?=$downloads_today?></td>
                    <td><?=$downloads_yesterday?></td>
                    <td><?=$downloads_month?></td>
                    <td><?=$downloads_year?></td>
                    <td><?=$downloads_total?></td>
                </tr>
            </tbody>
        </table>
        <?=Chart::column($stats_downloads,array('title'=>__('Downloads statistics per day'),
                            'height'=>400,
                            'width'=>'100%',
                            'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>       
        <?=Chart::column($stats_downloads_by_month,array('title'=>__('Downloads statistics per month'),
                            'height'=>400,
                            'width'=>'100%',
                            'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>
    </div>
    <!-- Licenses TAB -->
    <div class="tab-pane active" id="licenses">
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
                    <th><?=__('Active')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?=__('Licenses')?></b></td>
                    <td><?=$licenses_today?></td>
                    <td><?=$licenses_yesterday?></td>
                    <td><?=$licenses_month?></td>
                    <td><?=$licenses_year?></td>
                    <td><?=$licenses_total?></td>
                    <td><?=$licenses_active?></td>
                </tr>
            </tbody>
        </table>
        <?=Chart::column($stats_licenses,array('title'=>__('Licenses statistics per day'),
                            'height'=>400,
                            'width'=>'100%',
                            'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>       
        <?=Chart::column($stats_licenses_by_month,array('title'=>__('Licenses statistics per month'),
                            'height'=>400,
                            'width'=>'100%',
                            'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>
    </div>
    <div class="tab-pane active" id="products">
        <div class="col-md-9">
            <h3><?=__('Totals products')?></h3>
            <table class="table table-bordered table-condensed sortable">
                <thead>
                    <tr>
                        <th><?=__('Product')?></th>
                        <th>$$$</th>
                        <th><?=__('Orders')?></th>
                        <th><?=__('Views')?></th>
                        <th><?=__('Downloads')?></th>
                        <th><?=__('Licenses')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?foreach ($products as $p):?>
                    
                    <tr>
                        <td><a href="<?=Route::url('oc-panel', array('id'=>$p->seotitle,'controller'=>'stats','action'=>'index')) ?>">
                            <?=$p->title?></a></td>
                        <td><?=(isset($orders_product[$p->id_product]))?round($orders_product[$p->id_product]['total'],2):0?></td>
                        <td><?=(isset($orders_product[$p->id_product]))?$orders_product[$p->id_product]['count']:0?></td>
                        <td><?=(isset($visits_product[$p->id_product]))?$visits_product[$p->id_product]['count']:0?></td>
                        <td><?=(isset($downloads_product[$p->id_product]))?$downloads_product[$p->id_product]['count']:0?></td>
                        <td><?=(isset($licenses_product[$p->id_product]))?$licenses_product[$p->id_product]['count']:0?></td>
                    </tr>
                    <?endforeach?>
                </tbody>
            </table>

        </div> 
        <div class="col-md-9">
            <?=Chart::pie($products_total,array(
                                        'height'=>600,
                                        'width'=>'100%'))?> 
        </div>
    </div>
</div>


