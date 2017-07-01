
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header-title lr10\">\r\n\t<b>短信模板配置</b><span style=\" padding-left:30px;\"></span>\r\n    \r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<form action=\"\" method=\"post\" id=\"myform\">\r\n<table width=\"100%\" class=\"lr10\">\r\n    <tr>\r\n    \t<td width=\"150\">用户短信验证短信模板：</td> \r\n   \t\t<td><textarea name=\"\" style=\" height:50px; width:450px\" class=\"input-text\">";
echo $temp["m_reg_temp"];
echo "</textarea>\r\n        <font color=\"red\">000000</font> 是发送的验证码！请不要超过75个字,超过按照2条短信发送</td>\r\n    </tr>\r\n    <tr>\r\n    \t<td width=\"150\">用户夺宝获奖短信模板：</td> \r\n   \t\t<td><textarea name=\"m_shop_temp\" style=\" height:50px; width:450px\" class=\"input-text\">";
echo $temp["m_shop_temp"];
echo "</textarea>\r\n        <font color=\"red\">00000000</font> 是发送的夺宝码！,请不要超过75个字,超过按照2条短信发送\r\n        </td>\r\n    </tr>\r\n      <tr>\r\n    \t<td width=\"100\"></td> \r\n   \t\t<td> <input type=\"submit\" value=\" 提交 \" name=\"dosubmit\" class=\"button\"></td>\r\n   \t </tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n</body>\r\n</html> ";

?>
