
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<form action=\"#\" method=\"post\">\r\n<table width=\"100%\" cellspacing=\"0\">\r\n<tr>\r\n\t<td>\r\n    \t<input type=\"radio\" name=\"bank_type\" value=\"tenpay\" ";

if ($bank == "tenpay") {
    echo "checked";
}

echo "> 财付通\r\n        <span class=\"lr10\"> | </span>\r\n        <input type=\"radio\" name=\"bank_type\" value=\"yeepay\" ";

if ($bank == "yeepay") {
    echo "checked";
}

echo "> 易宝支付\r\n\t\t<span class=\"lr10\"> | </span>\r\n\t\t<input type=\"radio\" name=\"bank_type\" value=\"cbpay\" ";

if ($bank == "cbpay") {
    echo "checked";
}

echo "> 网银在线\t\r\n    </td>\r\n</tr>\r\n<tr><td> <input type=\"submit\" value=\" 提交 \" class=\"button\" name=\"dosubmit\"></td></tr>\r\n</table>\r\n</form>\r\n</div>\r\n<script>\r\n\r\n</script>\r\n</body>\r\n</html> ";

?>
