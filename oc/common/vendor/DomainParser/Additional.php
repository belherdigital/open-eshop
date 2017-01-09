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
$additional = array(
        'uk' => array('co.uk', 'me.uk', 'net.uk', 'org.uk', 'sch.uk', 'ac.uk', 'gov.uk', 'nhs.uk', 
                'police.uk', 'mod.uk'), 'au' => array('csiro.au'), 
        'ke' => array('co.ke', 'or.ke', 'ne.ke', 'go.ke', 'ac.ke', 'sc.ke', 'me.ke', 'mobi.ke', 
                'info.ke'), 
        'tr' => array('com.tr', 'gen.tr', 'org.tr', 'biz.tr', 'info.tr', 'name.tr', 'net.tr', 
                'web.tr', 'edu.tr'), 
        'nz' => array('ac.nz', 'co.nz', 'geek.nz', 'gen.nz', 'maori.nz', 'net.nz', 'org.nz', 
                'school.nz'), 
        'il' => array('ac.il', 'co.il', 'org.il', 'net.il', 'k12.il', 'gov.il', 'muni.il', 'idf.il'), 
        'ck' => array('co.ck', 'edu.ck', 'gov.ck', 'net.ck', 'org.ck'), 
        'fj' => array('ac.fj', 'biz.fj', 'com.fj', 'info.fj', 'mil.fj', 'name.fj', 'net.fj', 
                'org.fj', 'pro.fj'), 
        
        // private domain names
        'centralnic' => array('ae.org', 'ar.com', 'br.com', 'cn.com', 'de.com', 'eu.com', 'gb.com', 
                'gb.net', 'gr.com', 'hu.com', 'hu.net', 'jp.net', 'jpn.com', 'kr.com', 'no.com', 
                'qc.com', 'ru.com', 'sa.com', 'se.com', 'se.net', 'uk.com', 'uk.net', 'us.com', 
                'us.org', 'uy.com', 'za.com', 'com.de'), 'de.vu' => array('de.vu'), 
        'co.cc' => array(), 'com.cc' => array('com.cc'), 'org.cc' => array('org.cc'), 
        'edu.cc' => array('edu.cc'), 'net.cc' => array('net.cc'),
    'venez' => array(
        '0rg.fr', '1s.fr', 'ass0.fr', 'be.ma', 'c0m.at', 'c4.fr', 'ch.ma', 'fr.ht', 'fr.mu',
        'gaming.cx', 'ht.cx', 'lachezvos.com', 'laschezvoscoms.com', 'no-ip.be', 'no-ip.fr',
        'qc.cx', 'sarl.tk', 'venez.fr', 'viens.la', 'vu.cx', 'x-plosif.com', 'xl.cx', 'ze.tc', 'zik.dj',
    ),
);
