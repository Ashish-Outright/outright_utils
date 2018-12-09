<?php
/**
 * @description  : Files rebulid/files merging in a single file
 * @Author       : Outright 
 * @Date         : 
 * @param   string    ---> $dir_name          : Directory  path
 * @param   string    ---> $target_FileName   : Target file name
 * @param   string    ---> $target_FileExt    : Target file extension
 * @param   bool      ---> $merge_Order       : Files merging order(0|1) ,  0 for desc & 1 for asc order
 * @param   array     ---> $skip_extension    : Files to skip from the directory on scaning time
 * @return  array     ---> return the result in array format
 * @since 1.0.0   
 */

function outright_rebuild($dir_name,$target_FileName,$target_FileExt='',$merge_Order=false,$skip_extension=array()){
	
	$Result     = array('status' => 'error', 'message' => '');
	if(file_exists($dir_name)==TRUE){
		try{
			if($merge_Order){
			   $Files_list     = array_diff(scandir($dir_name,$merge_Order),$skip_extension );
			}else{
			   $Files_list     = array_diff(scandir($dir_name),$skip_extension );
			}
			if(count($Files_list)){
				foreach($Files_list as $Filekey=>$Filename){
					$Filename_toread   = $dir_name.'/'.$Filename;
					if(!is_file($Filename_toread)){
					  	continue ;
					}
					$Readfile             = outright_fread($Filename_toread);
					if($Readfile['status']=='error'){
						return $Readfile ;
					}
					if(!empty($Readfile['filedata']) && $Readfile['file_size'] > 0){
						if($target_FileExt=='.php'){
							$built_date      = '<?php /***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/ ?>';
							$Final_file_data.= "\n<?php /** SOURCE FILE NAME : $Filename_toread **/ ?> \n".$Readfile['filedata'] ;
						}else if($target_FileExt=='.js' || $target_FileExt=='.css'){
							$built_date      = '/***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/';
							$Final_file_data.= "\n/** SOURCE FILE NAME : $Filename_toread **/\n".$Readfile['filedata'] ;
						}
						
					}
					$rebuild_files_list.= '<span>'.$Filename.'</span><br>' ;
			     }
				 if(!empty($Final_file_data) && isset($Final_file_data)){
					    
					    $Final_file_data = $built_date.$Final_file_data ;
						$Final_file_name = $target_FileName.$target_FileExt;
						$old_fiename     = $dir_name.'/'.$Final_file_name ;
						if(file_exists($old_fiename)){
							$copy_old_file  = outright_copy($dir_name,$target_FileName,$target_FileExt);
							if($copy_old_file['status']=='error'){
						       return $Readfile ;
					        }
						}

						$build_final_file  = outright_fwrite($dir_name,$Final_file_name,$Final_file_data,"w");
						if($build_final_file['status']=='error'){
						   return $build_final_file ;
						 }
						 if($build_final_file['status']=='success'){
							 $Result['status']      =   'success' ;
							 $Result['files_list']  =   $rebuild_files_list;
						 }
				 }
				
			}else{
				$Result["message"]   = 'File '.$dir_name.' dosnt have any files to merge' ;
			}
		}catch(Exception $e){
		     $Result["message"] = 'Error : '.$e;
		}
	}else{
		$Result["message"] = 'File '.$dir_name.' does not exist !'; 
	}
	return $Result ;
}


 /**
 * @description  : Files read
 * @Author       : Outright 
 * @Date         : 
 * @param   string    ---> $FilePath   : Files path
 * @return  array     ---> $Result     : return the result in array format
 * @since 1.0.0   
 */
 
 function outright_fread($FilePath,$mode='r',$data=false){
	 $Result        = array('status' => 'error', 'message' => '');
	 if(file_exists($FilePath)==TRUE){
		 $outright_fp   = fopen($FilePath,$mode) ;
		 if($outright_fp){
			try{
			   $outright_fs    = filesize( $FilePath );
			   $outright_fr    = fread( $outright_fp, $outright_fs );
			   $outright_fc    = fclose( $outright_fp );
			   $Result["status"]       = 'success';
			   $Result["message"]      = 'File reading completed successfully';
			   $Result["file_size"]    = $outright_fs;
			   $Result["filedata"]     = $outright_fr;
			 }catch(Exception $e){
			   $Result["message"] = 'Error : '.$e;
			}
		 }else{
			 $Result["message"]  = 'Error has been occured while opening '.$Filename.' file.';
		 }
	 }else{
		$Result["message"] = 'File '.$FilePath.' does not exist !'; 
	 }
	 return $Result ;
}

/**
 * @description  : Files write
 * @Author       : Outright 
 * @Date         : 
 * @param   string    ---> $FilePath   : Files path
 * @param   string    ---> $myFile     : Files path
 * @param   string    ---> $message    : Files path
 * @return  array     ---> $Result     : return the result in array format
 * @since 1.0.0   
 */

 function outright_fwrite($FilePath,$myFile,$message,$mode='W'){
		$Result        = array('status' => 'error', 'message' => '');
		$Filename = $FilePath.'/'.$myFile ;
		if(file_exists($FilePath)==TRUE){
			$outright_fp   = fopen($Filename,$mode) ;
			if($outright_fp){
				if(is_writeable($Filename)){
					try{
						$outright_fw   = fwrite($outright_fp, $message);
                        $outright_fc   = fclose($outright_fp);
						$Result["status"]  = 'success';
						$Result["message"] = 'File write completed successfully.';
					}catch(Exception $e){
						$Result["message"] = 'Error : '.$e;
					} 
				}else{
					$Result["message"] = 'File '.$Filename.' is not writable !';
				}
			}else{
				$Result["message"]  = 'Error has been occured while opening '.$Filename.' file.';
			}
			
		}else{
			$Result["message"] = 'File '.$FilePath.' does not exist !'; 
		}
		
		return  $Result ;
	}
	
/**
 * @description  : Files copy from one folder to another folder
 * @Author       : Outright 
 * @Date         : 
 * @param   string    ---> $backup_dir_name   : Back up directory path
 * @param   string    ---> $file_name         : File name 
 * @param   string    ---> $file_ext          : File Extension
 * @return  array     ---> $Result            : return the result in array format
 * @since 1.0.0   
 */	
	
 function outright_copy($backup_dir_name='Backup', $file_name,$file_ext,$backup_sub_dir_name = 'Backup'){
	 
	 $Result         = array('status' => 'error', 'message' => '');
	 if(!empty($backup_dir_name)){
		$back_up_folder = $backup_dir_name.'/'.$backup_sub_dir_name ; 
		$copy_filename  = $backup_dir_name.'/'.$file_name.$file_ext ;
	 }else{
		$back_up_folder = 'Backup' ; 
		$copy_filename  = $file_name.$file_ext ;
	 }
	 if(file_exists($copy_filename)==TRUE){
		if(!file_exists($back_up_folder) && !is_dir($back_up_folder)) {
            mkdir($back_up_folder,0755);         
            chmod($back_up_folder,0777);      
         }
         $backup_file_name =  $back_up_folder.'/'.$file_name.date('y_m_d_h_i_s').$file_ext;
         if(copy( $copy_filename, $backup_file_name)){
			$Result["status"]  = 'success';
			$Result["message"] = 'File copy has been completed succesfully.';
		 }else{
			 $Result["message"] ="Unable to copy the file from $copy_filename to $backup_file_name.";
		 }
		 
	 }else{
		$Result["message"] = 'File '.$copy_filename.' does not exist !'; 
	 }
	 return $Result ;
 }
 
 
 /**
 * @description  : array to file convert 
 * @Author       : Outright 
 * @Date         : 
 * @param   string    ---> $array_name   : array name 
 * @param   string    ---> $array_value  : array value 
 * @param   string    ---> $file_name    : file name
 * @param   string    ---> $mode         : file operation mode
 * @param   string    ---> $header       : file headers
 * @return  array     ---> $Result       : return the result in array format
 * @since 1.0.0   
 */	
 
 function outright_array_to_file_write( $array_name, $array_value, $file_name, $mode="w", $header='' ){
	
    if(!empty($header) && ($mode != 'a' || !file_exists($the_file))){
		$the_string = $header;
	}else{
    	$the_string =   "<?php\n" .
                    '// created: ' . date('Y-m-d H:i:s') . "\n";
	}
    $the_string .=  "\$$array_name = " .
                    outright_var_export( $array_value ) .
                    ";";

    return outright_file_put_contents($file_name, $the_string, LOCK_EX);
}


function outright_var_export($array_value) {
		return var_export($array_value, true);
}

 /**
 * @param $filename  - String value of the file to create
 * @param $data      - The data to be written to the file
 * @param $flags     - int as specifed by file_put_contents parameters
 * @param $context
 * @return int       - Returns the number of bytes written to the file, false otherwise.
 */
 
function outright_file_put_contents($filename, $data, $flags=null, $context=null){
	
	if(empty($flags)) {
		return file_put_contents($filename, $data);
	} elseif(empty($context)) {
		return file_put_contents($filename, $data, $flags);
	} else{
		return file_put_contents($filename, $data, $flags, $context);
	}
}



/**
 * outright_file_get_contents
 *
 * @param $filename         - String value of the file to create
 * @param $use_include_path - Searching within the include_path (or ) boolean value indicating whether or not to search the the included_path
 * @param $context          - headers or some extra parameters 
 * @return string|boolean   - Returns a file data on success, false otherwise
 */
function outright_file_get_contents($filename, $use_include_path=false, $context=null){
	//check to see if the file exists, if not then use touch to create it.
	
	if(!is_readable($filename)){
	    return false;
	}
	if(empty($context)) {
		return file_get_contents($filename, $use_include_path);
	} else {
		return file_get_contents($filename, $use_include_path, $context);
	}
}





/**
 * outright_chmod
 * Attempts to change the permission of the specified filename to the mode value specified in the
 * default_permissions configuration; otherwise, it will use the mode value.
 *
 * @param  string    filename - Path to the file
 * @param  int $mode The integer value of the permissions mode to set the created directory to
 * @return boolean   Returns TRUE on success or FALSE on failure.
 */
 
function outright_chmod($filename, $mode=null) {
    if ( !is_int($mode) )
        $mode = (int) $mode;
	if(!is_windows()){
        if(isset($mode) && $mode > 0){
		   return @chmod($filename, $mode);
		}else{
	    	return false;
		}
	}
	return true;
}

/**
 * outright_chown
 * Attempts to change the owner of the file filename to the user specified in the
 * default_permissions configuration; otherwise, it will use the user value.
 *
 * @param filename - Path to the file
 * @param user - A user name or number
 * @return boolean - Returns TRUE on success or FALSE on failure.
 */
function outright_chown($filename, $user='') {
	if(!is_windows()){
		if(strlen($user)){
			return chown($filename, $user);
		}else{
			if(strlen($GLOBALS['sugar_config']['default_permissions']['user'])){
				$user = $GLOBALS['sugar_config']['default_permissions']['user'];
				return chown($filename, $user);
			}else{
				return false;
			}
		}
	}
	return true;
}

/**
 * outright_chgrp
 * Attempts to change the group of the file filename to the group specified in the
 * default_permissions configuration; otherwise it will use the group value.
 *
 * @param filename - Path to the file
 * @param group - A group name or number
 * @return boolean - Returns TRUE on success or FALSE on failure.
 */
function outright_chgrp($filename, $group='') {
	if(!is_windows()){
		if(!empty($group)){
			return chgrp($filename, $group);
		}else{
			if(!empty($GLOBALS['sugar_config']['default_permissions']['group'])){
				$group = $GLOBALS['sugar_config']['default_permissions']['group'];
				return chgrp($filename, $group);
			}else{
				return false;
			}
		}
	}
	return true;
}



function outright_replace_in_file($FilePath, $OldText, $NewText){
	$Result         = array('status' => 'error', 'message' => '');
	if(file_exists($FilePath)==TRUE){
		if(is_writeable($FilePath)){
			try{
				$FileContent = outright_file_get_contents($FilePath);
				$FileContent = str_replace($OldText, $NewText, $FileContent);
				if(file_put_contents($FilePath, $FileContent) > 0){
					$Result["status"]  = 'success';
					$Result["message"] = 'File data replaced successfully.';
				}
				else{
				   $Result["message"]  = 'Error while writing file.';
				}
			}catch(Exception $e){
				$Result["message"] = 'Error : '.$e;
			}
		}else{
			$Result["message"] = 'File '.$FilePath.' is not writable !';
		}
	}else{
		$Result["message"] = 'File '.$FilePath.' does not exist !';
	}
	return $Result;
}



function outright_rebuild_amith($dir_name,$outright_target_dir,$target_FileName,$target_FileExt='',$merge_Order=false,$skip_extension=array()){
	
	$Result     = array('status' => 'error', 'message' => '');
	if(file_exists($dir_name)==TRUE){
		try{
			if($merge_Order){
			   $Files_list     = array_diff(scandir($dir_name,$merge_Order),$skip_extension );
			}else{
			   $Files_list     = array_diff(scandir($dir_name),$skip_extension );
			}
			if(count($Files_list)){
				foreach($Files_list as $Filekey=>$Filename){
					$Filename_toread   = $dir_name.'/'.$Filename;					
					if(!is_file($Filename_toread)){
					  	continue ;
					}
					if($Filename =='outright_utils_version_v2_7.php'){
						continue;
					}
					$Readfile             = outright_fread($Filename_toread);
					if($Readfile['status']=='error'){
						return $Readfile ;
					}
					if(!empty($Readfile['filedata']) && $Readfile['file_size'] > 0){
						if($target_FileExt=='.php'){
							$built_date      = '<?php /***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/ ?>';
							$Final_file_data.= "\n<?php /** SOURCE FILE NAME : $Filename_toread **/ ?> \n".$Readfile['filedata'] ;
						}else if($target_FileExt=='.js' || $target_FileExt=='.css'){
							$built_date      = '/***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/';
							$Final_file_data.= "\n/** SOURCE FILE NAME : $Filename_toread **/\n".$Readfile['filedata'] ;
						}
						
					}
					$rebuild_files_list.= '<span>'.$Filename.'</span><br>' ;
			     }
				 if(!empty($Final_file_data) && isset($Final_file_data)){
					    
					    $Final_file_data = $built_date.$Final_file_data ;
						$Final_file_name = $target_FileName.$target_FileExt;
						$old_fiename     = $dir_name.'/final/'.$Final_file_name ;
						if(file_exists($old_fiename)){
							$copy_old_file  = outright_copy($outright_target_dir,$target_FileName,$target_FileExt);
							if($copy_old_file['status']=='error'){
						       return $Readfile ;
					        }
						}						
						$build_final_file  = outright_fwrite($outright_target_dir,$Final_file_name,$Final_file_data,"w");
						if($build_final_file['status']=='error'){
						   return $build_final_file ;
						 }
						 if($build_final_file['status']=='success'){
							 $Result['status']      =   'success' ;
							 $Result['files_list']  =   $rebuild_files_list;
						 }
				 } else{
				 	$Result["message"]   = 'File '.$dir_name.' dosnt have any files to merge' ;
				 }
				
			}else{
				$Result["message"]   = 'File '.$dir_name.' dosnt have any files to merge' ;
			}
		}catch(Exception $e){
		     $Result["message"] = 'Error : '.$e;
		}
	}else{
		$Result["message"] = 'File '.$dir_name.' does not exist !'; 
	}
	return $Result ;
}

function outright_rebuild_utils_file($dir_name,$outright_target_dir,$target_FileName,$target_FileExt='',$merge_Order=false,$skip_extension=array()){
	
	$Result     = array('status' => 'error', 'message' => '');
	if(file_exists($dir_name)==TRUE){
		try{
			if($merge_Order){
			   $Files_list     = array_diff(scandir($dir_name,$merge_Order),$skip_extension );
			}else{
			   $Files_list     = array_diff(scandir($dir_name),$skip_extension );
			}
			if(count($Files_list)){
				foreach($Files_list as $Filekey=>$Filename){
					$Filename_toread   = $dir_name.$Filename;					
					if(!is_file($Filename_toread)){
					  	continue ;
					}
					if($Filename =='outright_utils_version_v2_7.php'){
						continue;
					}
					$Readfile             = outright_fread_skip_php_tag($Filename_toread);
					if($Readfile['status']=='error'){
						return $Readfile ;
					}
					if(!empty($Readfile['filedata']) && $Readfile['file_size'] > 0){
						if($target_FileExt=='.php'){
							$built_date      = '/***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/';
							$Final_file_data.= "\n/** SOURCE FILE NAME : $Filename_toread **/  \n".$Readfile['filedata'] ;
						}else if($target_FileExt=='.js' || $target_FileExt=='.css'){
							$built_date      = '/***** BUILT DATE   '.print_r(date("Y-m-d-h-i-s"),1).'  ***/';
							$Final_file_data.= "\n/** SOURCE FILE NAME : $Filename_toread **/\n".$Readfile['filedata'] ;
						}
						
					}
					$rebuild_files_list.= '<span>'.$Filename.'</span><br>' ;
			     }
				 if(!empty($Final_file_data) && isset($Final_file_data)){
					    
					    $Final_file_data = $built_date.$Final_file_data ;
						$Final_file_name = $target_FileName.$target_FileExt;
						$old_fiename     = $dir_name.'/final/'.$Final_file_name ;
						if(file_exists($old_fiename)){
							$copy_old_file  = outright_copy($outright_target_dir,$target_FileName,$target_FileExt);
							if($copy_old_file['status']=='error'){
						       return $Readfile ;
					        }
						}						
						$build_final_file  = outright_fwrite($outright_target_dir,$Final_file_name,$Final_file_data,"w");
						if($build_final_file['status']=='error'){
						   return $build_final_file ;
						 }
						 if($build_final_file['status']=='success'){
							 $Result['status']      =   'success' ;
							 $Result['files_list']  =   $rebuild_files_list;
						 }
				 } else{
				 	$Result["message"]   = 'File '.$dir_name.' dosnt have any files content to merge' ;
				 }
				
			}else{
				$Result["message"]   = 'File '.$dir_name.' dosnt have any files to merge' ;
			}
		}catch(Exception $e){
		     $Result["message"] = 'Error : '.$e;
		}
	}else{
		$Result["message"] = 'File '.$dir_name.' does not exist !'; 
	}
	return $Result ;
}

function outrigt_error_log($log_file,array $message ){
	
   $date = date('[Y-m-d H:i]:');
   return file_put_contents($log_file, $date . PHP_EOL . print_r($message,1) . PHP_EOL , FILE_APPEND | LOCK_EX);
}

function outright_fread_skip_php_tag($FilePath,$mode='r',$data=false){
	$Result        = array('status' => 'error', 'message' => '');
	if(file_exists($FilePath)==TRUE){
		$outright_fp   = fopen($FilePath,$mode) ;
		if($outright_fp){
			try{
				$outright_fs    = filesize( $FilePath );
				$filecontent = file($FilePath);
				$content =  $filecontent[sizeof($filecontent)-1];		
				if (strcasecmp($content, "?>") == 1) {
					$last_str = 2;
				} else {
					$last_str = 0;
				}
				$value = array_slice($filecontent,1,sizeof($filecontent)-$last_str);
				$value = implode('', $value);
				//$outright_fr    = fread( $outright_fp, $outright_fs );
				$outright_fr    = $value;
				$outright_fc    = fclose( $outright_fp );
				$Result["status"]       = 'success';
				$Result["message"]      = 'File reading completed successfully';
				$Result["file_size"]    = $outright_fs;
				$Result["filedata"]     = $outright_fr;
			}catch(Exception $e){
				$Result["message"] = 'Error : '.$e;
			}
		}else{
			$Result["message"]  = 'Error has been occured while opening '.$Filename.' file.';
		}
	}else{
		$Result["message"] = 'File '.$FilePath.' does not exist !'; 
	}
	return $Result ;
}

//method is used to copy file from one directory to another and you can also detete the source file.
function outright_copy_module_file($dir,$outright_target_dir,$file_unlink=0){	
	$skip_extension = array('.','..');
	$files1 = array_diff(scandir($dir),$skip_extension );
	foreach ($files1 as $key => $value) {	
		$outright_view_file_dir = $dir.$value;
		if(!file_exists($outright_target_dir)){
			mkdir($outright_target_dir, 0777, true);
		}
		$outright_target_dir_file = $outright_target_dir.$value;	
		if(copy($outright_view_file_dir, $outright_target_dir_file)){
			chmod($outright_target_dir_file,0777);
			if($file_unlink){
				unlink($outright_view_file_dir);
			}
		}
		chmod($outright_target_dir,0777);
	}
	return true;	
}