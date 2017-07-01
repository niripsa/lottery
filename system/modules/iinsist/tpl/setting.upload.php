
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntr{height:40px;line-height:40px}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\">\r\n  <table width=\"100%\" cellspacing=\"0\">\t\r\n  \r\n\t\t<tr>\r\n\t\t\t<td width=\"200\" align=\"right\">允许上传图片大小：</td>\r\n\t\t\t<td><input type=\"text\" name=\"upimgsize\" value=\"";
echo $web["upimgsize"];
echo "\"  class=\"input-text\"> bit字节  1024字节=1K</td>\r\n\t\t\t\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"200\" align=\"right\">允许上传附件大小：</td>\r\n\t\t\t<td><input type=\"text\" name=\"upfilesize\" value=\"";
echo $web["upfilesize"];
echo "\"  class=\"input-text\"> bit字节  1024字节=1K</td>\r\n\t\t\t\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"200\" align=\"right\">允许上传图片类型：</td>\r\n\t\t\t<td><input type=\"text\" name=\"up_image_type\" value=\"";
echo $web["up_image_type"];
echo "\" class=\"input-text wid250\">\r\n\t\t\t<span class=\"span_meg\"> 允许上传的图片类型： 用 , 号分隔</span>\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"200\" align=\"right\">允许上传附件类型：</td>\r\n\t\t\t<td><input type=\"text\" name=\"up_soft_type\" value=\"";
echo $web["up_soft_type"];
echo "\" class=\"input-text wid250\">\r\n\t\t\t<span class=\"span_meg\"> 允许上传附件类型： 用 , 号分隔</span>\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"200\" align=\"right\">允许上传媒体类型： </td>\r\n\t\t\t<td><input type=\"text\" name=\"up_media_type\" value=\"";
echo $web["up_media_type"];
echo "\" class=\"input-text wid250\">\r\n\t\t\t<span class=\"span_meg\"> 允许上传媒体类型： 用 , 号分隔</span>\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n\r\n\t\t<tr>\t\t\r\n\t\t\t<td width=\"200\" align=\"right\"><h3>图片缩略图配置： </h3></td>\r\n\t\t\t<td>\r\n\t\t\t\t头像缩略图大小:　<input type=\"text\" name=\"thumb_user\" value=";
echo $web["thumb_user"];
echo " class=\"input-text wid80\">  \r\n\t\t\t\tjson格式,  例:{\"30\":\"20\"}表示 宽30px高20px  最多3组\r\n\t\t\t\t<br>\r\n\t\t\t\t商品缩略图大小:　<input type=\"text\" name=\"thumb_goods\" value=";
echo $web["thumb_goods"];
echo " class=\"input-text wid250\">\r\n\t\t\t\tjson格式,  例:{\"30\":\"20\"}表示 宽30px高20px  最多3组\r\n\t\t\t</td> \r\n\t\t</tr>\r\n\t\t\r\n\t\t<tr></tr>\t\r\n\t\t<tr>\r\n        \t<td width=\"200\" align=\"right\"></td>\r\n            <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\" 提交 \" ></td>\t\t\r\n\t\t</tr>\r\n\t\t</table>\r\n\t\t</form>\r\n\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html> ";

?>
