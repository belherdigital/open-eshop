<?php

include('POTCreator.php');

$obj = new POTCreator;

$obj->set_root(dirname(__FILE__) . '/example');
$obj->set_exts('php|tpl');
$obj->set_regular('/_[_|e]\([\"|\']([^\"|\']+)[\"|\']\)/i');
$obj->set_base_path('..');
$obj->set_read_subdir(true);

$potfile = 'example/language/example.pot';
$obj->write_pot($potfile);

header('Content-type:text/html; Charset=GBK');
echo "<p>POT 文件位于 - The POT file located in:<br>\n{$potfile}</p>\n";
echo "<p>然后你就可以使用 Poedit 进行翻译啦 - Then you can translate it by Poedit.</p>\n";

?>