<?php

function outright_pre_install_check(){

	$final_file='outright_utils/final/final.php';
	if(!file_exists($final_file))
	{
		$msg ="<h1> Oopps!!! , <br/>Seems Core package was not installed. However nothing to worry , download it from <a href='https://store.outrightcrm.com/outright_utils.zip'>here</a> , then install it first , before installing this package</h1>"; 
		echo $msg;
		exit();
	}
}

function outright_do_repair(){
require_once("modules/Administration/QuickRepairAndRebuild.php");
	global $moduleList;
	$repair = new RepairAndClear();
	$repair->repairAndClearAll(array("clearAll"),$moduleList, true,false);
	$exit_on_cleanup = true;
	sugar_cleanup(false);
}

function outright_post_install_msg($plug_in){

$msg ="<h1> Thanks for using our ".$plug_in." Module , we commit best services<br/>Please feel free to reach us at sales@outrightcrm.com if any problem or help required!!</h1>";

echo $msg;
}