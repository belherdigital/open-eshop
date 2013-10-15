<?php defined('SYSPATH') or die('No direct script access.');?>

	
<div class="well">
  	<div class="hero-unit">
   
		<h2>Page Not Found</h2>
	    <p>The requested page <?php echo HTML::anchor($requested_page, $requested_page) ?> is not found.</p>
	 
	    <p>It is either not existing, moved or deleted. Make sure the URL is correct. </p>
	     
	    <p>To go back to the previous page, click the Back button.</p>
	     
	    <p><a href="<?php echo URL::site('/', TRUE) ?>">If you wanted to go to the main page instead, click here.</a></p>
	  
  	</div>
</div><!--/well--> 
