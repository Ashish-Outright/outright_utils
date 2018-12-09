<?php
$module_name = 'outr_Section_links';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'url',
            'label' => 'LBL_URL',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'icon',
            'label' => 'LBL_ICON',
          ),
          1 => 
          array (
            'name' => 'outr_admin_section_outr_section_links_1_name',
            'label' => 'LBL_OUTR_ADMIN_SECTION_OUTR_SECTION_LINKS_1_FROM_OUTR_ADMIN_SECTION_TITLE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
