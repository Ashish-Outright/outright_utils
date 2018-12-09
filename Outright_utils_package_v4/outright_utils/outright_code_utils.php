<?php

function outright_print_php_start_tag(){

echo '&#60;?php <br/><br/>';

}

function outright_print_php_end_tag(){

echo '<br/><br/> ?&#62; ';

}

function outright_get_string_between($target_string , $start_char, $end_char){

	$start_char_pos = strpos( $target_string , $start_char ) +1;
	$end_char_pos = strpos( $target_string , $end_char ) ;
	$end_char  = $end_char_pos - $start_char_pos;
	$result_string = substr($target_string ,$start_char_pos,$end_char);

	return $result_string;

}