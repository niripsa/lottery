
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "    <a href=\"";
echo G_MODULE_PATH;
echo "/other/upload/\">根目录</a>\r\n    <span class=\"span_fenge lr5\">|</span>\r\n    <a href=\"javascript:history.go(-1);\">返回上一页</a>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"lr10\">当前位置：<a href=\"";
echo G_MODULE_PATH;
echo "/other/upload/\">根目录</a>\r\n    ";
$curr_path = G_MODULE_PATH . "/other/upload/";
echo "    ";
if (is_array($dir) && (1 < count($dir))) {
    foreach ($dir as $v ) {
        echo "        ";
        $curr_path .= $v . "-";
        echo "          > <a href=\"";
        echo $curr_path;
        echo "\">";
        echo $v;
        echo "</a>\r\n    ";
    }
}

echo "\r\n\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n\t\t<th width=\"100px\" align=\"center\">名称</th>\r\n        <th width=\"100px\" align=\"center\">类型</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\r\n\t";

foreach ($arr as $v ) {
    echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">\r\n                ";
    if (($v["type"] == "文件") && !in_array(strtolower(_get_file_type($v["name"])), array("jpg", "png", "gif"))) {
        echo "                    ";
        echo $v["name"];
        echo "                ";
    }
    else {
        echo "                    <a href=\"";
        echo $v["url"];
        echo "\">";
        echo $v["name"];
        echo "</a>\r\n                ";
    }

    echo "            </td>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t";
    echo $v["type"];
    echo "\t\t\t</td>                     \t\r\n\t\t</tr>\r\n\t";
}

echo "  \t</tbody>\r\n</table>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
