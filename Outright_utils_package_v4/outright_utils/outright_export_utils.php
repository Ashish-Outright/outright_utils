<?php

/*****************************************
 * 
 *     @description : Export data in xls format
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$filename    : filename to export                                                          
 *     @param     string --->$sql         : mysql query valuer                                                          
 *     @param     array  --->$dataHeader  : data header in array format                                                         
 *     @return    sting  : Returns string value on success 
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_export_in_xls_format($filename , $sql , $dataHeader){
		if($sql && $filename != ''){
			$data = outright_run_sql($sql);
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			foreach($dataHeader as $key => $values){
					$header .= $key."\t";
				}
			 echo rtrim($header,"\t")."\r\n";
			 foreach($data as $keyRow => $keyVal) {		
				 //~ array_walk($keyVal, __NAMESPACE__ . '\cleanDataExport');
				 $keyVal = outright_clean_variables_for_export($keyVal);
				 $rowsValue = '';	
					foreach($dataHeader as $key => $val){
						if( $val == 'ufname' ){
							if( $keyVal[$val] == '' && $keyVal['ulname'] == '' ){
									$rowsValue .= "Unassigned\t";
							}else{
									$rowsValue .= $keyVal[$val].' '.$keyVal['ulname']."\t";
							}
						}else if( $val == 'apfname' ){
								$rowsValue .= $keyVal[$val].' '.$keyVal['aplname']."\t";
						}else{
								$rowsValue .= $keyVal[$val]."\t";
						} 
					}
					echo rtrim($rowsValue,"\t")."\r\n";
			  }
		}
	exit;
}


/*****************************************
 * 
 *     @description : Cleans the export variables
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$data    : data value or string to clean                                                   
 *     @return    array|false         : Returns array value on success or false on fail 
 *     @since 1.0.0
 * 
 * ***************************************/
 
function outright_clean_variables_for_export($data){
	$replaceArr = array(  '&apos;' => "'" );
	$finalValArr = array();
	if($data){
		foreach( $data as $key => $val ){
			$finalValArr[$key] = $val;
		    foreach( $replaceArr as $rKey => $rVal ){
				$finalValArr[$key] = str_replace( $rKey , $rVal , $val );
			}
			$finalValArr[$key] = str_replace( '&quot;' , '"' , $finalValArr[$key] );
			$finalValArr[$key] = preg_replace("/\t/", "", $finalValArr[$key]);
			$finalValArr[$key] = preg_replace("/\r?\n/", "", $finalValArr[$key]);
		}
	   return $finalValArr;
	}
	return false;
}


function outright_export_in_csv_format($filename,$sql,$dataHeader){
		if($sql && $filename != ''){
			$data      = outright_run_sql($sql);
			$delimiter = ",";
			$f         = fopen('php://memory', 'w');
			foreach($dataHeader as $key => $values){
					$fields[] = $key;
			}
			fputcsv($f, $fields, $delimiter);
			foreach($data as $keyRow => $keyVal) {		
				//array_walk($keyVal, __NAMESPACE__ . '\cleanDataExport');
				$keyVal = outright_clean_variables_for_export($keyVal);
				$lineData = array();	
				foreach($dataHeader as $key => $val){
					
							$lineData[] = $keyVal[$val];
					}
				
				fputcsv($f, $lineData, $delimiter);
			}
			fseek($f, 0);
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '";');
			fpassthru($f);
		}
	 exit;
}


