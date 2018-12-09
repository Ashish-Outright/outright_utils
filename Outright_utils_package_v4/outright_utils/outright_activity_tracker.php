<?php

function outright_set_outright_user_activities_tracker($action,$activitieData){
		switch($action){
				default :
					outright_set_activities_in_tracker($action,$activitieData);
			}
	}

function outright_set_activities_in_tracker($action,$activitieData,$record_id=false){
	global $current_user,$db;
	$loginTime = date('Y-m-d h:i:s');
		if($action != 'Login' && $action != 'Logout'){
				$qry = "select id , name from out_outright_activity_tracker_actions where deleted=0 and status='active'";
				$resArr = outright_run_sql($qry);
				$finalArr = array();
				foreach($resArr as $key => $val){
						$finalArr[] = $val['name'];
					}
				if(!in_array($action,$finalArr)){
						return false;
					}
			}
		$new_bean = new out_Outright_Activity_Tracker();
		$new_bean->new_with_id = 1;
		$new_bean->id = create_guid();
		$new_bean->name = 'Action : '.$action;
		$new_bean->user_name = $current_user->user_name;
		if($activitieData['record'])
				$new_bean->rec_id = $activitieData['record'];
		if($record_id)	
				$new_bean->rec_id = $record_id;
		$new_bean->parent_module = $_REQUEST['module'];
		$new_bean->user_ip = $_SERVER['REMOTE_ADDR'];
		$new_bean->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$new_bean->user_name = $current_user->user_name;
		$new_tracker_id = $new_bean->save();
	}
