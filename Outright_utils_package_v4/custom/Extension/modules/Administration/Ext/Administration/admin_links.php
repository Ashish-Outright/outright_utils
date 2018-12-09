<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    $admin_option_defs = array();
   
    $admin_group_header[] = array(
        //Section header label
        'Outright Tools',
        
        //$other_text parameter for get_form_header()
        '',
        
        //$show_help parameter for get_form_header()
        false,
        
        //Section links
        $admin_option_defs, 
        
        //Section description label
        'Tools and Utils provided by Outright Systems Pvt. Ltd.'
    );