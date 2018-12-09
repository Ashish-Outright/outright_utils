
<?php
if(!defined("sugarEntry") || !sugarEntry) die("Not A Valid Entry Point");
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
 ?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php

			class outr_Section_linksViewEdit extends ViewEdit
			{
				public function __construct()
				{
					parent::ViewEdit();
					$this->useForSubpanel = true;
					$this->useModuleQuickCreateTemplate = true;
				}
				function display(){
					global $sugar_config;
					?>
					<script type="text/javascript">
						
						$(document).ready(function(){
							$("#outr_admin_section_outr_section_links_1_name").remove();
							$("#btn_outr_admin_section_outr_section_links_1_name").remove();
							$("#btn_clr_outr_admin_section_outr_section_links_1_name").remove();
							$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").after('<select id="outr_admin_section_outr_section_links_1outr_admin_section_ida" name="outr_admin_section_outr_section_links_1outr_admin_section_ida"></select>');
							$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").remove();
							var record_id = $("input[name*='record']").val();
							$.ajax({
								url:"<?php echo $sugar_config['site_url']?>/index.php?entryPoint=get_admin_sections",
								type:"post",
								data:{record_id:record_id},
								success:function(data)
								{
									//alert(data);
									obj = $.parseJSON(data);
									if(obj.status='success')
									{
											$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").append(obj.html);
									}
										//~ alert(data);
								}
								});
						});
						$(document).ready(function(){
							$("#outr_admin_section_outr_section_links_1_name").remove();
							$("#btn_outr_admin_section_outr_section_links_1_name").remove();
							$("#btn_clr_outr_admin_section_outr_section_links_1_name").remove();
							$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").after('<select id="outr_admin_section_outr_section_links_1outr_admin_section_ida" name="outr_admin_section_outr_section_links_1outr_admin_section_ida"></select>');
							$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").remove();
							var record = $("input[name*='record']").val();
							$.ajax({
								url:"<?php echo $sugar_config['site_url']?>/index.php?entryPoint=get_admin_sections",
								type:"post",
								data:{record:record},
								success:function(data)
								{
									obj = $.parseJSON(data);
									if(obj.status='success')
									{
											$("#outr_admin_section_outr_section_links_1outr_admin_section_ida").append(obj.html);
									}
										//~ alert(data);
								}
								});
						});
					</script>
					<?php
				parent::display();
					   
				}

			}
