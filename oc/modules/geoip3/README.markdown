# GeoIP3 #

GeoIP3 is a Kohana 3 port of Doru Moisa's 2.3.x module. 
Ported by Ryder Ross. 01/29/2010


## REQUIREMENTS ##

- Download the GeoLite City database  [Download](http://www.maxmind.com/app/geolitecity)
- If you wish to use the shared memory functions in PHP, you must compile PHP with the --enable-shmop parameter in your configure 
**CAUTION:** The Shared Memory functions were not tested when I ported Doru's module to Kohana 3. 

## INSTALL ##
1. Unpack the module in the modules folder
2. Unpack the GeoLiteCity.dat file downloaded in the prerequisites section, step 1, into the geoip3/database folder
3. Enable the module in your bootstrap file by adding the following element to the Kohana::modules array:

	`MODPATH.'geoip3',     // MAXMIND Kohana3 GeoIP Library`
  
4. (Optional) Edit the geoip3/config/geoip3.php file if you wish to customize the module
	
	
## USAGE ##

		$ip = $_SERVER['REMOTE_ADDR'];
		
		echo Geoip3::instance()->city($ip)."<br/>";
		// will return the city name  
		
		$mode = 'geo';
		echo Geoip3::instance()->coord($ip, $mode)."<br/>";
		// will return the geographical coords
		// $mode can be one of the following:
		// 'geo-dms' - will return the coords in degree/minute/second format
		// 'geo-dec' - will return the coords in a decimal format 
		// 'geo'     - will return the raw coords

		echo Geoip3::instance()->city_info($ip)."<br/>"; 
		// will return a nice formatted string consisting in the city name and 
		//geo-dms coords between brackets

		$property = 'region';
		echo Geoip3::instance()->property($property, $ip)."<br/>";
		// will retrieve a specified property associated with an ip address 
		// from the maxmind database. to get a list of possible property names,
		// see the geoiprecord class from geoip3/vendor/maxmind/geoipcity.php

		var_dump(Geoip3::instance()->record($ip));
		// returns an object with all the information in the maxmind database
		// related to an ip address, or null

## NOTES ##
- This will not work or will return null on mallformed or special ip addresses, like 127.0.0.1
- Any suggestions are welcome. Please report bugs to ryross@gmail.com 
- A special thank you to Doru Moisa for providing the initial kohana module.
- The original project can be [found here](http://dev.kohanaphp.com/projects/geoip)
- The Shared Memory functions were not tested when I ported Doru's module to Kohana 3. 