<?php
defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n<style>\r\nbody{ background-color:#fff}\r\n.textarea{width:400px;height:100px;}\r\n</style>\r\n</head>\r\n<body>\r\n<script language=\"JavaScript\">\r\n$(function(){\r\n\t$(\"form\").submit(function(){\r\n\t\tvar title=$(\"#title\").val();\r\n\t\tvar img=$(\"#img\").val();\r\n\t\tif(title.length<1){\r\n\t\t\talert(\"圈子名不能为空\");\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\treturn true;\r\n\t});\r\n})\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<form action=\"\" method=\"post\" id=\"myform\">\r\n<table width=\"100%\" >\r\n    <tr>\r\n    \t<td width=\"100\">标题：</td> \r\n   \t\t<td><input type=\"text\" name=\"title\" style=\"width:300px;\" class=\"input-text\" id=\"title\" value=\"";
echo $tiezi["title"];
echo "\"></td>\r\n    </tr>\t\r\n    <tr>\r\n    \t<td>内容：</td>\r\n    \t<td><textarea  name=\"content\" class=\"textarea\">";
echo $tiezi["content"];
echo "</textarea></td>\r\n\t</tr>\r\n\t<tr>\r\n    \t<td>置顶：</td>\r\n    \t<td>\r\n            <input type=\"hidden\" name=\"zhiding\" id=\"zhiding\" value=\"";
echo $tiezi["zhiding"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|不置顶,置顶\",\"txt\",\"zhiding\",\"";
echo $tiezi["zhiding"];
echo "\");</script>\r\n\t\t    <span class=\"ml10 lh30\">(帖子是否置顶)</span>\r\n        </td>\r\n\t</tr>\r\n    <tr>\r\n        <td>审核：</td>\r\n        <td>\r\n            <input type=\"hidden\" name=\"shenhe\" id=\"shenhe\" value=\"";
echo $tiezi["shenhe"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|不通过,通过\",\"txt\",\"shenhe\",\"";
echo $tiezi["shenhe"];
echo "\");</script>\r\n            <span class=\"ml10 lh30\">(帖子是否审核)</span>\r\n        </td>\r\n    </tr>\r\n</table>\r\n   \t<div class=\"bk15\"></div>\r\n\t<input class=\"button\" type=\"submit\" name=\"submit\" value=\"提交\" />\r\n</form>\r\n</div>\r\n</body>\r\n</html> \r\n";

