<?php

global $currentModule;
global $current_language;
global $current_user;
global $sugar_flavor;
global $sugar_config;

require('modules/Administration/metadata/adminpaneldefs.php');
global $app_list_strings;
require('cache/modules/Administration/language/en_us.lang.php');
$tab = array();
$header_image = array();
$url = array();
$onclick = array();
$label_tab = array();
$id_tab = array();
$description = array();
$group = array();
$sugar_smarty = new Sugar_Smarty();
$values_3_tab = array();
$admin_group_header_tab = array();
global $admin_group_header;  ///variable defined in the file above.
foreach ($admin_group_header as $key=>$values) {
	$module_index = array_keys($values[3]);
    $addedHeaderGroups = array();
    foreach ($module_index as $mod_key=>$mod_val) {
        if((!isset($addedHeaderGroups[$values[0]]))) {
			$outright_section=array();
			$tmparray=array();
			$outright_sql="SELECT * FROM outr_admin_section WHERE deleted='0'";
			$outright_fin_res = outright_run_sql($outright_sql);
			foreach ($outright_fin_res as $outright_values) {
				$outright_section[] = $outright_values['name'];
			}
            $admin_group_header_tab[]=$values;
            $outright_merge_section = translate($values[0],'Administration');
			if(in_array($outright_merge_section,$outright_section))
			{
		
			}
			else
			{
				$date_entered=date("Y/m/d H:i:s", strtotime('-330 minutes'));
				$in_data=array('name'=>$outright_merge_section,'date_entered'=>$date_entered);
				$db_name = 'outr_admin_section';
				outright_insert($db_name,$in_data);
			}
            $colnum=0;
            $i=0;
            $fix = array_keys($values[3]);
            if(count($values[3])>1){

                $tmp_array = $values[3];
                $return_array = array();
                foreach ($tmp_array as $mod => $value){
                    $keys = array_keys($value);
                    foreach ($keys as $key){
                        $return_array[$key] = $value[$key];
                    }
                }
                $values_3_tab[]= $return_array;
                $mod = $return_array;
            }
           else {
                $mod = $values[3][$fix[0]];
    	        $values_3_tab[]= $mod;
           }
           //~ print_r($mod);die();
            foreach($mod as $key1=>$values2)
			{
				$search_option=array();
				//~ $key2 = preg_replace('/(?<! )[A-Z]/', ' $0', $key1);
				$options = $mod_strings[$values2[1]];
				if(empty($options))
				{
					$key2 = preg_replace('/(?<! )[A-Z]/', ' $0', $key1);
					$options = ucwords(str_replace('_',' ',$key2));
				}
				$urls=ltrim($values2[3], '.');
				$outright_options_sql="SELECT name FROM outr_section_links WHERE deleted='0'";
				$outright_options_fin_res = outright_run_sql($outright_options_sql);
				$desc=$mod_strings[$values2[2]];
				foreach($outright_options_fin_res as $val1)
				{
					$search_option[]=$val1['name'];
				}
				if(in_array($options,$search_option))
				{
					
				}
				else
				{
					$date_entered=date("Y/m/d H:i:s", strtotime('-330 minutes'));
					$in_data=array('name'=>$options,'url'=>$urls,'description'=>$desc,'date_entered'=>$date_entered);
					$db_name = 'outr_section_links';
					outright_insert($db_name,$in_data);
					$outright_linkid_sql="SELECT id FROM outr_section_links WHERE name='$options'";
					$outright_link_id_sql = outright_run_sql($outright_linkid_sql);
					$section_link_id = $outright_link_id_sql[0]['id'];
					$outright_sectionid_sql="SELECT id FROM outr_admin_section WHERE name='$outright_merge_section'";
					$outright_section_id_sql = outright_run_sql($outright_sectionid_sql);
					$admin_section_id = $outright_section_id_sql[0]['id'];
					$in_data=array('outr_admin_section_outr_section_links_1outr_admin_section_ida'=>$admin_section_id,'outr_admin_section_outr_section_links_1outr_section_links_idb'=>$section_link_id);
					$db_name = 'outr_admin_section_outr_section_links_1_c';
					outright_insert($db_name,$in_data);
					
				}
				
			}
}
}
}
echo "<script>window.location.href='".$sugar_config['site_url']."/index.php?module=Administration&action=index';</script>";

