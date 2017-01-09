### 2.0.2 (Jul 03, 2013)
* rewritten mozilla domain name list parser (fix issue #1)
* removed trailing spaces

### 2.0.1 (Mar 18, 2013)
* changed own version in composer.json
* changed phpdoc for adding additional tlds in Parser `catchTlds`
* fixed release dates in CHANGELOG.md
* fixed typos in CHANGELOG.md

### 2.0.0 (Mar 14, 2013)
* rewritten `load()` method to use regex
* added tldGroup to `Result`
* added Additional.php file to outsource missing tlds
* added some .FJ second-level domain names because they were missing in the Mozilla list
* added type to composer.json
* added validHostname to XML output
* changed exception handling - ConnectionException will only be thrown if there is no cache file and no internet connection
* changed link to changelog in README.md

### 1.1.7 (Mar 11, 2013)
* added composer.json
* added CHANGELOG.md
* changed description of Domain Parser in README.md

### 1.1.6 (Dec 31, 2012)
* added some .CK second-level domain names because they were missing in the Mozilla list
* changed documentation and description in README.md
* changed copyright to 2013

### 1.1.5 (Nov 30, 2012)
* added support for IDN top level domain names

### 1.1.4 (Sep 04, 2012)
* added .IL second-level domain names because they were missing in the Mozilla list

### 1.1.3 (Sep 04, 2012)
* added some .NZ second-level domain names because they were missing in the Mozilla list
* fixed another bug that reloads the cache file all the time

### 1.1.2 (Sep 04, 2012)
* added `setCachePath()` method for setting a different path for the cache file
* changed some phpDoc
* fixed `ucfirst()` in AbstractException
* fixed reloading the cache all the time bug

### 1.1.1 (Jul 07, 2012)
* added `dirname(__FILE__)` to require_once of classes
* added support for looking up only top-level domain names
* changed Exception handling, there are more different Exceptions to be thrown now
* changed format in README.md

### 1.1.0 (Jul 06, 2012)
* added `setEncoding()` method for setting the encoding of given domain name
* added `isValid()` method to check if domain name is valid
* added additional output format serialize
* added additional output format XML
* added fqdn and idn_fqdn to output
* added `filter_var()` to all public methods except `parse()`
* added more TLDs because they were missing in the Mozilla list
* changed exception handling, if you want an exception you must set the flag to true otherwise you will only get an error message trapped in the response
* changed `Result` properties to camelCase
* changed comments in some php files to me more accurate
* changed `toArray()` method to improve it
* changed description in README.md
* changed `__constructer()` method: format is the only parameter now

### 1.0.2 (Jun 29, 2012)
* fixed url of issue tracker
* changed name in README.md
* fixed repository url in README.md
* cleanup and added support for multiple output formats (array, object and JSON)

### 1.0.1 (Jun 22, 2012)
* removed debug output from Parser.php

### 1.0.0 (Jun 22, 2012)
* Initial commit
