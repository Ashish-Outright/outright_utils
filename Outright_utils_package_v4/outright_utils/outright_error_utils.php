<?php

 
/**
 * start outright_error_log
 * Generate the error log file 
 * 
 * @param string|array $msg        - Error log message
 * @param string       $location   - File location
 * @param int          $type       - Error log type
 * @param string       $extra_msg  - Extra message
 * @return true|false              - Returns true on success or false on fail 
 */
 
 function outright_error_log($msg,$location,$type=3,$extra_msg=''){
	$log        = print_r($msg,1);
	$create_log = error_log('['.date("Y-m-d, g:i").']'. $extra_msg.$log."\n", $type,$location);
	return $create_log ? $create_log: false;
 }  
 /** End outright_error_log **/

/**
 * start outright_on_error_level
 * Error level on
 * 
 */

 function outright_on_error_level(){
	if(!$_REQUEST['show_error']){
		 error_reporting(E_ALL); 
		 ini_set('display_errors', 1);
	} 
}/** End outright_on_error_level**/

/** 
 * start outright_print
 * Print data 
 * 
 * @parm string|array  $data - Data to print
 **********/
function outright_print($data,$stop=false){
	echo "<pre>";
	print_r($data);
	echo "<pre>";
	if($stop){
	die;
   }
} /** End outright_print**/
	

