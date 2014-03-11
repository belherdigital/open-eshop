<?if (core::cookie('elastic_alert')!=1  AND  Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
<div class="alert alert-warning fade in">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick='setCookie("elastic_alert",1,365)'>×</button>
<p>
<span class="label label-success">PRO Tip</span> 
Do you want your emails to reach users inbox? Do you want to trace your e-mails? 
Try <a href="http://j.mp/elasticemailoc" target="_blank">ElasticEmail!</a> Get 5.000 free emails ($5 Free)
<a class="btn btn-success" href="http://j.mp/elasticemailoc" target="_blank" onclick='setCookie("elastic_alert",1,365)' >Sign Up</a>
</p>
</div>
<?endif?>