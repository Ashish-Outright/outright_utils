<?php
$popupMeta = array (
    'moduleMain' => 'outr_Section_links',
    'varName' => 'outr_Section_links',
    'orderBy' => 'outr_section_links.name',
    'whereClauses' => array (
  'name' => 'outr_section_links.name',
  'url' => 'outr_section_links.url',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'url',
),
    'searchdefs' => array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'url' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_URL',
    'width' => '10%',
    'name' => 'url',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'URL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_URL',
    'width' => '10%',
    'default' => true,
  ),
),
);
