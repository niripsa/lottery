
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n\t\t<th width=\"80px\">id</th>\r\n\t\t<th width=\"100px\" align=\"center\">广告位名称</th>\r\n\t\t<th width=\"100px\" align=\"center\">广告位宽度</th>\r\n\t\t<th width=\"\" align=\"center\">广告位高度</th>\r\n\t\t<th width=\"\" align=\"center\">广告位描述</th>\r\n\t\t<th width=\"30%\" align=\"center\">操作</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";
if (is_array($arr) && (0 < count($arr))) {
    foreach ($arr as $v ) {
        echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">";
        echo $v["aid"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo _strcut($v["title"], 12);
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $v["width"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $v["height"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo _strcut($v["des"], 16);
        echo "</td>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/ad_pos_edit/";
        echo $v["aid"];
        echo "\">修改</a>\r\n                <span class='span_fenge lr5'>|</span>\r\n\t\t\t\t<a href=\"";
        echo G_MODULE_PATH;
        echo "/other/ad_pos_del/";
        echo $v["aid"];
        echo "\" onClick=\"return confirm('是否真的删除！');\">删除</a>\r\n\t\t\t</td>\t\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n</div>\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
