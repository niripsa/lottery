
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px; clear:} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header-title lr10\">\r\n\t<b>在线升级</b> <span class=\"lr10\"> <font color=\"red\">升级前请做好数据备份,如果你自己修改了模板文件请手动下载补丁更新：\r\n\t地址 http://download.yungoucms.com/yungou/newpatch/utf8/\r\n\t</font> </span>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"header-data lr10\">\r\n\t系统版本: ";
echo $v_version;
echo "\t<span class=\"lr10\">&nbsp;</span>\r\n\t升级时间: ";
echo $v_time;
echo "\t<span class=\"lr10\">&nbsp;</span>\r\n\t";

if ($stauts == -1) {
    echo "获取升级列表失败";
}

echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n            <th width=\"100px\" align=\"center\">可升级文件</th>\r\n\t\t\t<th width=\"100px\" align=\"center\">升级说明</th>\r\n\t\t\t<th width=\"100px\" align=\"center\">编码方式</th>\r\n            <th width=\"100px\" align=\"center\">状态</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";

if ($pathlist) {
    foreach ($pathlist as $v ) {
        echo "\t\t<tr>\r\n            <td width=\"100px\" align=\"center\">";
        echo $v;
        echo "</td>\r\n\t\t\t<td width=\"100px\" align=\"center\"><a href=\"http://download.yungoucms.com/yungou/newpatch/utf8/";
        echo $v;
        echo ".txt\" target=\"_blank\">查看说明</></td>\r\n\t\t\t<td align=\"center\">";
        echo G_CHARSET;
        echo "</td>\r\n            <td align=\"center\"><font color=\"#0c0\">可升级</font></td>\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n<div class=\"btn_paixu\">\r\n\t<form action=\"\" method=\"post\">\r\n\t";

if ($pathlist) {
    echo "\t<input type=\"submit\" class=\"button\" name=\"submit\" value=\" 开始升级 \" />\r\n\t";
}

echo "</div>\r\n</div><!--table-list end-->\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
