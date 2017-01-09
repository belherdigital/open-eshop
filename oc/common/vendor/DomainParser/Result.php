<?php
/**
 * Novutec Domain Tools
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Novutec
 * @package    DomainParser
 * @copyright  Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * @namespace Novutec\DomainParser
 */
namespace Novutec\DomainParser;

/**
 * DomainParserResult
 *
 * @category   Novutec
 * @package    DomainParser
 * @copyright  Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Result
{

    /**
     * Fully qualified domain name
     *
     * @var string
     * @access protected
     */
    protected $fqdn;

    /**
     * IDN fully qualified domain name
     *
     * @var string
     * @access protected
     */
    protected $idnFqdn;

    /**
     * Domain name
     * 
     * @var string
     * @access protected
     */
    protected $domain;

    /**
     * Domain name IDN converted
     *
     * @var string
     * @access protected
     */
    protected $idnDomain;

    /**
     * Top-level domain name
     *
     * @var string
     * @access protected
     */
    protected $tld;

    /**
     * Top-level domain name IDN converted
     *
     * @var string
     * @access protected
     */
    protected $idnTld;

    /**
     * Group name of top-level domain name
     * 
     * @var string
     * @access protected
     */
    protected $tldGroup;

    /**
     * Is the hostname valid
     * 
     * @var boolean
     * @access protected
     */
    protected $validHostname;

    /**
     * Constructs a new object from parsed domain name by DomainParser
     * 
     * @param  string $domain
     * @param  string $idnDomain
     * @param  string $tld
     * @param  string $idnTld
     * @param  string $tldGroup
     * @param  boolean $validHostname
     * @return void
     */
    public function __construct($domain = '', $idnDomain = '', $tld = '', $idnTld = '', $tldGroup = '', 
            $validHostname = false)
    {
        if ($domain != '' && $tld != '') {
            $this->fqdn = $domain . '.' . $tld;
        }
        
        if ($idnDomain != '' && $idnTld != '') {
            $this->idnFqdn = $idnDomain . '.' . $idnTld;
        }
        
        $this->domain = $domain;
        $this->idnDomain = $idnDomain;
        $this->tld = $tld;
        $this->idnTld = $idnTld;
        $this->tldGroup = $tldGroup;
        $this->validHostname = $validHostname;
    }

    /**
	 * Writing data to properties
	 *
	 * @param  string $name
	 * @param  mixed $value
	 * @return void
	 */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
	 * Checking data
	 *
	 * @param  mixed $name
	 * @return boolean
	 */
    public function __isset($name)
    {
        return isset($this->{$name});
    }

    /**
	 * Reading data from properties
	 *
	 * @param  string $name
	 * @return void
	 */
    public function __get($name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        
        return null;
    }

    /**
     * Returns the result by format
     * 
     * @param  string $format
     * @return mixed
     */
    public function get($format)
    {
        switch ($format) {
            case 'json':
                return $this->toJson();
                break;
            case 'serialize':
                return $this->serialize();
                break;
            case 'array':
                return $this->toArray();
                break;
            case 'xml':
                return $this->toXml();
                break;
            default:
                return $this;
        }
    }

    /**
     * Convert properties to json
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert properties to array
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Serialize properties
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * Convert properties to xml by using SimpleXMLElement
     *
     * @return string
     */
    public function toXml()
    {
        $xml = new \SimpleXMLElement(
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><parser></parser>');
        
        $xml->addChild('fqdn', $this->fqdn);
        $xml->addChild('idn_fqdn', $this->idnFqdn);
        $xml->addChild('domain', $this->domain);
        $xml->addChild('idnDomain', $this->idnDomain);
        $xml->addChild('tld', $this->tld);
        $xml->addChild('idnTld', $this->idnTld);
        $xml->addChild('tldGroup', $this->tldGroup);
        $xml->addChild('validHostname', $this->validHostname);
        
        return $xml->asXML();
    }
}