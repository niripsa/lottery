
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r<html xmlns=\"http://www.w3.org/1999/xhtml\">\r<head>\r<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r<title></title>\r<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r<style>\rtbody tr{ line-height:30px; height:30px;}\r</style>\r</head>\r<body>\r<div class=\"header lr10\">\r\t";
echo headerment($ments);
echo "</div>\r<div class=\"bk10\"></div>\r<div class=\"table-list lr10\">\r<!--start-->\r  <table width=\"100%\" cellspacing=\"0\">\r    <thead>\r\t\t<tr>\r            <th width=\"100px\" align=\"center\">ID</th>\r            <th width=\"100px\" align=\"center\">会员组名：</th>\r            <th width=\"100px\" align=\"center\">经验值</th>\r            <th width=\"100px\" align=\"center\">管理</th>       \r\t\t</tr>\r    </thead>\r    <tbody>\r    \t";

foreach ($member_group as $v ) {
    echo "\t\t<tr>\r\t\t\t<td align=\"center\">";
    echo $v["groupid"];
    echo "</td>\r\t\t\t<td align=\"center\">";
    echo $v["name"];
    echo "</td>\t\r\t\t\t<td align=\"center\">";
    echo $v["jingyan_start"];
    echo "--";
    echo $v["jingyan_end"];
    echo "</td>\r            <td align=\"center\">\r            \t<a href=\"";
    echo G_MODULE_PATH;
    echo "/member/group_modify/";
    echo $v["groupid"];
    echo "\">修改</a>\r               <!--  <span class=\"span_fenge lr5\">|</span>\r               <a href=\"/* ";
    echo G_MODULE_PATH;
    echo " *//member/group_del//* ";
    echo $v["groupid"];
    echo " */\" onclick=\"return confirm('是否真的删除！');\">删除</a> -->\r            </td>            \t\r\t\t</tr>\r       ";
}

echo "\t\r  \t</tbody>\r\t\r</table>\r</div><!--table-list end-->\r<script>\r</script>\r</body>\r</html> ";

?>
