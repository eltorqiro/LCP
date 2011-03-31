<?php
require_once('../application/CombatParser.class.php');

require_once('../application/CustomKey.class.php');

date_default_timezone_set('Australia/Melbourne');
CustomKey::generate();

$x = new CombatParser();
$x->loadInterpreter('../sample/interpreter.xml', true);
$x->load('../sample/combatlog.txt');

?>