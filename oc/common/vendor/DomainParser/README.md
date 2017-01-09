Novutec Domain Parser
=====================

A domain name parser to parse and to validate a domain name.

At first it will parse a given string to split it by the hostname and top-level domain name.
This will be done with a list from Mozilla and we also added some missing second-level domain
names. Afterwards we will convert the domain name to it punycode and unicode notation. If an
error occures by doing so, e.g. characters that are not allowed. It will kill these characters
and set a flag to false. This flag is used for the validation.

Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)
Licensed under the Apache License, Version 2.0 (the "License").

Installation
------------
Installing from source: `git clone git://github.com/novutec/DomainParser.git` or [download the latest release](https://github.com/novutec/DomainParser/zipball/master)

Move the source code to your preferred project folder.

Usage
-----
* Include Parser.php
```
require_once 'DomainParser/Parser.php';
```

* Create Parser() object
```
$Parser = new Novutec\DomainParser\Parser();
```

* Call parse() method
```
$result = $Parser->parse($string);
```

* Please note, if the given string doesn't contain a domain name the default tld
.com will be added to the query. You may change this by adding a tld to the parse
method call.
```
$result = $Parser->parse($string, $yourPreferredDefaultTld);
```

* You may choose 5 different return types. the types are array, object, json, serialize and
xml. By default it is object. If you want to change that call the format method before calling
the parse method or provide to the constructer. If you are not using object and an
error occurs, then exceptions will not be trapped within the response and thrown directy.
```
$Parser->setFormat('json');
$Parser = new Novutec\DomainParser\Parser('json');
```

3rd Party Libraries
-------------------
Thanks to developers of following used libraries:
* phlyLabs: http://phlylabs.de
* mozilla: http://www.mozilla.org 

ChangeLog
---------
See ChangeLog at https://github.com/novutec/DomainParser/blob/master/CHANGELOG.md

Issues
------
Please report any issues via https://github.com/novutec/DomainParser/issues

LICENSE and COPYRIGHT
---------------------
Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.