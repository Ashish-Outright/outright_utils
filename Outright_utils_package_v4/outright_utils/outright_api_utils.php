<?php
function call_curl($method,$param,$url) {
	global $user_name;	
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$json = json_encode($param);
	$postArgs = array(
	    'method' => $method,
	    'input_type' => 'JSON',
	    'response_type' => 'JSON',
	    'rest_data' => $json,
	    );
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
	$response = curl_exec($curl);
	$result = json_decode($response);
	if ( !isset($result) ) {
	    die("Error: {$result->name} - {$result->description}\n.");
	}	
	return $result;
}

function nameValuePairToSimpleArray($array_data){
    $my_array=array();       
	foreach($array_data as $key=>$res_array){		
        $my_array[$res_array->name]=$res_array->value;		  
    }
    return $my_array;
}


/*****************************************
 * 
 *     @description : Rest API call 
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$url          : URL value                                                               
 *     @param     string --->$data         : data value                                                                
 *     @param     string --->$access_token : access_token value                                                                
 *     @param     string --->$method       : request method                                                                
 *     @return    obj|array|false          : Return's the obj|array on success or false on fail                                                               
 *     @since 1.0.0
 * 
 * ***************************************/

function doRESTCALL(  $url , $data ,$access_token,$method="GET") {

	$guid    = create_guid();
	$ch      = curl_init();
	$headers = array(
			"User-Agent: php-calEndar/1.0",         // SEnding a User-Agent header is a best practice.
			"Authorization: Bearer ".$access_token, // Always need our auth token!
			"Accept: application/json",             // Always accept JSON response.
			"client-request-id: ".$guid, // Stamp each new request with a new GUID.
			"return-client-request-id: true"			
			// Tell the server to include our request-id GUID in the response.
		  );
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
	$post_data = 'method=' . $method . '&input_type=JSON&response_type=JSON';
	//$json = getJSONobj();
	//$jsonEncodedData = json_encode($data);

	$post_data = $post_data . "&rest_data=" . $data;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result    = curl_exec($ch);
	
	curl_close($ch);
	$result = explode("\r\n\r\n", $result, 2);
	$response_data = json_decode($result[1]);
	echo '<pre>';
	print_r($response_data);
	echo '</pre>';
	$rest_session_id = $response_data->id  ;
	return $response_data ? $response_data:false;
}