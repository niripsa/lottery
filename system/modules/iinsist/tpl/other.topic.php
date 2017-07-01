
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\nfunction tiezi(id){\r\n\tif(confirm(\"您确认要删除帖子及其回复\")){\r\n\t\twindow.location.href=\"";
echo G_MODULE_PATH;
echo "/other/topic_del/\"+id;\r\n\t}\r\n}\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"mgr_table\">\r\n    <thead>\r\n\t<tr class=\"thead\" align=\"center\">\r\n\t\t<th>ID</th>\r\n\t\t<th>标题</th>\r\n\t\t<th>内容</th>\r\n\t\t<th>回复</th>\r\n\t\t<th>点击量</th>\r\n\t\t<th>置顶</th>\r\n\t\t<th>管理</th>\r\n\t</tr>\r\n    </thead>\r\n\t";
if (is_array($tiezi) && (0 < count($tiezi))) {
    foreach ($tiezi as $v ) {
        echo "\t<tr align=\"center\" class=\"mgr_tr\">\r\n\t\t<td height=\"30\">";
        echo $v["id"];
        echo "</td>\t\t\r\n\t\t<td>";
        echo _strcut($v["title"], 25);
        echo "</td>\r\n\t\t<td class=\"number\">";
        echo _strcut(filter_html($v["content"]), 25);
        echo "</td>\r\n\t\t<td>";
        echo $v["huifu"];
        echo "</td>\r\n\t\t<td>";
        echo $v["dianji"];
        echo "</td>\r\n\t\t<td>";

        if ($v["zhiding"] == "N") {
            echo "未置顶";
        }
        else {
            echo "置顶";
        }

        echo "</td>\r\n\t\t<td class=\"action\">\r\n\t\t<span>[<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/msg/";
        echo $v["id"];
        echo "\">查看回复</a>]</span>\r\n\t\t<span>[<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/topic_edit/";
        echo $v["id"];
        echo "\">修改</a>]</span>\r\n\t\t<span>[<a onclick=\"tiezi(";
        echo $v["id"];
        echo ")\" href=\"javascript:;\">删除</a>]</span>\r\n\t\t</td>\t\t\r\n\t</tr>\r\n\t";
    }
}

echo "</table>\r\n";

if ($num < $total) {
    echo " \r\n<div id=\"pages\"><ul><li>共 ";
    echo $total;
    echo " 条</li>";
    echo $page;
    echo "</ul></div>\r\n\r\n";
}

echo " \t\r\n</body>\r\n</html> \r\n";

?>
