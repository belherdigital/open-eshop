<?php
/**
 * Helper include to actually install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//there was a submit from index.php
if ($_POST AND $succeed)
{
	$install 	= TRUE;
	
///////////////////////////////////////////////////////
	//check DB connection
	$link = @mysql_connect($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASS']);
	if (!$link) 
	{
		$error_msg = __('Cannot connect to server').' '. $_POST['DB_HOST'].' '. mysql_error();
		$install = FALSE;
	}
	
	if ($link && $install) 
	{
        if ($_POST['DB_NAME'])
        {
            $dbcheck = @mysql_select_db($_POST['DB_NAME']);
            if (!$dbcheck)
            {
            	 $error_msg.= __('Database name').': ' . mysql_error();
            	 $install = FALSE;
        	}
        }
        else 
        {
    		$error_msg.= '<p>'.__('No database name was given').'. '.__('Available databases').':</p>';
    		$db_list = @mysql_query('SHOW DATABASES');
    		$error_msg.= '<pre>';
			if (!$db_list) 
			{
				$error_msg.= __('Invalid query'). ':<br>' . mysql_error();
			}
			else 
			{
				while ($row = mysql_fetch_assoc($db_list)) 
				{
					$error_msg.= $row['Database'] . '<br>';
				}
			}

    		$error_msg.= '</pre>';
            $install 	= FALSE;
        }
	}

	//save DB config/database.php
	if ($install)
	{
        $_POST['TABLE_PREFIX'] = slug($_POST['TABLE_PREFIX']);
		$search  = array('[DB_HOST]', '[DB_USER]','[DB_PASS]','[DB_NAME]','[TABLE_PREFIX]','[DB_CHARSET]');
		$replace = array($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASS'],$_POST['DB_NAME'],$_POST['TABLE_PREFIX'],$_POST['DB_CHARSET']);
		$install = replace_file(DOCROOT.'install/example.database.php',$search,$replace,APPPATH.'config/database.php');
		if (!$install)
			$error_msg = __('Problem saving '.APPPATH.'config/database.php');
	}

    
	//install DB
	if ($install)
	{
        //check if has key is posted if not generate
        $hash_key = ((cP('HASH_KEY')!='')?cP('HASH_KEY'): generate_password() );
       
        //check if DB was already installed, I use content since is the last table to be created
        if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$_POST['TABLE_PREFIX']."content'"))==1) 
            $installed = TRUE;
        else
            $installed = FALSE;

        if ($installed===FALSE)//if was installed do not launch the SQL. 
            include DOCROOT.'install/install.sql.php';
	}

///////////////////////////////////////////////////////
	//AUTH config
	if ($install)
	{
		$search  = array('[HASH_KEY]', '[COOKIE_SALT]','[QL_KEY]');
		$replace = array($hash_key,$hash_key,$hash_key);
		$install = replace_file(DOCROOT.'install/example.auth.php',$search,$replace,APPPATH.'config/auth.php');
		if (!$install)
			$error_msg = __('Problem saving '.APPPATH.'config/auth.php');
	}

///////////////////////////////////////////////////////
	//create robots.txt
	if ($install)
	{
		$search  = array('[SITE_URL]', '[SITE_FOLDER]');
		$replace = array($_POST['SITE_URL'], $suggest_folder);
		$install = replace_file(DOCROOT.'install/example.robots.txt',$search,$replace,DOCROOT.'robots.txt');
		if (!$install)
			$error_msg = __('Problem saving '.DOCROOT.'robots.txt');
	}


///////////////////////////////////////////////////////
	//create htaccess
	if ($install)
	{
		$search  = array('[SITE_FOLDER]');
		$replace = array($suggest_folder);

		$install = replace_file(DOCROOT.'install/example.htaccess',$search,$replace,DOCROOT.'.htaccess');

		if (!$install)
			$error_msg = __('Problem saving '.DOCROOT.'.htaccess');
	}


///////////////////////////////////////////////////////
	//all good!
	if ($install) 
		unlink(DOCROOT.'install/install.lock');//prevents from performing a new install
	//else @todo mysql rollback??
	
	
	
}