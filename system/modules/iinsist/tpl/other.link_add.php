
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">\r\n<table width=\"100%\" class=\"lr10\">\r\n  \t <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">链接名称：</td>\r\n\t\t\t<td><input type=\"text\" name=\"name\" class=\"input-text\"></td>\r\n\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">链接地址：</td>\r\n\t\t\t<td><input type=\"text\" name=\"url\"  class=\"input-text\"></td>\r\n\t\t</tr>\r\n        <tr>\r\n        \t<td width=\"120\" align=\"right\">链接图片:</td>\r\n            <td>\r\n\t\t\t<div class=\"lf\"><input type=\"text\" id=\"imagetext\" name=\"logo\" value=\"\"  class=\"input-text lh20\"></div>\r\n              <div class=\"button lf\" onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','图片上传','banner',1,'imagetext')\">上传</div>\t\t\t  \r\n         \r\n            </td>\r\n        </tr>\r\n\t\t<tr>\r\n        \t<td width=\"120\" align=\"right\"></td>\r\n            <td><input type=\"submit\" class=\"button\" name=\"submit\"  value=\" 提交 \" ></td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
