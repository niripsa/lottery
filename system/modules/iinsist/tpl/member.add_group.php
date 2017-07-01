
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t<a href=\"";
echo WEB_PATH;
echo "/admin/member/add_group\">增加会员组</a>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n  \t <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">会员组名：</td>\r\n\t\t\t<td><input type=\"text\" name=\"name\" value=\"";
echo $member_group["name"];
echo "\" class=\"input-text\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">开始经验：</td>\r\n\t\t\t<td><input type=\"text\" name=\"jingyan_start\" value=\"";
echo $member_group["jingyan_start"];
echo "\" class=\"input-text\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">结束经验：</td>\r\n\t\t\t<td><input type=\"text\" name=\"jingyan_end\" value=\"";
echo $member_group["jingyan_end"];
echo "\" class=\"input-text\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n        \t<td width=\"120\" align=\"right\"></td>\r\n            <td>\t\t\r\n            <input type=\"submit\" class=\"button\" name=\"submit\" value=\"提交\" >\r\n            </td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n<script>\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
