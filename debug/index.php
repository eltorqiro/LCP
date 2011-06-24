<?php
/*
require_once('../application/CustomKey.class.php');
date_default_timezone_set('Australia/Melbourne');
CustomKey::generate();
*/
$start = microtime(true);

require_once('../application/CombatParser.class.php');

$x = new CombatParser();
$x->setInterpreter('../sample/interpreter.xml', true);
$x->setRuleset('../sample/rules.xml', true);
$x->load('../sample/combatlog.txt', './output.xml');

$end = microtime(true);
echo $end - $start . "\n";

?>