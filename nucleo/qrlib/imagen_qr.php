<?php
            require_once("qrlib.php");


            $param = $_GET['data']; // remember to sanitize that - it is user input!
			#echo $param;
			// we need to be sure ours script does not output anything!!!
			// otherwise it will break up PNG binary!
			
			ob_start("callback");
			$debugLog = ob_get_contents();
			ob_end_clean();
			
			QRcode::png($param); 
?>