<?php

require_once('../conf/config.php');
require_once('../application/alphaID.lib.php');

list($usec, $sec) = explode(" ",microtime());
$str = substr($sec, 3) . substr($usec, 2, 3);

$date = date('Ym');
$date = substr($date, 2);
$str = "".$date."".$str."";

$fileKey =  alphaID($str);

if(isset($_GET['upload']))
  $upload = $_GET['upload'];

if($upload == "true") {
  if ($_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    if (($_FILES["file"]["type"] == "text/plain") && ($_FILES["file"]["size"] > 20000)) {
      // success code

  header("location: index.php?xml=".$fileKey."");


		//File Location Check And Move

    		if(file_exists($logDir)){
    			//Do Nothing
    		}else{
        		mkdir($logDir, 0777, true);
				chmod($logDir, 0777);
    		}

    		if(file_exists("".$logDir."/".date('Y-m')."/txt")){
			  //Do Nothing
			}

			else{
				mkdir("".$logDir."/".date('Y-m')."", 0777, true);
				chmod("".$logDir."/".date('Y-m')."", 0777);
			}

    		if(file_exists("".$logDir."/".date('Y-m')."/txt")){

				move_uploaded_file($_FILES["file"]["tmp_name"], "".$logDir."/".date('Y-m')."/txt/".$fileKey.".txt");

			}
			else{

				mkdir("".$logDir."/".date('Y-m')."/txt", 0777, true);
				chmod("".$logDir."/".date('Y-m')."/txt", 0777);

				move_uploaded_file($_FILES["file"]["tmp_name"], "".$logDir."/".date('Y-m')."/txt/".$fileKey.".txt");

    		}

		//End File Location Check And Move

    }
    else {
      // fail due to type or size
      echo "File Type or Size Is Not Correct <br />";
      echo $_FILES["file"]["type"];
      echo "<br />";
      echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";

    }
  }
     else {
      // fail due to upload error
      echo "Upload Failed Please Try Again.";
    }
  }

  else {
  ?> <html> <body>
  <form action="upload.php?upload=true" method="post"
  enctype="multipart/form-data"> <label for="file">Filename:</label> <input type="file" name="file" id="file" /> <br /> <input type="submit" name="submit" value="Submit" /> </form>
  </body> </html>
  <?php
  }
?>