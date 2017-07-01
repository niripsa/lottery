<?php
$sTicket = "kgt8ON7yVITDhtdwci0qeVXVa-fonM7Zhz4SfSKUSSQ-30PYq23JeW-XIw8GrraHf4v8lZEOPnkKDQ3Pf9dv6g";

$sAppid = "wxae24848adddda398";
$sTimestamp = time();
$sNonceStr = createNonceStr();
$sUrl =  "http://dev.fenhong/test_wechat.html";


$sPinjie = "jsapi_ticket=$sTicket&noncestr=$sNonceStr&timestamp=$sTimestamp&url=$sUrl";
$sSignature = sha1($sPinjie);
$aRet = array();
$aRet['appid'] = $sAppid;
$aRet['timestamp'] = $sTimestamp;
$aRet['nonceStr'] = $sNonceStr;
$aRet['signature'] = $sSignature;
echo json_encode($aRet);

function createNonceStr(){
	$strOption = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$strResult = "";
	for ($i=1; $i<=12; $i++) {
		$strResult .= substr($strOption, mt_rand(0, strlen($strOption)-1), 1);
	}

	return $strResult;
}