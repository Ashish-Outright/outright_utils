<?php
/**
 * DB Manager : This script manages the database  operations 
 * Author     : Outright
 * Date       : 
 */
 
if(!class_exists('outrigh_DB')){
	
	Class outrigh_DB {
		
		function __construct(){
			global $sugar_config;
			$this->logfile = isset($sugar_config['log_file']) && !empty($sugar_config['log_file'])? $sugar_config['log_file'] : 'outrightcrm.log';
		}
	
		/**
	     * mysqlconnection 
	     * @param  array $configOptions  : DB connection parameters 
	     * @param  bool  $dieOnError     : True if we want to call die if the query returns errors
	     * @return bool                  : Return true on success or false on fail
	     */
		public function outright_mysql_connect(array $configOptions = null, $dieOnError = false){
			
			global $sugar_config;

			if(is_null($configOptions)){
				$configOptions = $sugar_config['dbconfig'];
			}
	
			$this->dbconn = mysql_connect($configOptions['db_host_name'],$configOptions['db_user_name'],$configOptions['db_password']);
			if(empty($this->dbconn)) {
				
				  $connect_error[]       = "Could not connect to the server. Please refer to outrightcrm.log for details.";
				  $err_msg[]             = "Could not connect to server ".$configOptions['db_host_name']." as ".$configOptions['db_user_name'].":".mysql_error() ;
				  if(!$GLOBALS['log']->fatal($err_msg)){
					  outrigt_error_log($this->logfile,$err_msg);
				  }
				  if($dieOnError) {
					 if(isset($GLOBALS['app_strings']['ERR_NO_DB'])) {
						outright_die($GLOBALS['app_strings']['ERR_NO_DB']);
					 } else {
						outright_die("Could not connect to the server. Please refer to outrightcrm.log for details.");
					 }
				   } else {
					 $_SESSION['outright_errors'] = $connect_error ;
					 return false;
				   }
				
			}
			if(!empty($configOptions['db_name']) && !@mysql_select_db($configOptions['db_name'])) {
				$connect_error[]  = "Unable to select database {$configOptions['db_name']} Please refer to outrightcrm.log for details.";
				$err_msg[]=  "Unable to select the database {$configOptions['db_name']}: " . mysql_error($this->dbconn);
				if($dieOnError) {
					outright_die($GLOBALS['app_strings']['ERR_NO_DB']);
				} 
				if(!$GLOBALS['log']->fatal($err_msg)){
				  outrigt_error_log($this->logfile,$err_msg);
				 }
				return false;
				
			}
			$connect_succ[] =  "Connection has been established :".$this->dbconn ;
			if(!$GLOBALS['log']->fatal($connect_succ)){
				 outrigt_error_log($this->logfile,$connect_succ);
			}
			return true;
		  
	    }
	    
	    /**
	     * mysql query execution 
	     * @param  strring $sql         : SQL Statement to execute 
	     * @param  bool    $dieOnError  : True if we want to call die if the query returns errors 
	     */
	    public function outright_mysql_query($sql= false, $dieOnError = false){
			
			$this->query_exec_time = microtime(true);
			if(!mysql_query($sql, $this->dbconn)){
				$connect_error[]  = "Could not execute the query. Please refer to outrightcrm.log for details.";
				$err_msg[]        = "Unable to execute the query ".$sql.":".mysql_error($this->dbconn);
				if(!$GLOBALS['log']->fatal($err_msg)){
					  outrigt_error_log($this->logfile,$err_msg);
				}
				
				if($dieOnError) {
					if(isset($GLOBALS['app_strings']['ERR_NO_DB'])) {
						outright_die($GLOBALS['app_strings']['ERR_NO_DB']);
					} else {
					  outright_die("Unable to execute the query. Please refer to outrightcrm.log for details.");
					}
				} 
				
				$_SESSION['outright_errors'] = $connect_error ;
				return false;
			}
			$this->query_exec_time = microtime(true) - $this->query_exec_time;
			$suc_msg['exec_time']   = 'Query Execution Time:'.$this->query_exec_time;
			$suc_msg['suc_msg']     = 'Executed Query:'.$sql;
			if(!$GLOBALS['log']->fatal($suc_msg)){
				outrigt_error_log($this->logfile,$suc_msg);
			}
			return true;
			
		}
		
		/**
	     * Data base export 
	     * @param  string $sql_file      : SQL file to execute 
	     * @param  bool    $dieOnError   : True if we want to call die if the query returns errors 
	     * @param  bool    $update_user  : If it is True Updates User name & password other couldont perform 
	     * @param  string   $msg         : Message to log if error occurs
	     */
		
		public function outright_db_export($sql_file = null,$update_user = false,$dieOnError = false,$msg=''){
			
			if(is_null($sql_file)){
				$sql_file = "qucick_install.sql";
			}
			if(!file_exists($sql_file)) {
				$err_msg[]             = "Error has been occured while creating tables  Please refer to outrightcrm.log for details.";
				$realtm_err[]          = "file $sql_file does not exist";
				if(!$GLOBALS['log']->fatal($err_msg)){
					outrigt_error_log($this->logfile,$realtm_err);
				}
				$_SESSION['outright_errors'] = $err_msg ;
				return false;
			}
			$query_string  = file($sql_file);
			if(!$query_string && empty($query_string)){
				$err_msg[]             = "Error has been occured while creating the tables  Please refer to outrightcrm.log for details.";
				$realtm_err[]          = "unableto load the  data from $sql_file or change the file permission to avoid this error. ";
				if(!$GLOBALS['log']->fatal($err_msg)){
					outrigt_error_log($this->logfile,$realtm_err);
				}
				$_SESSION['outright_errors'] = $err_msg ;
				return false;
			}
			foreach ($query_string as $query){
				if (substr($query, 0, 2) == '--' || $query == '') continue;
				$templine .= $query;
				if (substr(trim($query), -1, 1) == ';'){
					if($this->outright_mysql_query($templine)==false ){
						$err_msg[]             = "Error has been occured while creating the tables  Please refer to outrightcrm.log for details.";
				        $realtm_err[]          = "Unable to execute the query ".$sql.":".mysql_error($this->dbconn);
						if(!$GLOBALS['log']->fatal($err_msg)){
							outrigt_error_log($this->logfile,$realtm_err);
						}
						$_SESSION['outright_errors'] = $err_msg ;
						return false;
					}
					$templine = '';
				}
			}
			if($update_user){
				$change_user_cre  = "UPDATE users SET user_name='".$_SESSION['setup_site_admin_office_email']."' , user_hash ='".md5($_SESSION['setup_site_admin_office_email'])."' ";
				if($this->outright_mysql_query($change_user_cre)==false){
					$err_msg[]             = "Error has been occured while changing the user details Please refer to outrightcrm.log for details.";
					$realtm_err[]          = "Unable to change the user details ".$sql.":".mysql_error($this->dbconn);
					if(!$GLOBALS['log']->fatal($err_msg)){
						outrigt_error_log($this->logfile,$realtm_err);
					}
					$_SESSION['outright_errors'] = $err_msg ;
					return false;
				}
		    }
			return true;
		}
	    
	}
	
	
}