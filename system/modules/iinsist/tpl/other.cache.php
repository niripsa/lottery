
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\n.t-cache{padding:20px;}\r\n.t-cache li{ border-bottom:1px solid #eee; line-height:35px;}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"bk10\"></div>\r\n<div class=\"header-data lr10\">\r\n\t<b>清空缓存</b> \r\n</div>\r\n<div class=\"bk10\"></div>\r\n\r\n<div class=\"t-cache\">\r\n<form method=\"post\" action=\"\">\r\n\t<li><input name=\"cache[template]\" value=\"template\" type=\"checkbox\">&nbsp;模板缓存 </li>\r\n\t<li><input name=\"cache[file_cache]\" value=\"file_cache\" type=\"checkbox\">&nbsp;文件缓存 （一些升级文件缓存）</li>\r\n\t<li><input name=\"cache[logs_cache]\" value=\"logs_cache\" type=\"checkbox\">&nbsp;错误日志缓存</li>\r\n\t<li><input name=\"cache[admin_log_cache]\" value=\"admin_log_cache\" type=\"checkbox\">&nbsp;管理员日志</li>\r\n    <div class=\"bk10\"></div>\r\n\t<input name=\"dosubmit\" value=\"开始清除\" type=\"submit\" class=\"button\" style=\"width:90px; text-align:center\" />\r\n</form>\r\n</div>\r\n\r\n<div class=\"bk30\"></div>\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html>";

?>
