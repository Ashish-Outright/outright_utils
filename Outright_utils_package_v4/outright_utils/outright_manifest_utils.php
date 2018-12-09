<?php


function recurse_copy($src,$dst) {
							$dir = opendir($src);
							@mkdir($dst,0777,true);
							while(false !== ( $file = readdir($dir)) ) {
							if (( $file != '.' ) && ( $file != '..' )) {
							if ( is_dir($src . '/' . $file) ) {
							recurse_copy($src . '/' . $file,$dst . '/' . $file);
							}
							else {
							copy($src . '/' . $file,$dst . '/' . $file);
							}
							}
							}
							closedir($dir);
					}
function recurse_copy2($src,$dst) {
							$dir = opendir($src);
							@mkdir($dst,0777,true);
							while(false !== ( $file = readdir($dir)) ) {
							if (( $file != '.' ) && ( $file != '..' )) {
							if ( is_dir($src . '/' . $file) && $file != 'metadata' && $file != 'views' && $file != 'Ext') {
							recurse_copy($src . '/' . $file,$dst . '/' . $file);
							}
							else {
							copy($src . '/' . $file,$dst . '/' . $file);
							}
							}
							}
							closedir($dir);
					}
function outright_manisfet_genrator($bean_name,$package_name,$outright_path){
		$manifest = array (
		  'acceptable_sugar_flavors' => 
		  array (
			0 => 'CE',
			1 => 'PRO',
			2 => 'CORP',
			3 => 'ENT',
			4 => 'ULT',
		  ),
		  'acceptable_sugar_versions' => 
		  array (
			'exact_matches' => 
			array (
			),
			'regex_matches' => 
			array (
			  0 => '(.*?)\\.(.*?)\\.(.*?)$',
			),
		  ),
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

		if(isset($bean_name)){
				global $new_installdefs,$skipArr;
				$new_installdefs = array();
				$new_installdefs = array('id' => '' , 'beans' => array() , 'image_dir' => '' , 'copy' => array() , 'language' => array() , 'relationships' => array() , 'connectors' => array()  , 'dashlets' => array() , 'layoutfields' => array() , 'layoutdefs' => array() , 'vardefs' => array()  , 'custom_fields' => array() , 'logic_hooks' => array() , 'pre_execute' => array() , 'post_execute' => array() , 'pre_uninstall' => array() , 'post_uninstall' => array() );
				$skipArr = array('LICENSE.txt','manifest.php','outright_create_package.php','post_install.php','pull_custom_fields.php');
				$new_installdefs['id'] = $package_name.'_'.rand();	
						$new_manifest['name'] = $package_name;
						$new_manifest['key'] = $package_name;
				$packageName = $new_manifest['name'];
				$new_manifest['version'] = '1';
			     foreach($bean_name as $key=>$value)
			        {
						$new_installdefs['beans'][] = array(
							  'module' => $value,
							  'class' => $value,
							  'path' => 'modules/'.$value.'/'.$value.'.php',
							  'tab' => true,
						);
			        }
		       
				outright_collect_all_file($outright_path);
				if($new_installdefs['image_dir']=='')
				{
				   unset($new_installdefs['image_dir']);
				}
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
				 outright_create_zip_with_folder($package_name,$outright_path,$outright_custom_val='2'); 
			}
		
}
function outright_collect_all_file($outright_path){
				global $new_installdefs,$skipArr;		
				$dirArr = glob($outright_path."*");
				foreach($dirArr as $key => $dirFile){
						if(is_file($dirFile)){
						$mydir = end(explode('/',$dirFile));
								if(!in_array($dirFile,$skipArr) && $mydir !='pull_custom_fields.php'){
										$new_installdefs['copy'][] = array(
											'from' => '<basepath>/'.$dirFile,
											'to' => $dirFile,
										);
									}
							}
				if(is_dir($dirFile) && $dirFile != 'icons' && $dirFile != 'language' && $dirFile != 'module_multiple_export/scripts'){
						$new_installdefs['copy'][] = array(
									'from' => '<basepath>/'.$dirFile,
									'to' => $dirFile,
								);
					}
						else if(is_dir($dirFile) && $dirFile == 'icons'){
								$new_installdefs['image_dir'] = '<basepath>/icons';
							}
						else if(is_dir($dirFile) && $dirFile == 'language'){
								$new_installdefs['language'][] = array (
									  'from' => '<basepath>/'.$dirFile,
									  'to_module' => 'application',
									  'language' => 'en_us',
									);
							}
					}
			}
function outright_create_zip_with_folder($zip_name,$outright_path,$out_val){
	           $outright_path = str_replace('/','',$outright_path);
	            $outright_path = realpath($outright_path);
				$zip = new ZipArchive();
				$zip->open($zip_name.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

				$files = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($outright_path),
					RecursiveIteratorIterator::LEAVES_ONLY
				);	
				
				foreach ($files as $name => $file)
				{	
					if (!$file->isDir() && !strpos($file,'outright_create_package.php'))
					{
						$filePath = $file->getRealPath();
						$relativePath = substr($filePath, strlen($outright_path) + 1);
						$zip->addFile($filePath, $relativePath);
					}
				}
				$zip->close();
				chmod($zip_name.'.zip',0777);
				set_time_limit(0); 
				if(ini_get('zlib.output_compression')) {
					ini_set('zlib.output_compression', 'Off');
				}
				$zipname = $zip_name.'.zip';
				ob_clean();
				ob_end_flush();
				set_time_limit(0);
				if (file_exists($zipname)){
					
					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename='.$zip_name.'.zip');
					header('Content-Length: ' . filesize($zipname));
					readfile($zipname);			
				}
				if($out_val=='2')
				{
				unlink($zipname);
			   }
			}
			