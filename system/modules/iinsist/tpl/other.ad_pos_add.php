
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title></title>\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\n<style>\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\ntable td{ padding-left:10px;}\ninput.button{ display:inline-block}\n</style>\n</head>\n<body>\n<div class=\"header lr10\">\n\t";
echo headerment($ments);
echo "</div>\n<div class=\"bk10\"></div>\n<div class=\"table_form lr10\">\n<!--start-->\n<form name=\"myform\" action=\"\" method=\"post\">\n<table width=\"100%\" class=\"lr10\">\n  \t <tr>\n\t\t\t<td width=\"120\" align=\"right\">广告位名称：</td>\n\t\t\t<td><input type=\"text\" name=\"title\" value=\"";
echo $ad["title"];
echo "\" class=\"input-text\"></td>\n\t</tr>\n\t\t<tr>\n\t\t\t<td width=\"120\" align=\"right\">广告位宽度：</td>\n\t\t\t<td><input type=\"text\" name=\"width\" value=\"";
echo $ad["width"];
echo "\"  class=\"input-text\">(px)</td>\n\t\t</tr>\n\t\t<tr>\n\t\t\t<td width=\"120\" align=\"right\">广告位高度：</td>\n\t\t\t<td><input type=\"text\" name=\"height\" value=\"";
echo $ad["height"];
echo "\"  class=\"input-text\">(px)</td>\n\t\t</tr>\n        <tr>\n        \t<td width=\"120\" align=\"right\">广告位描述:</td>\n            <td>\n           \t<textarea name=\"des\" style=\"width:400px;height:100px;\">";
echo $ad["des"];
echo "</textarea>\n            </td>\n        </tr>\n\t\t<tr>\n\t\t\t<td width=\"120\" align=\"right\">广告位审核：</td>\n\t\t\t<td>\n                <div class=\"select2-1 lf\">\n                    <div class=\"sel_off ";
if (($ad["checked"] == "N") || empty($ad["checked"])) {
    echo "active";
}

echo "\" rel='N'>OFF</div>\n                    <div class=\"sel_on ";

if ($ad["checked"] == "Y") {
    echo "active";
}

echo "\" rel='Y'>ON</div>\n                    <input type=\"hidden\" name=\"checked\" value=\"";
echo $ad["checked"] == "" ? "N" : $ad["checked"];
echo "\">\n                    <div class=\"cl\"></div>\n                </div>\n            </td>\n\t\t</tr>\n\t\t<tr>\n        \t<td width=\"120\" align=\"right\"></td>\n            <td><input type=\"submit\" class=\"button\" name=\"submit\"  value=\" 提交 \" ></td>\n\t\t</tr>\n</table>\n</form>\n</div><!--table-list end-->\n\n<script>\nfunction upImage(){\n\treturn document.getElementById('imgfield').click();\n}\n</script>\n</body>\n</html> ";

?>
