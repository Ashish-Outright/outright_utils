<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

/*********************************************************************************

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $currentModule;
global $current_language;
global $current_user;
global $sugar_flavor;

global $sugar_config;
if (!is_admin($current_user) && !is_admin_for_any_module($current_user))
{
   sugar_die("Unauthorized access to administration.");
}
session_start();
$_SESSION['changeadmin'];
echo  '<h2>Administration</h2>';
if(empty($_SESSION['changeadmin']))
{
	echo '<span style="font-size:15px;"><ul style="margin-left:15px;"><li  class="list_headlink"><a href="'.$sugar_config[site_url].'/index.php?module=outr_admin_section&action=changeadmin">New Admin</a> </li>';
}else
{
	echo '<span style="font-size:15px;"><ul style="margin-left:15px;"><li  class="list_headlink"><a href="'.$sugar_config[site_url].'/index.php?module=outr_admin_section&action=changeadmin" >Classic Admin</a> </li>';
}
echo '<li class="list_headlink"><a href="'.$sugar_config[site_url].'/index.php?module=outr_admin_section&action=EditView&return_module=outr_admin_section&return_action=DetailView">Create New Admin Panel</a> </li><li class="list_headlink"><a href="'.$sugar_config[site_url].'/index.php?module=outr_Section_links&action=EditView&return_module=outr_Section_links&return_action=DetailView">Create New Admin Section</a> </li><li class="list_headlink"><a href="'.$sugar_config[site_url].'/index.php?entryPoint=merge_admin_with_sugar">Pull from Classic Admin</a></li></ul></span> ';
//get the module links..
session_start();
$_SESSION['changeadmin'];
require('modules/Administration/metadata/adminpaneldefs.php');

global $admin_group_header;  ///variable defined in the file above.

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
$db_name = $sugar_config['dbconfig']['db_name']; 
if(!empty($_SESSION['changeadmin']))
{
$outright_sql="SELECT * FROM outr_admin_section WHERE deleted='0' ORDER BY date_entered ASC";
$outright_fin_res = outright_run_sql($outright_sql);
$outright_i=1;
echo '<div style="background:;padding-bottom:25px;padding-top:25px;"><div class="row">';
 foreach ($outright_fin_res as $outright_values) {
	 echo '<div class="card panel expanded">
							<div class="card-head style-primary" data-toggle="collapse" data-target="#accordion'.$outright_values[id].'">
								<header>'.$outright_values["name"].'</header>
								<div class="tools">
									<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
								</div>
							</div>
							<div id="accordion'.$outright_values[id].'" class="collapse in">
								<div class="card-body"><div class="row">';
								$outright_admin_manager="SELECT * FROM `outr_admin_section_outr_section_links_1_c` t1 LEFT JOIN outr_section_links t2 ON t1.outr_admin_section_outr_section_links_1outr_section_links_idb=t2.id WHERE t1.outr_admin_section_outr_section_links_1outr_admin_section_ida='$outright_values[id]' AND t1.deleted='0' ORDER BY t2.date_entered ASC";
							 $outright_admin_manager_res = outright_run_sql($outright_admin_manager); 
							 $outright_j=1;  
							 foreach ($outright_admin_manager_res as $outright_val) {
								 if(!empty($outright_val["name"])){
								 if(!empty($outright_val["icon"])){$icon=$outright_val["icon"];}else{$icon='fa fa-cog';}
								 if(!empty($outright_val["description"])){$description=$outright_val["description"];}else{$description='Description about this link.';}
								 $description=substr($description,0,85);
								 echo '<div class="col-lg-3" style="min-height:75px;"> 
					<div class="">
						<div class="card-head" style="line-height:18px;"><a href="'.$sugar_config['site_url'].trim($outright_val["url"]).'" style="color:#0aa89e; font-weight:bold; display:inline-block; margin-top:7px; font-size: 13px;"><i class="'.$icon.'"></i> '.$outright_val["name"].'</a><br><p style="margin-top:7px;margin-left: 5px;font-style: italic;">'.$description.'</p></div>  
					</div>
				</div>';
				$outright_j++;
							 }
							 }
								echo '</div></div>
							</div>
						</div>';
    $outright_i++;
 }     
echo '</div>';
}
else
{
$j=0;
foreach ($admin_group_header as $key=>$values) {
    $module_index = array_keys($values[3]);
    $addedHeaderGroups = array();
    foreach ($module_index as $mod_key=>$mod_val) {
        if((!isset($addedHeaderGroups[$values[0]]))) {
            $admin_group_header_tab[]=$values;
            $group_header_value=get_form_header(translate($values[0],'Administration'),$values[1],$values[2]);
            $group[$j][0] = '<table cellpadding="0" cellspacing="0" width="100%" class="h3Row"><tr ><td width="20%" valign="bottom"><h3>' . translate($values[0]) . '</h3></td></tr>';
        	$addedHeaderGroups[$values[0]] = 1;
        	if (isset($values[4]))
    	       $group[$j][1] = '<tr><td style="padding-top: 3px; padding-bottom: 5px;">' . translate($values[4]) . '</td></tr></table>';
    	    else
    	       $group[$j][2] = '</tr></table>';
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

            foreach ($mod as $link_idx =>$admin_option) {
				if(!empty($GLOBALS['admin_access_control_links']) && in_array($link_idx, $GLOBALS['admin_access_control_links'])){
                    continue;
                }
                $colnum+=1;
                $header_image[$j][$i]= SugarThemeRegistry::current()->getImage($admin_option[0],'border="0" align="absmiddle"',null,null,'.gif',translate($admin_option[1],'Administration'));
                $url[$j][$i] = $admin_option[3];
                if(!empty($admin_option[5])) {
                	$onclick[$j][$i] = $admin_option[5];
                }
                $label = translate($admin_option[1],'Administration');
                if(!empty($admin_option['additional_label']))$label.= ' '. $admin_option['additional_label'];
                if(!empty($admin_option[4])){
                	$label = ' <font color="red">'. $label . '</font>';
                }

                $label_tab[$j][$i]= $label;
                $id_tab[$j][$i] = $link_idx;
                
                $description[$j][$i]= translate($admin_option[2],'Administration');

                if (($colnum % 2) == 0) {
                    $tab[$j][$i]= ($colnum % 2);
                }
                else {
                    $tab[$j][$i]= 10;
                }
                $i+=1;
            }

        	if (($colnum % 2) != 0) {
        	    $tab[$j][$i]= 10;
        	}
        $j+=1;
    }
  }
}
}
$sugar_smarty->assign("VALUES_3_TAB", $values_3_tab);
$sugar_smarty->assign("ADMIN_GROUP_HEADER", $admin_group_header_tab);
$sugar_smarty->assign("GROUP_HEADER", $group);
$sugar_smarty->assign("ITEM_HEADER_IMAGE", $header_image);
$sugar_smarty->assign("ITEM_URL", $url);
$sugar_smarty->assign("ITEM_ONCLICK", $onclick);
$sugar_smarty->assign("ITEM_HEADER_LABEL",$label_tab);
$sugar_smarty->assign("ITEM_DESCRIPTION", $description);
$sugar_smarty->assign("COLNUM", $tab);
$sugar_smarty->assign('ID_TAB', $id_tab);

echo $sugar_smarty->fetch('modules/Administration/index.tpl');
?>
<style>
.card {
    position: relative;
    margin-bottom: 24px;
    background-color: #ffffff;
    color: #313534;
    border-radius: 2px;
    -webkit-box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
}
.card .style-primary, .card.style-primary {
    background-color: #0aa89e;
    border-color: #0aa89e;
    color: #ffffff;
}
.card-head header {
    display: inline-block;
    padding: 11px 24px;
    vertical-align: middle;
    line-height: 17px;
    font-size: 20px;
}
.card-head {
    position: relative;
    min-height: 56px;
    vertical-align: middle;
    border-radius: 2px 2px 0 0;
}
.card-body {
    padding: 24px;
    position: relative;
}

.card-body:last-child {
    border-radius: 0 0 2px 2px;
}
.list_headlink
{
	list-style-type: initial;
}
</style>
