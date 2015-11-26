<div class="page-header">
    <div class="dropdown pull-right">
        &nbsp;
        <button class="btn btn-default dropdown-toggle" type="button" id="datesMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="fa fa-tasks"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="datesMenu">
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-30 days'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 30 days')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 month'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last month')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-3 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 3 months')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-6 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 6 months')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 year'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last year')?></a></li>
            <li><a href="?from_date=2014-11-01&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('All time')?></a></li>
        </ul>
    </div>
    <form name="date" class="form-inline pull-right" method="post" action="<?=URL::current()?>">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><?=__('From')?></div>
                <input type="text" class="form-control" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <span>-</span>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><?=__('To')?></div>
                <input type="text" class="form-control" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </form>
    <h1><?=__('Site Usage Statistics')?></h1>  
</div>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'gross_revenue'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Gross Revenue')?></span>
                    <h2 class="statcard-number">
                        <?=i18n::format_currency($gross_revenue_total)?>
                        <small class="delta-indicator <?=Num::percent_change($gross_revenue_total, $gross_revenue_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($gross_revenue_total, $gross_revenue_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($gross_revenue, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'net_revenue'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Net Revenue')?></span>
                    <h2 class="statcard-number">
                        <?=i18n::format_currency($net_revenue_total)?>
                        <small class="delta-indicator <?=Num::percent_change($net_revenue_total, $net_revenue_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($net_revenue_total, $net_revenue_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($net_revenue, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'fees'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Fees')?></span>
                    <h2 class="statcard-number">
                        <?=i18n::format_currency($fees_total)?>
                        <small class="delta-indicator <?=Num::percent_change($fees_total, $fees_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($fees_total, $fees_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($fees, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'paid_orders'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Paid Orders')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($paid_orders_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($paid_orders_total, $paid_orders_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($paid_orders_total, $paid_orders_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($paid_orders, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'unpaid_orders'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Unpaid Orders')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($unpaid_orders_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($unpaid_orders_total, $unpaid_orders_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($unpaid_orders_total, $unpaid_orders_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($unpaid_orders, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'visits'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Visits')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($visits_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($visits_total, $visits_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($visits_total, $visits_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($visits, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'downloads'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Downloads')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($downloads_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($downloads_total, $downloads_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($downloads_total, $downloads_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($downloads, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'licenses'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Licenses')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($licenses_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($licenses_total, $licenses_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($licenses_total, $licenses_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($licenses, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'tickets_opened'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Tickets Opened')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($tickets_opened_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($tickets_opened_total, $tickets_opened_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($tickets_opened_total, $tickets_opened_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($tickets_opened, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'tickets_answered'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Tickets Answered')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($tickets_answered_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($tickets_answered_total, $tickets_answered_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($tickets_answered_total, $tickets_answered_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($tickets_answered, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'tickets_closed'))?>" class="display-block">
                <div class="p-a">
                    <span class="statcard-desc"><?=__('Tickets Closed')?></span>
                    <h2 class="statcard-number">
                        <?=Num::format($tickets_closed_total, 0)?>
                        <small class="delta-indicator <?=Num::percent_change($tickets_closed_total, $tickets_closed_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($tickets_closed_total, $tickets_closed_total_past)?></small> 
                        <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                    </h2>
                    <hr class="statcard-hr">
                </div>
                <div>
                    <?=Chart::line($tickets_closed, $chart_config, $chart_colors)?>
                </div>
            </a>
        </div>
    </div>
</div>
