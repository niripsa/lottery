
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n\t\t<th width=\"80px\">id</th>\r\n\t\t<th width=\"\" align=\"center\">幻灯图片</th>\r\n        <th width=\"15%\" align=\"center\">类型</th>\r\n\t\t<th width=\"15%\" align=\"center\">操作</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";

foreach ($lists as $v ) {
    echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">";
    echo $v["id"];
    echo "</td>\r\n\t\t\t<td align=\"center\"><img height=\"50px\" src=\"";
    echo G_UPLOAD_PATH;
    echo "/";
    echo $v["img"];
    echo "\"/></td>\r\n            <td align=\"center\">";

    if ($v["type"] == 1) {
        echo "pc";
    }
    else {
        echo "手机";
    }

    echo "</td>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t<a href=\"";
    echo G_ADMIN_PATH;
    echo "/other/slide_update/";
    echo $v["id"];
    echo "\">修改</a>\r\n\t\t\t\t<a href=\"";
    echo G_ADMIN_PATH;
    echo "/other/slide_del/";
    echo $v["id"];
    echo "\">删除</a>\r\n\t\t\t</td>\t\r\n\t\t</tr>\r\n\t\t";
}

echo "  \t</tbody>\r\n</table>\r\n\t<div class=\"btn_paixu\"></div>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
