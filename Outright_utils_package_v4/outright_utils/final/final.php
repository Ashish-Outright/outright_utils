<?php
$scan_dir = "outright_utils";
$files_to_include = scandir($scan_dir);

foreach($files_to_include as $final_file){

		if(substr($final_file,"-3","4")=="php"){
		require_once "$scan_dir/$final_file";
		}
}