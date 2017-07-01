
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <style>\r\n \tth{ border:0px solid #000;}\r\n </style>\r\n \r\n\r\n <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n            <tr>\r\n            <th width=\"90\">排序</th>\r\n            <th align='center'>导航名称</th>\r\n            <th align='center' width=\"70\">显示隐藏</th>\r\n\t\t\t<th align='center'>类型</th>\r\n\t\t\t<th align='center'>管理操作</th>\r\n            </tr>\r\n\r\n    </thead>\r\n    <tbody>\r\n    \t";

foreach ($lists as $row ) {
    echo "          <tr>\r\n            <td align='center'><input name='listorders[1]' type='text' size='3' value='";
    echo $row["sort"];
    echo "' class='input-text-c'></td>\r\n            <td align='center'><a target=\"_blank\" href=\"";
    echo WEB_PATH . $row["url"];
    echo "\">";
    echo $row["name"];
    echo "</a></td>\r\n            <td align='center'>";
    echo $row["status"] == "Y" ? "显示" : "隐藏";
    echo "</td>\r\n\t\t\t<td align='center'>";

    if ($row["type"] == "index") {
        echo "头部导航";
    }
    else {
        echo "脚部导航";
    }

    echo "</td>\r\n\t\t\t<td align='center'>\r\n              <a href=\"";
    echo G_ADMIN_PATH;
    echo "/other/nav_edit/";
    echo $row["cid"];
    echo "\">修改</a><span class=\"span_fenge lr5\">|</span>\r\n              <a href=\"javascript:confirmurl('";
    echo $row[cid];
    echo "','确认要删除')\">删除</a>\r\n            </td>\r\n          </tr>\r\n          ";
}

echo "\t</tbody>\r\n  </table>\r\n  <div class=\"btn_paixu\">\r\n  \t<div style=\"width:80px; text-align:center;\">\r\n\t\t<input type=\"hidden\" class=\"button\" name=\"dosubmit\"  value=\"添加导航\">\r\n    </div>\r\n  </div>\r\n</div><!--table-list end-->\r\n<script>\r\n//window.parent.message(\"3443\",8,20);\r\n\r\nfunction confirmurl(id,str){\r\n\tif(confirm(str)){\r\n\t\tlocation.href='";
echo G_ADMIN_PATH;
echo "/other/nav_del/'+id;\r\n\t}\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
