<?php defined('SYSPATH') or die('No direct script access.');?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Internal server error occured</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

     <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=Theme::styles(array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css' => 'screen'),'default')?> 

  </head>

  <body>

    <div class="container">

	  <div class="hero-unit">
	  
	    <h2>Wow this seems to be an error...</h2>
	    <p>Something went wrong while we are processing your request. You can try the following:</p>
	     
	    <ul>
	        <li>Reload / refresh the page.</li>
	        <li>Go back to the previous page.</li>
	    </ul>
	     
	    <p>This incident is logged and we are already notified about this problem.
	    We will trace the cause of this problem.</p>
	     
	    <p>For the mean time, you may want to go to the main page.</p>
	     
	    <p><a href="<?php echo URL::site('/', TRUE) ?>">If you wanted to go to the main page, click here.</a></p>
		
		</div>

</div>
  </body>
</html>
