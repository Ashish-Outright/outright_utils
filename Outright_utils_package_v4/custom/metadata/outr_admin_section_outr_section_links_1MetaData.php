<?php
// created: 2018-08-09 06:59:15
$dictionary["outr_admin_section_outr_section_links_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'outr_admin_section_outr_section_links_1' => 
    array (
      'lhs_module' => 'outr_admin_section',
      'lhs_table' => 'outr_admin_section',
      'lhs_key' => 'id',
      'rhs_module' => 'outr_Section_links',
      'rhs_table' => 'outr_section_links',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'outr_admin_section_outr_section_links_1_c',
      'join_key_lhs' => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
      'join_key_rhs' => 'outr_admin_section_outr_section_links_1outr_section_links_idb',
    ),
  ),
  'table' => 'outr_admin_section_outr_section_links_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'outr_admin_section_outr_section_links_1outr_section_links_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'outr_admin_section_outr_section_links_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'outr_admin_section_outr_section_links_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'outr_admin_section_outr_section_links_1outr_admin_section_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'outr_admin_section_outr_section_links_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'outr_admin_section_outr_section_links_1outr_section_links_idb',
      ),
    ),
  ),
);