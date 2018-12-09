<?php
 function pre_uninstall(){
global $db;
$sql = "DROP TABLE outr_admin_section_outr_section_links_1_c";
$db->query($sql);
}
