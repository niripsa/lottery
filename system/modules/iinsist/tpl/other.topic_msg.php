
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\nfunction hueifu(id){\r\n\tif(confirm(\"您确认要删除该条留言\")){\r\n\t\twindow.location.href=\"";
echo WEB_PATH . "/" . ROUTE_M;
echo "/other/msg_del/\"+id;\r\n\t}\r\n}\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"mgr_table\">\r\n\t<tr class=\"thead\" align=\"center\">\r\n\t\t<td width=\"5%\" height=\"30\">ID</td>\r\n\t\t<td width=\"40%\">回复内容</td>\r\n\t\t<td width=\"10%\">会员</td>\r\n\t\t<td width=\"10%\">管理</td>\r\n\t</tr>\r\n\t";
if (is_array($huifu) && (0 < count($huifu))) {
    foreach ($huifu as $v ) {
        echo "\t<tr align=\"center\" class=\"mgr_tr\">\r\n\t\t<td height=\"30\">";
        echo $v["id"];
        echo "</td>\r\n\t\t<td>";
        echo $v["content"];
        echo "</td>\r\n\t\t<td>";
        echo get_user_name($v["huiyuan"]);
        echo "</td>\r\n\t\t<td class=\"action\">\r\n\t\t\t<span>[<a onclick=\"hueifu(";
        echo $v["id"];
        echo ")\" href=\"javascript:;\">删除</a>]</span>\r\n\t\t</td>\t\t\r\n\t</tr>\r\n\t";
    }
}

echo "</table>\r\n\r\n";

if ($num < $total) {
    echo " \r\n\r\n<div id=\"pages\"><ul><li>共 ";
    echo $total;
    echo " 条</li>";
    echo $page;
    echo "</ul></div>\r\n\r\n";
}

echo " \t\r\n\r\n</body>\r\n</html> \r\n";

?>
