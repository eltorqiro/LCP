<?php

require_once('../application/alphaID.lib.php');

$xml = $fileKey;
$xml = $_POST['file'];
$xml = $_POST['xml'];

if(isset($_GET['xml']))$xml = $_GET['xml'];

if($xml != ""){

	$fileKey =  alphaID($xml, true);
	$time = substr($fileKey, 4);
	$fdate = ''.str_replace($time,"",$fileKey);
	$fdate = "20".substr($fdate, 0, 2)."-".substr($fdate, 2)."";

	$txtfile = "/lotroLogs/".$fdate."/txt/".$xml.".txt";

	echo "<h2 align='center'>Your File ID: ".$xml."</h2>";

	require_once('CombatParser.class.php');
	$x = new CombatParser();
	$x->loadInterpreter('sample/interpreter.xml', true);
	$x->load($txtfile);


}
 else{
?>

<form name='xml' action='?xml=' method='post' enctype='application/x-www-form-urlencoded' onsubmit='return verifyMe();'>
<table class='table_form_1' id='table_form_1' cellspacing='0'>
	<tr>
		<td class='ftbl_row_1' ><LABEL for='xml' ACCESSKEY='none' ><b style='color:red'>*</b>Your File ID
		</td>
		<td class='ftbl_row_1a' ><input type='text' name='xml' id='xml' size='45' value=''>
		</td>
	</tr>
	<tr>
		<td colspan='2' align='right'><input type='submit' name='submit' value='Submit'>&nbsp;<input type='reset' name='reset' value='Reset'>
		</td>
	</tr>
</table>
</form>

 <br />
 OR
 <br />
<?php
 	include('upload.php');
}
?>
