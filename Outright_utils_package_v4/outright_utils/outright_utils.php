<?php

/*****************************************
 * start outright_convert_to_http
 *      @description  : Custom https to https function
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : echo customised message
 *      
 * ***************************************/
function outright_convert_to_http($str_url=false){
	global $sugar_config;
	if(!isset($str_url)){
		$str_url = $sugar_config['site_url'];
	}	
	return str_replace('https', 'http', $str_url);
}

/*****************************************
 * start outright_convert_to_https
 *      @description  : Custom echo function
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : echo customised message
 *      
 * ***************************************/
function outright_convert_to_https($str_url=false){
	global $sugar_config;
	if(!isset($str_url)){
		$str_url = $sugar_config['site_url'];
	}	
	return str_replace('http', 'https', $str_url);
}

/*****************************************
 * start outright_get_duration
 *      @description  : Custom echo function
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : echo customised message
 *      
 * ***************************************/
function outright_get_duration($endtime,$starttime){	
	$duration_arr = array();
	if(isset($endtime) && isset($starttime)){
		$diff = date_diff(date_create($starttime), date_create($endtime) );		
		$duration_arr =  array(
				'yr' => $diff->y,
				'mon' => $diff->m,
				'day' => $diff->d,
				'hr' => $diff->h,
				'min' => $diff->i,
				'sec' => $diff->s
			);	
	}
	return $duration_arr;	
	
}
/*****************************************
 * start outright_echo
 *      @description  : Custom echo function
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : echo customised message
 *      
 * ***************************************/
function outright_echo($msg, $bold = false){
	if($bold){
		echo "<b>".$msg."</b>";
	}
	else{
		echo $msg;
	}
}

/*****************************************
 * start outright_print_r
 *      @description  : Custom print_r function
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : print_r customised message
 *      
 * ***************************************/
function outright_print_r($msg, $bold = false, $pre = false){
	if($pre){
		echo "<pre>";
		if($bold){
			echo "<b>";
			print_r($msg);
			echo "</b>";
		}
		else{
			print_r($msg);
		}
		echo "</pre>";
	}
	else{
		if($bold){
			echo "<b>";
			print_r($msg);
			echo "</b>";
		}
		else{
			print_r($msg);
		}
	}
}

/*****************************************
 * start outright_get_calendar_settings
 *      @description  : fetch calendar syncronization settings from DB
 *      @Author       : Outright 
 *      @Date         : 
 *      
 *      @return  array : Returns settings array if found.
 *      
 * ***************************************/
function outright_get_calendar_settings(){
	$sql = "select * from outright_calendar_settings order by date_entered desc limit 1";
	$arr = outright_run_sql_one_row($sql);
	return $arr;
}


/*****************************************
 * start outright_save
 *      @description  : Update / Insert  a record
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string    ---> $mod_name   : On which Module do you want to inser/update the  data  ,
 *      @param   array     ---> $data_array : Parameters to insert  data in to the table , This array should be in Associative , 
 *      @param   string    ---> $bean_id   : bean_id indicates where we want to update the data in a table , 
 *      for example ::: update  table_name set db_filed_name = value   where id =$bean_id
 *      @return  int|false ---> $bean_id    : Return  record's id on success or false on fail
 *      @since 1.0.0
 *      
 * ***************************************/

function outright_save($mod_name,$data_array,$bean_id=false){
	
	$new_bean = new $mod_name;
	if(!$bean_id){
	  $bean_id = create_guid();
	  $new_bean->new_with_id =1;
	  $new_bean->id=$bean_id;
	}else{
       $new_bean->retrieve($bean_id);
    }
	foreach($data_array as $key=>$val){
	  $new_bean->$key = $val;
	}
    $new_bean->save();
    return $new_bean;
    
}/**End outright_save **/

/*****************************************
 * start outright_insert
 *      @description  : Create / Insert  a record
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string    ---> $tbl_name   : On which Table do you want to inser the  data  ,
 *      @param   array     ---> $data_array : Parameters to insert  data in to the table , This array should be in Associative ,
 *      @return  int|false ---> $bean_id    : Return new added record's id on success or false on fail
 *      @since 1.0.0
 *     
 * ***************************************/

function outright_insert($tbl_name , $data_array,$bean_id=false){
	
		global $current_user;
		$tbl_name = strtolower($tbl_name);
		$today    = date("Y-m-d h:i:s");
		$sql_from = "INSERT INTO ".$tbl_name." ( ";
		if(!$bean_id){
		   $bean_id = create_guid();
		}
		$ins_cols   = "  id ,date_modified,deleted ";
		$ins_values = " VALUES ( '".$bean_id."' , '".$today."' , 0  ";
		
		foreach($data_array as $key=>$val){
			$ins_cols   .=" , ".$key;
			$ins_values .=" , '".$val."' ";
		}
		
	   $ins_values .=" ) ";
	   $ins_cols   .=" ) ";

	   $final_sql   = $sql_from.$ins_cols.$ins_values;
	   outright_run_sql($final_sql);
	   return $bean_id;
	   
}/** End outright_insert**/

/*****************************************
 * start outright_update
 *      @description  : Update / Insert  a record
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string    ---> $tbl_name   : On which Table do you want to Update  the  data 
 *      @param   array     ---> $data_array : Parameters to insert in a table ,This array format should be in Associative  array,
 *      @param   string    ---> $bean_id    : parent id to update
 *      @return  int|false ---> $bean_id    : Return's the updated record's id on success or false on fail
 *      @since 1.0.0
 *      Note : This function couldn't Update the data in CRM style
 *      
 * 
 * ***************************************/

function outright_update($tbl_name , $data_array,$bean_id){
	
	global $current_user;
	$tbl_name = strtolower($tbl_name);
	$today    = date("Y-m-d h:i:s");
    $today    = date("Y-m-d h:i:s");
    $day_flag = date('z');
    $sql_from ="UPDATE  ".$tbl_name."  SET  date_modified = '".$today."' ";
	if(!$bean_id){
	  $bean_id = create_guid();
	}

	foreach($data_array as $key=>$val){
		$sql_from .=", ".$key." = '".$val."' ";
	 }
    $sql_where =" where id ='".$bean_id."' ";
    $final_sql =$sql_from.$sql_where;

    outright_run_sql($final_sql);

    return $final_sql;

}/** End outright_update**/



/*****************************************
 * 
 *      @description  :Load User Role 
 *      @Author       : Outright 
 *      Date          : 
 *      @param   string      ---> $role_id   :  Role id of user 
 *      @return  array|false ---> $result    :  Return's the user role data on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/

function outright_get_role_name($role_id){
     
    $qry = "SELECT id,name,description from acl_roles WHERE deleted=0 and id='".$role_id."' ";
    $result =outright_run_sql_one_row($qry);
    return $result;
}

/*****************************************
 * 
 *      @description  : Array To String Conversion  
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   array        ---> $input_arr  :  array value to convert into string  
 *      @return  string|false ---> $return_str :  Return's the string on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/

function outright_array_to_sql_string($input_arr){
  if(!is_array($input_arr)){
		return false ; 
  }	
  $return_str ="'".implode("','",$input_arr)."'";
  return $return_str;
}

/*****************************************
 * 
 *      @description  : Get Contact Name
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string      ---> $contact_id :  Contacts Id; 
 *      @return  array|false ---> $data_result :  Return's the array on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/

function outright_get_contact_name($contact_id){
	    global $db;
		$sql ='select first_name,last_name from contacts where id="'.$contact_id.'" and deleted = 0 ';
		$res =$db->query($sql);
		while($row = $db->fetchByAssoc($res)){
			$data_result['first_name'] = $row['first_name']; 
			$data_result['last_name'] = $row['last_name'];
		}
		return $data_result ? $data_result :false;
}


/*****************************************
 * 
 *      @description  : Get User Name
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string      ---> $user_id :  User's Id; 
 *      @return  string|false ---> $user_name :  Return's the string on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/
 
function outright_get_user_name($user_id){
	
	global $db;
	$sql ='select first_name,last_name from users where id="'.$user_id.'" and deleted = 0 ';
	$res =$db->query($sql);
	while($row = $db->fetchByAssoc($res)){
	   $user_name = $row['first_name']." ".$row['last_name']; 	
	}
	return $user_name ? $user_name:false;

}


/*****************************************
 * start outright_run_sql
 *      @description  : Fetch the data from data base and 
 *      @Author       : Outright 
 *      @Date         : 
 *      @param   string      ---> $sql :  mysql query value; 
 *      @return  array|false ---> $fin_res :  Return's the array on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/


function outright_run_sql($sql){
	global $db;
	$fin_res = array();
	$row =$db->query($sql);
	while($res=$db->fetchByAssoc($row)){	
	$fin_res[] =$res;	
	}
	return $fin_res ? $fin_res:false;
}/** End outright_run_sql**/



/*****************************************
 * start outright_run_major_sql
 *      @description  : Get Data from data base along with created user name & assigned user name
 *      @Author       : Outright 
 *      @Date         :  
 *      @param       string--->$sql : mysql query value;
 *      @return  array|false ---> $fin_res :  Return's the array on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/


function outright_run_major_sql($sql){
	global $db;
	$x=0;
	$fin_res = array();
	$row =$db->query($sql);
	while($res=$db->fetchByAssoc($row)){	
	$fin_res[$x] =$res;	
		if($res['created_by'] && !empty($res['created_by'])){
				$qry = " select last_name from users where deleted=0 and id='".$res['created_by']."'";
				$name= outright_run_sql($qry);
				$fin_res[$x] = $fin_res[$x] + array( 'created_by_name' => $name[0]['last_name']);
			}
		if($res['assigned_user_id'] && !empty($res['assigned_user_id'])){
				$qry = " select last_name from users where deleted=0 and id='".$res['assigned_user_id']."'";
				$name= outright_run_sql($qry);
				$fin_res[$x] = $fin_res[$x] + array( 'assigned_user_id_name' => $name[0]['last_name']);
			}
		++$x;
	}
	return $fin_res;
}/** End outright_run_major_sql **/


/*****************************************
 * 
 *      @description  : Get Product Details
 *      @Author       : Outright 
 *      @Date         :  
 *      @return  array|false ---> $fin_res :  Return's the array on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/

function outright_getProducts(){
	  $sql ='select id,name from out_out_products where deleted =0';
	  $res = outright_run_id_name_sql($sql,'id','name');
      $mod_arr[''] = '';
      foreach($res as $id=>$name){
		$mod_arr[$id] = $name;
      }
      return $mod_arr ? $mod_arr:false;
}

/*****************************************
 * 
 *      @description  : Checks user email address is existed or not in office 365 table
 *      @Author       : Outright 
 *      @Date         :  
 *      @param       string--->$email : Email address of the user ;
 *      @return  record_id|false ---> $fin_res :  Return's the record_id on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/

function outright_check_email_exist($email){ 
	
    $sql    = "select id from outright_calendar_users where email_address ='".$email."'";
    $result = outright_run_sql_one_row($sql);
	if($result['id']){
		return $result['id'];
	}
	else{
		return false;
	}
}

/*****************************************
 * outright_get_user_id_from_email
 *      @description  : fetch id of any user from their email id.
 *      @Author       : Outright 
 *      @Date         :  
 *      @param       string--->$email : Email address of the user ;
 *      @return  data array of user details
 *      @since 1.0.0
 * 
 * ***************************************/
function outright_get_user_id_from_email($email){

$user = "select id from users where email_address = '".$email."'";
	$res = outright_run_sql_one_row($user);
	$arr = array('id' => $res['id'], );
	return $arr;
}

/*****************************************
 *start outright_run_sql_one_row 
 *      Returns the only one row from data base 
 *      @Author       : Outright 
 *      @Date         :  
 *      @param       string--->$sql : sql query value  ;
 *      @return  array|false ---> $fin_res :  Return's the array on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/
 
function outright_run_sql_one_row($sql){
	global $db;
	$row = $db->query($sql);
	$res = $db->fetchByAssoc($row);
	return $res?$res:false;
}/** End outright_run_sql_one_row**/

/*****************************************
 * 
 *      @description  : Check givien number is phone number or not 
 *      @Author       : Outright 
 *      Date          :  
 *      @param       int--->$number : Phone number to check  ;
 *      @return  int|false ---> $num :  Return's the integer on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/


function outright_check_is_phone_number($number){
		$extension = '';
		$number = str_replace('(','',$number);
		$number = str_replace(')','',$number);
		$number = str_replace('-','',$number);
		$number = str_replace(' ','',$number);
		$length = strlen($number);
		if($length == 13){
				$extension = substr($number,0,2);
				$number = str_replace($extension,'',$number);
			}
		if(!is_numeric($number)){
				return false;
			}
		$index = 0;
		for($i=0;$i < 10;$i++){
			if($i == 0){
			   $num[$index] = '(';
			  ++ $index;
			}
			$num[$index] = $number[$i];
			++ $index;
				
			if($i == 2){
			   $num[$index] = ') ';
			   ++ $index;
			}
			else if($i == 5){
				$num[$index] = '-';
				++ $index;
			}
		}
		$num = implode('',$num);
		$num = $extension.$num;
		return $num?$num:false;
}
	
/*****************************************
 * 
 *      @description  : Returns the fetched data from database in array fromat or string format
 *      @Author       : Outright 
 *      @Date         :  
 *      @param       string--->$sql : sql query value  ;
 *      @param       boolean--->$ret_str : boolean value for array to string convert ;
 *      @return  array/string|false ---> $fin_res :  Return's the array/string on success or false on fail      
 *      @since 1.0.0
 * 
 * ***************************************/	
	
	
function outright_run_id_sql($sql,$ret_str=0){
	global $db;
	$fin_res = array();
	$retrun_str ='';
	$row =$db->query($sql);
	while($res=$db->fetchByAssoc($row)){	
	  $fin_res[] =$res['id'];	
	}
	if($ret_str){
	  return outright_array_to_sql_string($fin_res);
	}
	return $fin_res ? $fin_res:false;
}

/*****************************************
 * 
 *     @description  : Array to html drop down convert 
 *     @Author       : Outright 
 *     @Date         :  
 *     @param       array--->$optionArr : options array;
 *     @param       int|string--->$selected : selected value for drop down list ;
 *     @return  string|false ---> $option :  Return's the string on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_convert_options_from_array($optionArr,$selected){
	foreach($optionArr as $key => $values){
	   $sel = '';
	   if($key == $selected){
		   $sel = "selected";
	   }
	   $option .= "<option value='".$key."' ".$sel." >".$values."</option>";
	}
   return $option?$option:false;
}


/*****************************************
 * 
 *     @description  : Returns the account ids in string format base on contact id details
 *     @Author       : Outright 
 *     @Date         :  
 *     @param       string --->$contacts_ids_str : contact id values in string format with comma;
 *     @return      string --->$option :  Return's the string on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_get_related_accounts($contacts_ids_str){
   global $db;
   $ret_accounts = array();
   $sql          = " SELECT account_id as id from accounts_contacts where contact_id in ($contacts_ids_str) and deleted =0 ";
   $ret_accounts = outright_run_id_sql($sql,1);
   return $ret_accounts ? $ret_accounts:false;
}


/*****************************************
 * 
 *     @description  : Create New Task
 *     @Author       : Outright 
 *     @Date         :  
 *     @param       int --->$parent_id       : related parent id for task;
 *     @param      string --->$parent_status : status of the parent   ;    
 *     @param      string --->$note_body     : description of the task   ;    
 *     @param      string --->$parent_module : parent module name;    
 *     @return     obj|false --->$note       : Return's the object on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/
 
function create_note($parent_id,$parent_module,$note_type,$note_body){
	$note = new Note();
	$note->parent_id   = $parent_id;
	$note->note_type   = $note_type;
	$note->description = $note_body;
	$note->parent_type = $parent_module;
	$note->Save();
	return $note ? $note:false;
}

/*****************************************
 * 
 *     @description  : Load Contact details 
 *     @Author       : Outright 
 *     @Date         :  
 *     @param       int --->$contact_id      : Contact's id;
 *     @param      string --->$info_fields   :    ;        
 *     @return     obj|false --->$acc        : Return's the object on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_load_contact($contact_id,$info_fields){
   $acc = new Contact();
   $acc->retrieve($contact_id);
   return $acc ?$acc:false ;
}

/*****************************************
 *start outright_re_save 
 *     @description  : Resave function
 *     @Author       : Outright 
 *     @Date         :  
 *     @param     string --->$obj_name  : Objecct name or module name;
 *     @param     string --->$id        : Id of the object or record id    ;        
 *     @return    obj|false --->$new_bean   : Return's the object on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_re_save($obj_name , $id){
		$new_bean = new $obj_name();
		$new_bean->retrieve($id);
		$new_bean->save();
		return $new_bean ? $new_bean:false;
	}/**End outright_re_save**/
	
/*****************************************
 * start outright_copy_bean
 *     @description  : Copy bean Details
 *     @Author       : Outright 
 *     @Date         :  
 *     @param     obj --->$old_bean  : Objecct details or bean details;       
 *     @return    id|false --->$new_id   : Return's the id on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/
	
 function outright_copy_bean($old_bean){
	$new_bean = new $old_bean->object_name;
			foreach($new_bean->field_defs as $key => $value){
				if(in_array($key, array("id", "date_entered"))){
					continue;
				}
				if($value["type"] == "link"){
					continue;
				}
				$new_bean->$key = $old_bean->$key;
			}
	$new_id = $new_bean->save();
	return $new_id ? $new_id:false;
 }/** End outright_copy_beaout_global_search&action=DetailView&record=57dc97c8-9af5-c7af-b7ff-5b6d7dabfaeen**/



/*****************************************
 * 
 *     @description  : Create New call
 *     @Author       : Outright 
 *     @Date         :  
 *     @param     string --->$parent_id    : Parent modules's id;       
 *     @param     string --->$parent_type  : Parent modules's name;       
 *     @param     dtae   --->$start_date   : Starting date of the call;       
 *     @param     string --->$subject      : Subject of the call;       
 *     @param     string --->$message      : Message of the call;       
 *     @return    id|false --->$new_id     : Return's the id on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/



/*****************************************
 * 
 *     @description  : Set contact info relation details 
 *     @Author       : Outright 
 *     @Date         :  
 *     @param     string --->$contact_id        : Parent modules's id;       
 *     @param     string --->$email_address     : Parent modules's name;        
 *     @return    array|false --->$con_info_id  : Return's the id on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/
 
function outright_set_contact_contact_info($contact_id,$email_address){	
		
	require_once 'modules/outr_contact_info/outr_contact_info.php';
	$today =date('Y-m-d h:i:s');
	$con_info_id = create_guid();
	$info_rel_id = create_guid();

	$conf_info = new outr_contact_info();
	$conf_info->new_with_id = 1;
	$conf_info->id          = $con_info_id;
	$conf_info->email1      = $email_address;
	$conf_info->save();

	$sql =" INSERT INTO  contacts_outr_contact_info_1_c (
	id ,date_modified ,deleted ,contacts_outr_contact_info_1contacts_ida ,contacts_outr_contact_info_1outr_contact_info_idb)
	VALUES (
	'".$info_rel_id."' ,'".$today."' ,0,'".$contact_id."' ,'".$con_info_id."' 
	)";
	outright_run_sql($sql);
	return $con_info_id ? $con_info_id :false;
}

/*****************************************
 * 
 *     @description  : Get accoutn Roles Name
 *     @Author       : Outright 
 *     @Date         :        
 *     @return    array  --->$get_roles         : Return's the array on success;    
 *     @since 1.0.0
 * 
 * ***************************************/
 
function outright_account_type(){
      global $db;
      $qry  = "SELECT id,name from acl_roles WHERE deleted=0";
      $qry1 = $db->query($qry);
      $get_roles[''] = '';
      while($row = $db->fetchByAssoc($qry1)){
		   $get_roles[$row['name']] = $row['name'];
	  }
      return $get_roles;
}

/*****************************************
 * 
 *     @description :Search Record Details
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$tableName         : modules's name or table name       
 *     @param     array  --->$fieldNameArr      : Requested fields to show   
 *     @param     array  --->$wherefielsd       : Condition based search     
 *     @return    array|false --->$result       : Return's the result array on success or false on fail    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_search_record($tableName,$fieldNameArr=array(),$wherefielsd=array()){
	   $where     = '';
	   $fieldName = '';
	   if(!empty($fieldNameArr)){
		 foreach( $fieldNameArr as $values ){
				$fieldName .= $values." , ";
		 }
		  $selectField = rtrim($fieldName, ' , ');
	   }
	   if(!empty($wherefielsd)){
			$where .= ' where ';
			foreach($wherefielsd as $key=>$values){
				$where .=$key."= '".$values."' AND ";
			}	
			$where = rtrim($where, ' AND ');
	    }
		$finalQuery  = "select ".$selectField." from ".$tableName.$where." ;";
		$result      = outright_run_sql($finalQuery); 
		return $result?$result:false ;
		
}


/*****************************************
 * start outright_load_relationship
 *     @description : Load Relation Ship 
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$bean_name      : Parent modules's name ;       
 *     @param     string --->$bean_id        : Parent modules's id;        
 *     @param     string --->$rel_name       : Relation modules's name;        
 *     @return    obj|false --->$result      : Return's the relatedBeans obj on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/


function outright_load_relationship($bean_name,$bean_id,$rel_name){
	$bean = BeanFactory::getBean($bean_name, $bean_id);
	if ($bean->load_relationship($rel_name)){
	   $relatedBeans = $bean->$rel_name->getBeans();
	}
	return $relatedBeans ? $relatedBeans:false;
}/**End outright_load_relationship**/
	
/*****************************************
 * 
 *     @description : Enum to array convertion
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$enum_string      : Enum string;       
 *     @param     string --->$enum_string_js   : Enum js string;              
 *     @return    array|false --->$enum_string_js : Return's the enum_string_js array on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/	
	
	
 function outright_convert_enum_to_array($enum_string,$enum_string_js){
		
	$final_ret_array = array();
	$enum_string     = str_replace('^',"",$enum_string);
	$final_ret_array = explode(',',$enum_string);
	$enum_string_js  = str_replace('^',"#",$enum_string_js);
	$enum_string_js  = str_replace('#,',",",$enum_string_js);
	$final_ret_array['enum_js'] = substr($enum_string_js,0,-1);
	return $final_ret_array?$enum_string_js:false;	
			
 }
 
 



/*****************************************
 * 
 *     @description : Handle Ajax Request 
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$source_filed  : field class name or ID ; example :: class name should be ".class" and id "#idva"       
 *     @param     string --->$event            : jquery event name e; example 'click','dblclick','mouseenter'              
 *     @param     string --->$url              : Request URL for ajax              
 *     @param     string --->$destiation_field : class name or ID             
 *     @param     string --->$method     : methods for  request-response between a client and server are: GET and POST.             
 *     @param     JSO object --->$method : Requested data to process example :: $request_data = { "name":"John","age":"10"}         
 *     @return    string|obje|true|false : Return's the true|obj|string on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/	

function outright_ajax_on_event($source_filed,$event=false,$url,$destiation_field=false,$show_alert=false,$console_log=false,$method='post',$request_data){
	echo '<script>';
	if($event){
	  echo "$('".$source_filed."').".$event."(function(){";
	}
	  echo "$.ajax({
	        url  : '".$url."',
	        type : '".$method."',
	        data : ".$request_data.",
	        success: function(result){";
			if($destiation_field) { 
				echo "$('".$destiation_field."').html(result);";
		    }
			if($show_alert){
				echo 'alert(result);';	
			}
			if($console_log){
				echo 'console_log(result);';	
			}
		echo '} });';
	if($event){	
	  echo "});";
	}
	echo '</script>';
}


/*****************************************
 * 
 *     @description : Loads The email Templates
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$template_id  : Email Template id    
 *     @param     string --->$account_id   : parents Module id                              
 *     @return    string|false             : Return's the string on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/	


function outright_load_template($template_id,$account_id){
	global $db, $current_user;
	require_once('modules/EmailTemplates/EmailTemplate.php');
	$template = new EmailTemplate();
	$template->retrieve($template_id);
	$acc = outright_load_account($account_id);
			
	$all_dm_name ='';
	$apuser = new User();
	$cmuser = new User();
	$cmuser->retrieve($acc->assigned_user_id);
	$apuser->retrieve($acc->created_by);


	$all_con_ids = outright_get_contact_ids($account_id);

	$replace_array1 = array(
		'$dmname'=>$firtDM[0],
		
		'$curusrpn3'=>$current_user->phone3,
		);
				
		foreach($replace_array1 as $key=>$arr){		
		  $template->body_html =str_replace($key,$arr,$template->body_html);	
		}

	return $template->body_html?$template->body_html:false;
}

/*****************************************
 * 
 *     @description : Loads The account details for particular record
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$account_id  : Accounts Id                               
 *     @return    Obj|false               : Return's the object value on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_load_account($account_id){
	$acc = new Account();
	$acc->retrieve($account_id);
	return $acc?$acc:false;
}


/*****************************************
 * 
 *     @description : Parse the Html details
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$account_id  : Accounts Id                               
 *     @param     string --->$html        : Html string value                               
 *     @return    string|false            : Return's the string value on success or false on fail  ;    
 *     @since 1.0.0
 * 
 * ***************************************/
function outright_parseHTML($html,$account_id){
	global $db;
	$acc         = outright_load_account($account_id);
			
    $all_dm_name = '';
	$apuser      = new User();
	$cmuser      = new User();
	$cmuser->retrieve($acc->assigned_user_id);
	$apuser->retrieve($acc->created_by);


	$all_con_ids = outright_get_contact_ids($account_id);

	/*Get host name*/
	$row = $db->query("select * from project_cstm where id_c = '".$acc->show_name."'");
	$res =$db->fetchByAssoc($row);
	
		foreach($replace_array1 as $key=>$arr){		
		  $html =str_replace($key,$arr,$html);	
		}
	return $html?$html:false;

}




/*****************************************
 * 
 *     @description : option to array convert 
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$sql           : mysql query value                                                           
 *     @param     string --->$key_col       : colomn key value                                                                
 *     @param     string --->$val_col       : value  of the colomn                                                                
 *     @return    string|false --->$option  : Returns string value on success or false in fail                                                             
 *     @since 1.0.0
 * 
 * ***************************************/
 
 
function outright_convert_options_from_sql($sql,$key_col,$val_col,$selected=false){
		$options_array = outright_run_id_name_sql($sql,$key_col,$val_col,$selected);	
		$option        = outright_convert_options_from_array($options_array,$selected);
		return $option?$option:false;
	}

/*****************************************
 * 
 *     @description : Account email address
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$account_id    :account id                                                        
 *     @return    array|false --->$all_info_list  : Returns array value on success or false in fail 
 *     @since 1.0.0
 * 
 * ***************************************/
 
function get_account_email($account_id){
	$cont_ids       = outright_get_contact_ids($account_id);
	$all_email_list = array();
	$all_info_list  = array();
	foreach($cont_ids as $con_id){
	  $all_info_list[]= outright_get_contact_contact_info($con_id);
	}
	foreach($all_info_list as $info_beans){
	   if($info_beans['email1']){
		$all_info_list[] =$info_beans['email1'];
	   }
	}
	return $all_info_list?$all_info_list:false;
}

/*****************************************
 * 
 *     @description : contact info details
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$contact_id       : contact id                                                        
 *     @param     array  --->$retrun_fields    : return field values in array format    
 *     @return    array|false --->$return_arr  : Returns array value on success or false in fail 
 *     @since 1.0.0
 * 
 * ***************************************/
function outright_get_contact_info_fields($contact_id,$retrun_fields=array('email1'),$key_seperator=' , '){

	$info_beans = outright_get_contact_contact_info($contact_id);
	$return_arr = array();
	foreach($info_beans as $key=>$bean_info){
		foreach($retrun_fields as $val){			
			if(isset($bean_info->$val) && !empty($bean_info->$val)){
			 $return_arr[$val] .=$bean_info->$val.$key_seperator;
			}
		}
	}
  return $return_arr ? $return_arr :false;
}

/*****************************************
 * 
 *     @description : Get colomns from table
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$table       : table name                                                          
 *     @return    array  : Returns array value on success 
 *     @since 1.0.0
 * 
 * ***************************************/
 
 
function outright_getcolumns($table) {
	global $db;
	$sql = "SHOW columns FROM ".$table;
	$rwo = $db->query($sql);
	$ar = array();
	$matchcols = array();
	$i = 0;
	while($res = $db->fetchByAssoc($rwo)){
		$matchcols[] = $res['Field'];
		$ar[$i] = array('fname'=> $res['Field'], 'ftype' => $res['Type']);
		$i++;
	}
	return array('cols'=>$matchcols, 'detail'=>$ar);
}

/*****************************************
 * 
 *     @description : Get user details
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$userID      : user id                                                              
 *     @return    obj|false  : Returns Obj value on success or false on fail
 *     @since 1.0.0
 * 
 * ***************************************/
 
function outright_get_user_details($userID){
	$user_bean = new User();
	$user_bean->retrieve($userID);
	return $user_bean?$user_bean:false;
}

/*********************
 *start outright_retrive
 * @description : Get user details
 * @Author      : Outright 
 * Date         :  
 * @param     string --->$userID      : user id                                                              
 * @return    obj|false  : Returns Obj value on success or false on fail
 * @since 1.0.0
 ******************/
 
  function outright_retrive($module,$id){
   $bean_data = new $module();
   $bean_data->retrieve($id);
   return $bean_data ?$bean_data:false ;
}/** End outright_retrive**/



function outright_set_record_list_inSession( $query , $modName , $extra_params = ''){
		if($query && $modName){
				$finalIdArr = array();
				$resArr = outright_run_sql($query);
				if($resArr){
						foreach($resArr as $key => $val){
							$finalIdArr[] = $val['id'];
						}
						$_SESSION[$modName."OutrightIDArray".$extra_params] = $finalIdArr;
						return true;
					}
				return false;				
			}
		return false;
	}
	
function outright_get_record_position($recordID,$modName,$extra_params){
		if($recordID && $modName){
				$finalIdArr = array();
				$finalIdArr = $_SESSION[$modName."OutrightIDArray".$extra_params];
				$recordPosition = array();
				$next = array_search($recordID,$finalIdArr);
				$recordPosition['previous']['recID'] = $finalIdArr[$next-1];
				$recordPosition['previous']['position'] = $next-1;
				$recordPosition['current']['recID'] = $finalIdArr[$next];
				$recordPosition['current']['position'] = $next;
				$recordPosition['next']['recID'] = $finalIdArr[$next+1];
				$recordPosition['next']['position'] = $next+1;
				
				return $recordPosition;
			}
		return false;
	}

function outright_check_maintenance_mode($isLogedIn = NULL){
		global $current_user,$sugar_config;
		if($isLogedIn){
				$res = outright_check_maintenance_mode_by_query();
				if($res){
						SugarApplication::redirect($sugar_config['site_url']);
					}		
			}
		else{
				if($current_user->id && !$current_user->is_admin){
						outright_check_maintenance_mode_by_query();
					}
				else if($current_user->is_admin){
						outright_check_maintenance_mode_by_query('check');
					}
			}
	}
	
function outright_check_maintenance_mode_by_query($check = false){
		global $current_user,$sugar_config;
		$maintenanceTimeArr = outright_get_maintenance_date_time();
		$qry = outright_get_maintenance_query($maintenanceTimeArr);
		$resArr = outright_run_sql($qry);
		if($resArr){
			foreach($resArr as $key => $res){
					if($res['time_end'] > $maintenanceTimeArr['onlyTime']){
							if(!$check){
									unset($current_user);
									session_destroy();
									return true;
									break;
								}
							if($check){
									$_SESSION['userUnderMaintenance'] = 1;
								}									
						}
					else if($res['time_end'] <= $maintenanceTimeArr['onlyTime']) {
							$_SESSION['userUnderMaintenance'] = 0;
						}
				}
			}
		return false;
	}
function outright_get_maintenance_date_time(){
		$maintenanceTimeArr = array();
		$maintenanceTimeArr['currentDate'] = date('Y-m-d');
		$currentDateTime = date('Y-m-d h:i a',strtotime('+0 hour'));
		$currentDateTime = strtotime($currentDateTime) + 31 * 60;
		$currentDateTime = date('Y-m-d h:i a',$currentDateTime);
		$maintenanceTimeArr['currentDateTime'] = $currentDateTime;
		$onlyTime = str_replace($currentDate.' ','',$currentDateTime);		
		$maintenanceTimeArr['onlyTime'] = date('H:i',strtotime($onlyTime));
		return $maintenanceTimeArr;		
	}
function outright_get_maintenance_query($maintenanceTimeArr){
		$qry = "select id , day_of_week , interval_day , time_start , time_end , time_start from out_maintenance_mode where deleted=0 and maintenance_status = 'Active' and execution_start_date <= '".$maintenanceTimeArr['currentDate']."' and time_start <= '".$maintenanceTimeArr['onlyTime']."' and time_end > '".$maintenanceTimeArr['onlyTime']."' ";
		return $qry;
	}

function outright_check_eligibal_for_maintenance($dataArr){
		if($dataArr['day_of_week'] && $dataArr['last_executed_date']){
				if($dataArr['day_of_week'] == date("D")){
						return true;
					}
				return false;				
			}
		else if($dataArr['interval_day'] && $dataArr['last_executed_date']){
				$lastExecutionTime = strtotime($dataArr['last_executed_date']) + 
				$dataArr['interval_day'] * 86400;
				if(date('Y-m-d') == 
				strtotime('Y-m-d',$lastExecutionTime)){
						return true;
					}
				else{
						return false;
					}
			}
	}

function outright_run_id_name_sql($sql,$field1,$field2,$ret_str=0){
	global $db;
	$fin_res = array();
	$retrun_str ='';
	$row =$db->query($sql);
	while($res=$db->fetchByAssoc($row)){	
	$fin_res[$res[$field1]] =$res[$field2];	
	}
	
	if($ret_str){
	return outright_array_to_sql_string($fin_res);
	}
	return $fin_res;
}


function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
        if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}
	function get_custom_fields_for_any_module($mod)
	{
        $outright_sql = "SELECT * FROM fields_meta_data where custom_module = '".$mod."' and deleted = 0";
        $res = outright_run_sql($outright_sql);
        return $res;
        
	}
	
	function outright_get_module_fields($module_name = NULL){
	global $app_list_strings;
		if($module_name){
				$fieldArr = outiright_return_ModuleFields($module_name);
				unset($fieldArr['id']);	
				return $fieldArr;
			}
		else{
				$fieldArr = array();
				$moduleList = outright_getModuleName_list();
				foreach( $moduleList as $key => $values ){
						if($values){
								$fieldArr[$key] = outiright_return_ModuleFields($values);
							}
					}
				unset($fieldArr['id']);	
				return $fieldArr;
			}
	}
	
 function outright_die($error_message, $exit_code = 1){
	echo "<pre>";
	print_r( $error_message);
	echo "</pre>";
	die($exit_code);
}



function outright_built_config(array $configOptions = null,$config_loader=true, $dieOnError = false){
	global $sugar_config; 
	if(is_null($configOptions)){
		$configOptions = $sugar_config;
	}
	if($config_loader){
		if(!file_exists('config.php')){
			require_once('config_outright.php');
		 }
		 if(file_exists('config.php')){
			 require_once('config.php');
		 }
	 }
	 if($sugar_config == false && !isset($sugar_config) && empty($sugar_config)){
		 
			$connect_error[]  = "Error has been occured while creatiing a config file . Please refer to outrightcrm.log for details.";
			$err_msg[]        = "Unable to load the data from config_outright.php(or)config.php file please check your directory structure file is existed or not." ;
			if(!$GLOBALS['log']->fatal($err_msg)){
				  outrigt_error_log($this->logfile,$err_msg);
			}
			if($dieOnError) {
				if(isset($GLOBALS['app_strings']['ERR_NO_DB'])) {
					outright_die($GLOBALS['app_strings']['ERR_NO_DB']);
				} else {
				  outright_die("Unable to load the data from config_outright.php(or)config.php file please check your directory structure file is existed or not.");
				}
			} 
			$_SESSION['outright_errors'] = $connect_error ;
			return false;
			
	 }
	 $sugar_config['installer_locked']         = true ;
	 $sugar_config['dbconfig']['db_host_name'] = $configOptions['db_host_name'];
	 $sugar_config['dbconfig']['db_user_name'] = $configOptions['db_user_name'];
	 $sugar_config['dbconfig']['db_password']  = $configOptions['db_password'];
	 $sugar_config['dbconfig']['db_name']      = $configOptions['db_name'] ;
	 $sugar_config['site_url']                 = $configOptions['site_url'] ;
	 $build_config  = outright_array_to_file_write('sugar_config',$sugar_config, 'config.php', $mode="w", $header='' ); 
	 if($build_config)
		return  true ;
	
}

