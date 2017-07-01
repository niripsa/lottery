
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\nfunction shaidan(id){\r\n\tif(confirm(\"确定删除该晒单\")){\r\n\t\twindow.location.href=\"";
echo G_MODULE_PATH;
echo "/order/share_del/\"+id;\r\n\t}\r\n}\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\">\r\n \t<thead>\r\n\t<tr align=\"center\">\r\n\t\t<th width=\"5%\" height=\"30\">ID</th>\r\n\t\t<th width=\"15%\">晒单标题</th>\r\n\t\t<th width=\"10%\">缩略图</th>\r\n\t\t<th width=\"30%\">晒单内容</th>\r\n        <th width=\"10%\">晒单用户</th>\r\n\t\t<th width=\"10%\">羡慕嫉妒恨</th>\r\n\t\t<th width=\"10%\">评论</th>\r\n\t\t<th width=\"10%\">管理</th>\r\n\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t";
if (is_array($shaidan) && (0 < count($shaidan))) {
    foreach ($shaidan as $v ) {
        echo "\t<tr align=\"center\" class=\"mgr_tr\">\r\n\t\t<td height=\"30\">";
        echo $v["sd_id"];
        echo "</td>\r\n\t\t<td><a href=\"";
        echo WEB_PATH;
        echo "/index/share/detail/";
        echo $v["sd_id"];
        echo "\" target=\"_blank\">";
        echo _strcut($v["sd_title"], 30);
        echo "</a></td>\r\n\t\t<td><img height=\"30\" src=\"";
        echo G_UPLOAD_PATH . "/" . $v["sd_thumbs"];
        echo "\"></td>\r\n\t\t<td>";
        echo _strcut($v["sd_content"], 50);
        echo "</td>\r\n        <td>";
        echo get_user_name($v["sd_userid"]);
        echo "</td>\r\n\t\t<td>";
        echo $v["sd_zhan"];
        echo "</td>\r\n\t\t<td>";
        echo $v["sd_ping"];
        echo "</td>\r\n\t\t<td class=\"action\"><span>[<a onClick=\"shaidan(";
        echo $v["sd_id"];
        echo ")\" href=\"javascript:;\">删除</a>]</span></td>\t\t\r\n\t</tr>\r\n\t";
    }
}

echo "    </tbody>\r\n</table>\r\n";

if ($num < $total) {
    echo " \r\n\r\n<div id=\"pages\"><ul><li>共 ";
    echo $total;
    echo " 条</li>";
    echo $page;
    echo "</ul></div>\r\n\r\n";
}

echo " \t\r\n\r\n</div><!--table_list end-->\r\n\r\n</body>\r\n</html> \r\n";

?>
