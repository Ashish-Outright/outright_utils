<?php
function post_install(){
require_once("modules/Administration/QuickRepairAndRebuild.php");
global $moduleList;
$repair = new RepairAndClear();
$repair->repairAndClearAll(array("clearAll"),$moduleList, true,false);
$exit_on_cleanup = true;
sugar_cleanup(false);
}
