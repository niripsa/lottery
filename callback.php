<?php
#微信授权回调url

$sBaseUrl = $_REQUEST['real_url'];
if(!preg_match("#^http#", $sBaseUrl)){
	exit();
}
unset($_REQUEST['real_url']);

$sBaseUrl = str_replace("=", "", $sBaseUrl);

$sBaseUrl .= "&" . http_build_query($_REQUEST);

header("Location:$sBaseUrl");