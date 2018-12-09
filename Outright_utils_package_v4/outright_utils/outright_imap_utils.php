<?php

function outright_check_imap($url,$user,$pass){

$mbox = imap_open("{mail.safetylabs.org:143/service=imap/notls/novalidate-cert}}", "user_name","password");
//$mbox = imap_open("{imap.safetylabs.com:143/notls}INBOX", "test.safetylabs","test12345");
imap_createmailbox($mbox,  "{mail.safetylabs.org:143/service=imap/notls/novalidate-cert}crm_bounce");
echo '<pre>';
print_r($mbox);
echo '</pre>';
$list = imap_list($mbox, "{mail.safetylabs.org:143}", "*");
if (is_array($list)) {
    foreach ($list as $val) {
		echo $cnt++;
        echo imap_utf7_decode($val) . "\n";
    }
} else {
    echo "imap_list failed: " . imap_last_error() . "\n";
}

imap_close($mbox);


}