<?php

		function outright_load_hook($hook_name,$bean)
		{
           $sql = "select * from outr_outrightgeneratehooks where hook_name = '{$hook_name}' and deleted = 0";
		   $result = $GLOBALS['db']->query($sql);
		   $res = mysqli_fetch_assoc($result);
		   return $res;
		}
        function outright_load_hook_prefix($func_name,$bean,$event)
        {
			
			global $sugar_config;
            $outright_site_url = $sugar_config['site_url'] ;
			$module_name = $bean->module_name;
			$outright_table_name = $bean->table_name;
			$out_bean = BeanFactory::getBean($module_name);
            $field_defs[$module_name] = $out_bean->getFieldDefinitions();
            if($field_defs[$module_name]['first_name'] && $field_defs[$module_name]['last_name'])
            {
				$out_bean_last_name = $bean->last_name;
				$out_bean_first_name = $bean->first_name;
			}
			if($field_defs[$module_name]['name'])
			{
				$outright_bean_name = $bean->name;
			}
			$outright_module_name =  array('_'.$module_name);
			$outright_event_name = array('_'.$event);
		    $str1  = str_replace('_method','',$func_name);
		    $str2 = str_replace($outright_module_name,'',$str1);
		    $hook_name = str_replace($outright_event_name,'',$str2);
		    $hook_bean = outright_load_hook($hook_name,$bean);
		    $outright_new_records = $hook_bean['hook_new_recods'];
		    $outright_test_mode = $hook_bean['hook_test_mode'];
		    $outright_new_records_date = $hook_bean['hook_new_recods_date'];
		    if($outright_new_records)
		    {
				$sql ="select * from ".$outright_table_name." where id = '".$bean->id."' and deleted = 0";
				$result = $GLOBALS['db']->query($sql);
				$res= mysqli_fetch_assoc($result);
				$a = strtotime('+5 hour +30 minutes',strtotime($res['date_entered']));
				$b = strtotime($outright_new_records_date);
				if($b > $a)
				{
					 return false;
				}
				else
				{
					$module_name = $hook_bean['hook_module_name'];
				    $hook_desc = $hook_bean['hook_descrption'];
				    $beanName = BeanFactory::getBeanName($module_name);
					if($hook_bean['hook_audit'])
					{
						outright_make_hook_process_audit($beanName,$hook_desc,$event,'');
							
					}
					  
				}
		    }
		    if($outright_test_mode)
		    {
				if((stripos($out_bean_last_name,'test') !== false) || (stripos($out_bean_first_name,'test') !== false) || (stripos($outright_bean_name,'test') !== false))
				{
				
					$module_name = $hook_bean['hook_module_name'];
					$hook_desc = $hook_bean['hook_descrption'];
					$beanName = BeanFactory::getBeanName($module_name);
						if($hook_bean['hook_audit'])
						{
							 outright_make_hook_process_audit($beanName,$hook_desc,$event,'');
						}
				}
				else
				{
				     return false;
				}
		    }
			if(!$outright_test_mode)
			{
				$module_name = $hook_bean['hook_module_name'];
				$hook_desc = $hook_bean['hook_descrption'];
				$beanName = BeanFactory::getBeanName($module_name);
					if($hook_bean['hook_audit'])
					{
						outright_make_hook_process_audit($beanName,$hook_desc,$event,'');
							
					}
					  
			}
   		}
		function outright_make_hook_process_audit($beanName,$hook_desc,$event,$hook_code)
		{
			$hook_array = array("hook_bean_name"=>$beanName,"hook_event_name"=>$event,"hook_descrption"=>$hook_desc,"hook_code"=>$hook_code);
			outright_save('outr_OutrightHookAudit',$hook_array);
			
		}
		
		
		function outright_load_hook_suffix($func_name,$bean,$event)
		{
			$array = explode('_',$func_name);
			$first = array_shift($array);
   			$hook_data = outright_load_hook($first,$bean);
   			$hook_code = $hook_data['hook_file_code'];
   			$module_name = $hook_data['hook_module_name'];
   			$hook_desc = $hook_data['hook_descrption'];
   			$beanName = BeanFactory::getBeanName($module_name);
   			if($hook_data['hook_audit'])
   			{
				 outright_make_hook_process_audit($beanName,$hook_desc,$event,$hook_code);
			}
		}
function outright_custom_manisfet_genrator($package_name,$outright_path){
		$manifest = array (
		  'readme' => '',
		  'key' => '',
		  'author' => 'OutrightCRM',
		  'description' => 'Installs my files to the accounts module',
		  'icon' => '',
		  'is_uninstallable' => true,
		  'name' => '',
		  'published_date' => date('Y-m-d h:i:s'),
		  'type' => 'module',
		  'version' => '',
		  'remove_tables' => 'prompt',
		);
		$new_manifest = $manifest;
		$manifest_exist = file_exists('manifest.php') ? true : false;
		if($manifest_exist){
				include 'manifest.php';
				$new_manifest = $manifest;
			}
				global $new_installdefs,$skipArr;
				$new_installdefs = array();
				$new_installdefs = array('id' => '' , 'copy' => array() , 'vardefs' => array()  , 'custom_fields' => array() , 'logic_hooks' => array() , 'pre_execute' => array() , 'post_execute' => array() , 'pre_uninstall' => array() , 'post_uninstall' => array() );
				$skipArr = array('LICENSE.txt','manifest.php','outright_create_package.php','post_install.php','pull_custom_fields.php');
				$new_installdefs['id'] = $package_name.'_'.rand();	
						$new_manifest['name'] = $package_name;
						$new_manifest['key'] = $package_name;
				$packageName = $new_manifest['name'];
				$new_manifest['version'] = '1';
				outright_collect_all_file($outright_path);
				$new_array = array();	
				foreach($new_installdefs['copy'] as $key=>$value)
				{
					
					$value['from'] = str_replace($outright_path,'',$value['from']);
					$new_array [$key]['from'] = $value['from'];
					$value['to'] = str_replace($outright_path,'',$value['to']);
					$new_array [$key]['to'] = $value['to'];
					
				}
				 $new_installdefs['copy'] = $new_array;
				 file_put_contents($outright_path.'manifest.php',"<?php\n".'$manifest = '.var_export($new_manifest,true).";\n".'$installdefs = '.var_export($new_installdefs,true).';');
				 chmod('manifest.php',0777);
				 outright_create_zip_with_folder($package_name,$outright_path,$outright_custom_val='1');
			 
			}
?>
