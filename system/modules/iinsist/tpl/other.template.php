
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header-title lr10\">\r\n\t<b>模板管理</b> \r\n    <span style=\"color:#f60; padding-left:30px;\">谨防模板被盗,建议修改html目录地址</span>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n\t\t<th width=\"100px\" align=\"center\">模板名称</th>\r\n        <th width=\"100px\" align=\"center\">模板目录</th>\r\n        <th width=\"100px\" align=\"center\">html目录</th>\r\n        <th width=\"100px\" align=\"center\">模板作者</th>\r\n        <th width=\"100px\" align=\"center\">类型</th>\r\n        <th width=\"100px\" align=\"center\">状态</th>\r\n        <th width=\"100px\" align=\"center\">操作</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t";

foreach ($templates as $temp ) {
    echo "\t\t<tr>\r\n\t\t\t<td width=\"100px\" align=\"center\">";
    echo $temp["name"];
    echo "</td>\r\n        \t<td width=\"100px\" align=\"center\">";
    echo $temp["dir"];
    echo "</td>\r\n       \t\t<td width=\"100px\" align=\"center\">";
    echo $temp["html"];
    echo "</td>\r\n       \t\t<td width=\"100px\" align=\"center\">";
    echo $temp["author"];
    echo "</td>\r\n            <td width=\"100px\" align=\"center\">";
    echo $temp["type"];
    echo "</td>\r\n            <td width=\"100px\" align=\"center\">\r\n            ";
    if (($temp["dir"] == $curr_pc) || ($temp["dir"] == $curr_mobile)) {
        echo "<font color=\"#0c0\">已启用</font>";
    }
    else {
        echo "            <a style=\"color:#F60\" href=\"";
        echo G_MODULE_PATH;
        echo "/other/off/";
        echo $temp["dir"];
        echo "\">\r\n\t\t\t";
        echo $temp["html"] == "未填写" ? "未添加" : "启用";
        echo "\t\t\t</a>\t\t\r\n            ";
    }

    echo "            </td>   \r\n              <td width=\"100px\" align=\"center\">\r\n\t\t\t\t [<a  href=\"";
    echo G_MODULE_PATH;
    echo "/other/template_edit/";
    echo $temp["dir"];
    echo "\">";
    echo $temp["html"] == "未填写" ? "添加" : "修改";
    echo "</a>]\r\n\t\t\t\t [<a  href=\"";
    echo WEB_PATH;
    echo "/skin=";
    echo $temp["dir"];
    echo "\" target=\"_blank\">预览</a>]\r\n            </td>             \t\r\n\t\t</tr>\r\n\t";
}

echo "  \t</tbody>\r\n</table>\r\n</div><!--table-list end-->\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
