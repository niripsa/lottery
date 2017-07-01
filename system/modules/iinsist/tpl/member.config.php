
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n  \t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">不允许注册昵称:</td>\r\n\t\t\t<td><textarea name=\"nickname\" class=\"input-text\" style=\"width:400px; height:80px\">";
echo $user_config["nickname"];
echo "</textarea>\r\n             <span> 用户不可以使用的昵称,多个用,号分割</span></td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">注册类型:</td>\r\n\t\t\t<td>\r\n            \t        <label><input name=\"reg_email\" type=\"checkbox\" value=\"1\" ";
echo $user_config["reg_email"] ? "checked" : "";
echo " /> 邮箱注册 </label><br/>\r\n                        <label><input name=\"reg_mobile\" type=\"checkbox\" value=\"1\" ";
echo $user_config["reg_mobile"] ? "checked" : "";
echo " /> 手机注册 </label><br/>\r\n             </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">单IP每日注册:</td>\r\n\t\t\t<td>\r\n            \t  <input name=\"reg_num\" type=\"text\" class=\"input-text\" value=\"";
echo $user_config["reg_num"];
echo "\" /> 个<br/>\r\n             </td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n        \t<td width=\"120\" align=\"right\"></td>\r\n            <td>\t\t\r\n            <input type=\"submit\" class=\"button\" name=\"submit\" value=\"提交\" >\r\n            </td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n<script>\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
