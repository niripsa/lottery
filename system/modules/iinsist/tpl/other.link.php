
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n\t\t<th width=\"80px\">id</th>\r\n\t\t<th width=\"100px\" align=\"center\">链接名称</th>\r\n\t\t<th width=\"100px\" align=\"center\">链接类型</th>\r\n\t\t<th width=\"\" align=\"center\">链接图片</th>\r\n\t\t<th width=\"\" align=\"center\">链接文字</th>\r\n\t\t<th width=\"30%\" align=\"center\">操作</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";
if (is_array($links) && (0 < count($links))) {
    foreach ($links as $v ) {
        echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">";
        echo $v["id"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $v["name"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $v["type"] == 1 ? "文字" : "<font color='red'>图片</font>";
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo _strcut($v["logo"], 30);
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $v["url"];
        echo "</td>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t<a href=\"";
        echo G_ADMIN_PATH;
        echo "/other/link_edit/";
        echo $v["id"];
        echo "\">修改</a>\r\n                <span class='span_fenge lr5'>|</span>\r\n\t\t\t\t<a href=\"";
        echo G_ADMIN_PATH;
        echo "/other/link_del/";
        echo $v["id"];
        echo "\" onClick=\"return confirm('是否真的删除！');\">删除</a>\r\n\t\t\t</td>\t\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
