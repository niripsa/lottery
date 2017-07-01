
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\nfunction quanzi(id){\r\n\tif(confirm(\"确定删除该圈子并删除圈子下的帖子\")){\r\n\t\twindow.location.href=\"";
echo G_MODULE_PATH;
echo "/other/club_del/quanzi/\"+id;\r\n\t}\r\n}\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\"> \r\n \t<thead>\r\n\t<tr align=\"center\">\r\n\t\t<th>ID</th>\r\n\t\t<th>圈子名</th>\r\n\t\t<th>管理员</th>\r\n\t\t<th>简介</th>\r\n\t\t<th>公告</th>\r\n\t\t<th>成员</th>\r\n\t\t<th>帖子数</th>\r\n        <th>可加入</th>\r\n\t\t<th>可发帖</th>\r\n        <th>可回复</th>\r\n\t\t<th>管理</th>\r\n\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t";
if (is_array($quanzi) && (0 < count($quanzi))) {
    foreach ($quanzi as $v ) {
        echo "\t<tr align=\"center\" class=\"mgr_tr\">\r\n\t\t<td height=\"30\">";
        echo $v["cid"];
        echo "</td>\r\n\t\t<td>";
        echo $v["title"];
        echo "</td>\r\n\t\t<td>";
        echo get_user_name($v["guanli"]);
        echo "</td>\r\n\t\t<td>";
        echo _strcut($v["jianjie"], 25);
        echo "</td>\r\n\t\t<td class=\"number\">";
        echo _strcut($v["gongao"], 25);
        echo "</td>\r\n\t\t<td>";
        echo $v["chengyuan"];
        echo "</td>\r\n\t\t<td>";
        echo $v["tiezi"];
        echo "</td>\r\n        <td>";
        echo $v["jiaru"] == "Y" ? "会员可加入" : "会员不可加入";
        echo "</td>\r\n\t\t<td>";
        echo $v["glfatie"] == "Y" ? "会员可发帖" : "会员不可发帖";
        echo "</td>\r\n        <td>";
        echo $v["huifu"] == "Y" ? "帖子可回复" : "帖子不可回复";
        echo "</td>\r\n\t\t<td>        \r\n        <span>[<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/topic/";
        echo $v["cid"];
        echo "\">查看帖子</a>]</span>\r\n\t\t<span>[<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/club_edit/";
        echo $v["cid"];
        echo "\">修改</a>]</span>\r\n\t\t<span>[<a onClick=\"quanzi(";
        echo $v["cid"];
        echo ")\" href=\"javascript:;\">删除</a>]</span>\r\n\t\t</td>\t\r\n\t</tr>\r\n\t";
    }
}

echo "    </tbody>\r\n</table>\r\n<div style=\"width:100%;height:30px;background-color: #eef3f7;\"></div>\r\n\r\n</div><!--table_list end-->\r\n\r\n</body>\r\n</html> \r\n";

?>
