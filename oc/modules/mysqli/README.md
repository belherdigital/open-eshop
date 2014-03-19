Introduction
============

When Kohana 3 was released, I discovered the only way to connect was mysql, not mysqli.

One of the first things I did then was copy the mysql classes and update them to use a mysqli connection. Since I hardly use stored procedures (which sometimes return multiple resultsets), I didn't add any functionality to loop until there are no more result sets, although you can implement this yourself.

Installation
============
Extract to a module folder 'mysqli' under your modules directory and enable in your bootstrap.php file. Alternately, extract into MODPATH/database


Suggestions
============
Shoot me an email to azuka [at] zatechcorp.com.