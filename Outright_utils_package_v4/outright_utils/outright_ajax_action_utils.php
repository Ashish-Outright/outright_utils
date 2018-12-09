<?php
function outright_duplicateCheck($outright_data)
{
global $db;
	$sql = "select tb1.field_name as Field_Name , tb1.select_module_name , tb3.js_actions from outr_outrightdisplaymanager tb1 LEFT JOIN outr_outrightdisplaymanager_out_js_actions_1_c tb2 on tb1.id = tb2.outr_outri7894manager_ida AND tb2.deleted=0 LEFT JOIN out_js_actions tb3 on tb2.outr_outrightdisplaymanager_out_js_actions_1out_js_actions_idb = tb3.id AND tb3.deleted=0 where tb1.deleted=0 AND tb1.ajax_action='duplicateCheck' and tb1.select_module_name = '".$outright_data['module']."'";
	$res =$db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		$out_field = $row['Field_Name'];
		if(!empty($outright_data['record']))
		{
          $sql1 = "select ".$out_field." from ".strtolower($outright_data['module'])." where ".$out_field ." = '".$_REQUEST[$out_field]."' and id != '".$outright_data['record']."' and deleted=0";
	    }
	    else
	    {
			$sql1 = "select ".$out_field." from ".strtolower($outright_data['module'])." where ".$out_field ." = '".$outright_data[$out_field]."' and deleted=0";
	    }
	    
       $res1 =$db->query($sql1);
      $row1 = $db->fetchByAssoc($res1);
      if(empty($row1))
      {
		  return  "No duplicate";
	  }
	  else
	  {
		 return  "duplicate";  
	  }
	}
	return false;
}
