
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px; clear:}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header-title lr10\">\r\n\t<b>在线升级</b> <span class=\"lr10\"> <font color=\"red\">升级前请做好数据备份,如果你自己修改了模板文件请手动下载补丁更新\r\n\t</font> </span>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"header-data lr10\">\r\n\t系统版本: ";
echo System::load_sys_config("version", "version");
echo "\t<span class=\"lr10\">&nbsp;</span>\r\n\t升级时间: ";
echo System::load_sys_config("version", "release");
echo "\t<span class=\"lr10\">&nbsp;</span>\r\n\t";

if ($stauts == -1) {
    echo "获取升级列表失败";
}

echo "\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n            <th width=\"100px\" align=\"center\">版本号</th>\r\n\t\t\t<th width=\"100px\" align=\"center\">升级说明</th>\r\n\t\t\t<th width=\"100px\" align=\"center\">发布时间</th>\r\n            <th width=\"100px\" align=\"center\">补丁类型</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";

if ($pathlist) {
    foreach ($pathlist as $v ) {
        echo "\t\t<tr>\r\n            <td width=\"100px\" align=\"center\">";
        echo $v->version;
        echo " -> ";
        echo $v->release;
        echo "</td>\r\n\t\t\t<td width=\"100px\" align=\"center\">\r\n\t\t\t\t";
        echo $v->title;
        echo "  \r\n\t\t\t\t<a  target=\"_Blank\" href=\"";
        echo $geturl . "&release=" . $v->release;
        echo "\">[详细]</a>\t\t\t\r\n\t\t\t</td>\r\n\t\t\t<td align=\"center\">";
        echo $v->time;
        echo "</td>\r\n            <td align=\"center\">\r\n\t\t\t\t";

        switch ($v->status) {
        case 1:
            echo "<font color=\"#333\">优化程序</font>";
            break;

        case 2:
            echo "<font color=\"#00F\">bug修复</font>";
            break;

        case 3:
            echo "<font color=\"#f00\">漏洞修复</font>";
            break;
        }

        echo "\r\n\r\n            </td>\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n\r\n\r\n\t<div class=\"btn_paixu\">\r\n\t\t<form action=\"";
echo G_MODULE_PATH . "/upfile/web";
echo "\" method=\"post\">\r\n\t\t";

if ($pathlist) {
    echo "\t\t<input type=\"submit\" class=\"button\" name=\"submit\" value=\"一键升级 \" />\r\n\t\t　　<!--<input type=\"checkbox\" value=\"asd\" /><h4>不覆盖模板</h4>-->\r\n\t\t";
}

echo "\t\t</form>\r\n\t</div>\r\n\r\n</div><!--table-list end-->\r\n\r\n\r\n</body></html>";

?>
