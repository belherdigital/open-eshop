<?if (core::cookie('elastic_alert')!=1  AND Auth::instance()->get_user()->is_admin()):?>
<div class="alert alert-warning fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick='setCookie("elastic_alert",1,365)'>×</button>
        <p>
            <strong class="text-success">PRO Tip:</strong> 
            Do you want your emails to reach users inbox? Do you want to trace your e-mails? 
            Try <a href="http://j.mp/elasticemailoc" class="alert-link" target="_blank">ElasticEmail!</a> Get 150K emails free per month
        </p>
        <p>
            <a class="btn btn-success" href="http://j.mp/elasticemailoc" target="_blank" onclick='setCookie("elastic_alert",1,365)' >Sign Up</a>
        </p>
    </div>
<?endif?>