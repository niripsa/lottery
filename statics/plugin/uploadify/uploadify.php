<?php
require_once (dirname(__FILE__) . "/../../inc/config.inc.php");
$action = (isset($action) ? $action : "");
$iswatermark = (isset($iswatermark) ? $iswatermark : "");
$timestamp = (isset($timestamp) ? $timestamp : "");
$verifyToken = md5("unique_salt" . $timestamp);
if (!empty($_FILES) && ($token == $verifyToken) && isset($sessionid)) {
	require_once (PHPMYWIND_DATA . "/httpfile/upload.class.php");
	$upload_info = uploadfile("Filedata", $iswatermark);

	if (!is_array($upload_info)) {
		echo "0," . $upload_info;
	}
	else {
		echo implode(",", $upload_info);
	}

	exit();
}

if ($action == "del") {
	$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$filename'");
	unlink(PHPMYWIND_ROOT . "/" . $filename);
	exit();
}

?>
