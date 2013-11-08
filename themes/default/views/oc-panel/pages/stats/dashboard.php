<div class="page-header">
    <h1><?=__('Site Usage Statistics')?></h1>   
</div>


<div class="row">

    <div class="span9">

        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th><?=__('Today')?></th>
                    <th><?=__('Yesterday')?></th>
                    <th><?=__('Last 30 days')?></th>
                    <th><?=__('Total')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?=__('Sales')?></b></td>
                    <td>$<?=$amount_today?> (<?=$orders_today?>)</td>
                    <td>$<?=$amount_yesterday?> (<?=$orders_yesterday?>)</td>
                    <td>$<?=$amount_month?> (<?=$orders_month?>)</td>
                    <td>$<?=$amount_total?> (<?=$orders_total?>)</td>
                </tr>
                <tr>
                    <td><b><?=__('Visits')?></b></td>
                    <td><?=$visits_today?></td>
                    <td><?=$visits_yesterday?></td>
                    <td><?=$visits_month?></td>
                    <td><?=$visits_total?></td>
                </tr>
            </tbody>
        </table>

        <hr>
        <h2><?=__('Charts')?></h2>

        <form id="edit-profile" class="form-inline" method="post" action="">
            <fieldset>
                <?=__('From')?>
                <input  type="text" class="span2" size="16"
                        id="from_date" name="from_date"  value="<?=$from_date?>"  
                        data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                <?=__('To')?>
                <input  type="text" class="span2" size="16"
                        id="to_date" name="to_date"  value="<?=$to_date?>"  
                        data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">

            <button type="submit" class="btn btn-primary"><?=__('Filter')?></button> 
            
            </fieldset>
        </form>


        
    </div> <!-- /.span12 -->
    
</div> <!-- /.row -->


<?=Chart::column($stats_orders,array('title'=>__('Sales statistics'),
                                    'height'=>400,
                                    'width'=>800,
                                    'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>        

<?=Chart::column($stats_daily,array('title'=>__('Views and Ads statistics'),
                                    'height'=>400,
                                    'width'=>800,
                                    'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>          

                                                      