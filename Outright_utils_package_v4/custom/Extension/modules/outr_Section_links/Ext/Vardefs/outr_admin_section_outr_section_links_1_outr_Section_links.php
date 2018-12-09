<?php
// created: 2018-08-09 06:59:15
$dictionary["outr_Section_links"]["fields"]["outr_admin_section_outr_section_links_1"] = array (
  'name' => 'outr_admin_section_outr_section_links_1',
  'type' => 'link',
  'relationship' => 'outr_admin_section_outr_section_links_1',
  'source' => 'non-db',
  'module' => 'outr_admin_section',
  'bean_name' => 'outr_admin_section',
  'vname' => 'LBL_OUTR_ADMIN_SECTION_OUTR_SECTION_LINKS_1_FROM_OUTR_ADMIN_SECTION_TITLE',
  'id_name' => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
);
$dictionary["outr_Section_links"]["fields"]["outr_admin_section_outr_section_links_1_name"] = array (
  'name' => 'outr_admin_section_outr_section_links_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OUTR_ADMIN_SECTION_OUTR_SECTION_LINKS_1_FROM_OUTR_ADMIN_SECTION_TITLE',
  'save' => true,
  'id_name' => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
  'link' => 'outr_admin_section_outr_section_links_1',
  'table' => 'outr_admin_section',
  'module' => 'outr_admin_section',
  'rname' => 'name',
);
$dictionary["outr_Section_links"]["fields"]["outr_admin_section_outr_section_links_1outr_admin_section_ida"] = array (
  'name' => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
  'type' => 'link',
  'relationship' => 'outr_admin_section_outr_section_links_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OUTR_ADMIN_SECTION_OUTR_SECTION_LINKS_1_FROM_OUTR_SECTION_LINKS_TITLE',
);
