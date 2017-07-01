<?php
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

if ( ! empty( $xml ) ) 
{
    $xml_arr = json_decode( json_encode( simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
    $_SERVER['QUERY_STRING'] = "plugin=1&api=".$xml_arr['api']."&action=".$xml_arr['action']."&data=".$xml_arr['wx']."&".$_SERVER['QUERY_STRING'];
    $_GET['plugin']          = 1;  
    $_GET['api']             = $xml_arr['api'];
    $_GET['action']          = $xml_arr['action'];
    $_GET['data']            = $xml_arr['wx'];
}
else
{
    $_SERVER['QUERY_STRING'] = "plugin=".$_GET['plugin']."&api=".$_GET['api']."&action=".$_GET['action']."&data=".$_GET['wx']."&".$_SERVER['QUERY_STRING'];
    $_GET['plugin']          = 1;  
    $_GET['api']             = $xml_arr['api'];
    $_GET['action']          = $xml_arr['action'];
    $_GET['data']            = $xml_arr['wx'];
}

include 'index.php';