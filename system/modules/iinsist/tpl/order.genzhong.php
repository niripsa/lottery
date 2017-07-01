
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "\t<span class=\"lr10\"> </span><span class=\"lr10\"> </span>\r\n    <form action=\"\" method=\"post\" style=\"display:inline-block; \">\r\n\t<select name=\"paixu\">\r\n    \t<option value=\"time1\"> 按购买时间倒序 </option>\r\n        <option value=\"time2\"> 按购买时间正序 </option>\r\n\t\t<option value=\"num1\"> 按购买次数倒序 </option>\r\n        <option value=\"num2\"> 按购买次数正序 </option>\r\n        <option value=\"money1\"> 按购买总价倒序 </option>\r\n        <option value=\"money2\"> 按购买总价正序 </option>\r\n        \r\n\t</select>    \r\n\t<input type =\"submit\" value=\" 排序 \" name=\"paixu_submit\" class=\"button\"/>\r\n    </form>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"lr10\">\r\n<iframe name=\"kuaidi100\" src=\"http://www.kuaidi100.com/frame/910.html\" width=\"910\" height=\"700\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" vspace=\"0\" frameborder=\"0\" scrolling=\"no\"></iframe>\t\r\n</div>\r\n</body>\r\n</html> ";

?>
