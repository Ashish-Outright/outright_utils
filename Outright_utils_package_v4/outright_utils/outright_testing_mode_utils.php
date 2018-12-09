<?php 

function outright_setup_testing_mode_on_listview($custom_ret_array , $requestArr){
		global $db;
		$sql = "select id from outright_system_settings_extension where deleted=0 and testing_mode_forcefully_on=1";
		$resData = outright_run_sql_one_row($sql);
		$testingModeRec = outright_get_test_record_name_from_testing_mode_settings();
		if($resData && $testingModeRec){
				$newObj = outright_get_bean_obj($requestArr['module']);
				$whereCon = outright_make_testing_mode_where_con($testingModeRec , $newObj->table_name ,$requestArr['module']);
				$custom_ret_array['where'] .= $whereCon;
			}
		return $custom_ret_array;
	}

function outright_get_bean_obj($moduleName,$recordID = NULL){
		$new_bean = BeanFactory::newBean($moduleName);
		if($recordID){
				$new_bean->retrieve($recordID);
			}
		return $new_bean;
	}

function outright_get_test_record_name_from_testing_mode_settings(){
		$qry = "select id , name , testing_mode_modules from out_testing_mode_settings where deleted=0 and testing_mode_on=1";
		$dataArr = outright_run_sql($qry);
		if($dataArr){
				return $dataArr;
			}
		return false;
	}

function outright_make_testing_mode_where_con($dataArr , $tableName ,$moduleName){
		$whereCon = '';
		$nameArr = array();
		$targetModuleArr = array();
		$conName = outright_get_test_mode_condition_name($moduleName);
		
		foreach($dataArr as $key => $val){
				$targetModuleArr = outright_get_clean_field_name($val['testing_mode_modules']);
				//~ if(in_array($moduleName,$targetModuleArr)){
						$nameArr[] = "'".$val['name']."'";
					//~ }
			}
			
		if($nameArr)
			$whereCon = ' AND '.$tableName.'.'.$conName.' IN ('.implode(',',$nameArr).')';
			
		return $whereCon;
	}	
	
function outright_get_all_relationship_by_module_name($moduleName){
			$relationshipArr = array();
			$skipArr = outright_get_skip_arr_for_relationship();
			$new_bean = BeanFactory::newBean($moduleName);
			foreach($new_bean->field_defs as $name => $fieldDefs){
					if(array_key_exists('relationship',$fieldDefs) && array_key_exists('module',$fieldDefs) && array_key_exists('bean_name',$fieldDefs) && !array_key_exists('link_type',$fieldDefs) && !in_array($name,$skipArr)){
							$relationshipArr['rel_defs'][$name] = $fieldDefs;
						}
					else if(array_key_exists('relationship',$fieldDefs) && !in_array($name,$skipArr)){
							$relationshipArr['rel_defs_link'][$name] = $fieldDefs;
						}
				}			
				
			if($relationshipArr){
					return $relationshipArr;
				}
			return false;
		}

function outright_get_skip_arr_for_relationship(){
		$skipArr = array('emails','members','cases','created_by_link','modified_user_link','assigned_user_link','reports_to_link','email_addresses_primary','email_addresses');
		return $skipArr;
	}

function outright_condition_name_arr(){
		$conditionArr = array(
				'Accounts' => 'name',
				'Contacts' => 'last_name',
				'Leads' => 'last_name',
				'Prospects' => 'last_name',
				'Calls' => 'name',
				'Meetings' => 'name',
				'Tasks' => 'name',
				'Documents' => 'document_name',
				'Campaigns' => 'name',
				'Opportunities' => 'name',
				'Bugs' => 'name',
				'Notes' => 'name',
				'ProspectLists' => 'name',
			);
		return $conditionArr;
	}
	
function outright_get_test_mode_condition_name($moduleName){
		$conditionArr = outright_condition_name_arr();
		$condition = "name";
		if(array_key_exists($moduleName,$conditionArr)){
				$condition = $conditionArr[$moduleName];
			}
		return $condition;
	}
	
function outright_get_record_name_by_id($moduleName , $recordID){
		$recordArr = array();
		$new_bean = outright_get_bean_obj($moduleName,$recordID);
		$val = outright_get_test_mode_condition_name($moduleName);
		if($moduleName == 'Leads' || $moduleName == 'Contacts' || $moduleName == 'Prospects'){
				$val = full_name;
			}
		$recordArr['recName'] = $new_bean->$val;
		$recordArr['recId'] = $new_bean->id;
		return $recordArr;
	}
