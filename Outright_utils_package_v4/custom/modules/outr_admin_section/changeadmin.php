
<?php 
session_start();
if(!empty($_SESSION['changeadmin']))
{
	unset($_SESSION['changeadmin']);
}else
{
	$_SESSION['changeadmin']='checked';
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
