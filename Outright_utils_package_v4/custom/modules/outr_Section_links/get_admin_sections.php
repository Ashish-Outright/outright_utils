<?php
if(!empty($_POST['record']))
{
$record=trim($_POST['record']);
$sql1 = "SELECT outr_admin_section_outr_section_links_1outr_admin_section_ida FROM outr_admin_section_outr_section_links_1_c WHERE outr_admin_section_outr_section_links_1outr_section_links_idb='$record'";
$fin_res1 = outright_run_sql($sql1);
$edit_id = $fin_res1[0][outr_admin_section_outr_section_links_1outr_admin_section_ida];
$sql = "SELECT id,name FROM outr_admin_section WHERE deleted='0'";
$fin_res = outright_run_sql($sql);
$outright_html_content='';
foreach($fin_res as $res)
{
	if($res[id]==$edit_id)
	{
		$outright_html_content.='<option value="'.$res[id].'" selected>'.$res[name].'</option>';
	}else
	{
		$outright_html_content.='<option value="'.$res[id].'">'.$res[name].'</option>';
	}
}
$out_data_array=array(
'status'=>'success',
'html'=>$outright_html_content
);
echo json_encode($out_data_array);
}else
{
$record_id=trim($_POST['record_id']);
$sql = "SELECT id,name FROM outr_admin_section WHERE deleted='0'";
$fin_res = outright_run_sql($sql);
$outright_html_content='';
foreach($fin_res as $res)
{
	if($res[id]==$record_id)
	{
		$outright_html_content.='<option value="'.$res[id].'" selected>'.$res[name].'</option>';
	}else
	{
		$outright_html_content.='<option value="'.$res[id].'">'.$res[name].'</option>';
	}
}
$out_data_array=array(
'status'=>'success',
'html'=>$outright_html_content
);
echo json_encode($out_data_array);
}
