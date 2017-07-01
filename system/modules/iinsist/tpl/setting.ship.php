
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n            <th width=\"100px\" align=\"center\">快递公司</th>\r\n            <th width=\"100px\" align=\"center\">代码</th>\r\n            <th width=\"100px\" align=\"center\">保价费用</th>\r\n            <th width=\"100px\" align=\"center\">货到付款</th>\r\n            <th width=\"100px\" align=\"center\">是否启用</th>\r\n            <th width=\"100px\" align=\"center\">管理</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";
if (is_array($shiplist) && (0 < count($shiplist))) {
    foreach ($shiplist as $pay ) {
        echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">";
        echo $pay["ename"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $pay["ecode"];
        echo "</td>\r\n\t\t\t<td align=\"center\">\r\n                ";
        echo $pay["einsure"];
        echo "\t\t\t</td>\r\n            <td align=\"center\">\r\n                ";

        if ($pay["ecod"] == 1) {
            echo "                    <font color='#0c0'>支持</font>\r\n                ";
        }
        else {
            echo "                    <font color='#ff0000'>不支持</font>\r\n                ";
        }

        echo "            </td>\r\n            <td align=\"center\">\r\n                ";

        if ($pay["enabled"] == 1) {
            echo "                    <font color='#0c0'>启用</font>\r\n                ";
        }
        else {
            echo "                    <font color='#ff0000'>关闭</font>\r\n                ";
        }

        echo "\t\t\t</td>\t\r\n\t\t\t<td align=\"center\">\r\n                <a href=\"";
        echo G_MODULE_PATH;
        echo "/setting/ship_edit/";
        echo $pay["eid"];
        echo "\">修改</a>\r\n                <a href=\"";
        echo G_MODULE_PATH;
        echo "/setting/ship_del/";
        echo $pay["eid"];
        echo "\" onclick=\"return confirm('确认删除？');\">删除</a>\r\n            </td>\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n    <input type=\"button\" class=\"button\" name=\"dosubmit\" onclick=\"location.href='";
echo G_MODULE_PATH;
echo "/setting/ship_add/'\"  value=\" 添加 \" >\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\r\n</script>\r\n</body>\r\n</html> ";

?>
