
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar-blue.css\" type=\"text/css\"> \r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script> \r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">\r\n<table width=\"100%\" class=\"lr10\">\r\n  \t <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">广告名称：</td>\r\n\t\t\t<td><input type=\"text\" name=\"title\" class=\"input-text\" value=\"";
echo $ad["title"];
echo "\"></td>\r\n\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">广告类型：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<select name=\"type\" class=\"selecttype\">\r\n\t\t\t\t\t<option value=\"text\" ";

if ($ad["type"] == "text") {
    echo "selected";
}

echo ">文字</option>\r\n\t\t\t\t\t<option value=\"img\" ";

if ($ad["type"] == "img") {
    echo "selected";
}

echo ">图片</option>\r\n\t\t\t\t\t<option value=\"code\" ";

if ($ad["type"] == "code") {
    echo "selected";
}

echo ">代码</option>\r\n\t\t\t\t</select>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">广告位置：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<select name=\"aid\">\r\n\t\t\t\t";

foreach ($ad_pos as $v ) {
    echo "\t\t\t\t\t<option value=\"";
    echo $v["aid"];
    echo "\" ";

    if ($ad["aid"] == $v["aid"]) {
        echo "selected";
    }

    echo ">";
    echo _strcut($v["title"], 30);
    echo "</option>\r\n\t\t\t\t";
}

echo "\t\t\t\t</select>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">开始日期：</td>\r\n\t\t\t<td><input name=\"addtime\" type=\"text\" id=\"posttime\" class=\"input-text posttime\" value=\"";

if (!empty($ad["addtime"])) {
    echo date("Y-m-d", $ad["addtime"]);
}

echo "\"  readonly=\"readonly\" />\r\n\t\t\t\t<script type=\"text/javascript\">\r\n\t\t\t\tdate = new Date();\r\n\t\t\t\tCalendar.setup({\r\n\t\t\t\t\tinputField     :    \"posttime\",\r\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d\",\r\n\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t});\r\n\t\t\t\t</script>\r\n             </td>    \r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">结束日期：</td>\r\n\t\t\t<td><input name=\"endtime\" type=\"text\" id=\"posttimeend\" class=\"input-text posttime\" value=\"";

if (!empty($ad["endtime"])) {
    echo date("Y-m-d", $ad["endtime"]);
}

echo "\"  readonly=\"readonly\" />\r\n\t\t\t\t<script type=\"text/javascript\">\r\n\t\t\t\tdate = new Date();\r\n\t\t\t\tCalendar.setup({\r\n\t\t\t\t\tinputField     :    \"posttimeend\",\r\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d\",\r\n\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t});\r\n\t\t\t\t</script>\r\n             </td> \r\n\t\t</tr>\r\n        <tr class=\"adtext\">\r\n        \t<td width=\"120\" align=\"right\">广告位描述:</td>\r\n            <td>\r\n           \t<textarea name=\"text\" style=\"width:400px;height:100px;\">";
echo $ad["content"];
echo "</textarea>\r\n            </td>\r\n        </tr>\r\n\t\t<tr class=\"adimg\" style=\"display:none\">                \r\n        <td align=\"right\">广告位图片：</td>\r\n        <td>\r\n           \t<input type=\"text\" name=\"adphoto\" id=\"imagetext\" value=\"";
echo $ad["content"];
echo "\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','banner',1,'imagetext')\" \r\n             value=\"上传图片\"/>\r\n\t\t\t\r\n        </td>\r\n        </tr>\r\n\t\t<tr class=\"adcode\" style=\"display:none\">\r\n        \t<td width=\"120\" align=\"right\">广告位代码:</td>\r\n            <td>\r\n           \t<textarea name=\"code\" style=\"width:400px;height:100px;\">";
echo $ad["content"];
echo "</textarea>\r\n            </td>\r\n        </tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">是否开启：</td>\r\n\t\t\t<td>\r\n                <div class=\"select2-1 lf\">\r\n                    <div class=\"sel_off ";
if (($ad["checked"] == "0") || empty($ad["checked"])) {
    echo "active";
}

echo "\" rel='0'>OFF</div>\r\n                    <div class=\"sel_on ";

if ($ad["checked"] == "1") {
    echo "active";
}

echo "\" rel='1'>ON</div>\r\n                    <input type=\"hidden\" name=\"checked\" value=\"";
echo $ad["checked"] == "" ? "0" : $ad["checked"];
echo "\">\r\n                    <div class=\"cl\"></div>\r\n                </div>\r\n\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n        \t<td width=\"120\" align=\"right\"></td>\r\n            <td><input type=\"submit\" class=\"button\" name=\"submit\"  value=\" 提交 \" ></td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n<script type=\"text/javascript\">\r\n\t$(document).ready(function(){\r\n\t  $(\".selecttype\").change(function(){\r\n\t\tvar values=this.value;\r\n\t\tif(values==\"text\"){\r\n\t\t\t$(\".adtext\").show();\r\n\t\t\t$(\".adimg\").hide();\r\n\t\t\t$(\".adcode\").hide();\r\n\t\t}else if(values==\"img\"){\r\n\t\t\t$(\".adtext\").hide();\r\n\t\t\t$(\".adimg\").show();\r\n\t\t\t$(\".adcode\").hide();\r\n\t\t}else if(values==\"code\"){\r\n\t\t\t$(\".adtext\").hide();\r\n\t\t\t$(\".adimg\").hide();\r\n\t\t\t$(\".adcode\").show();\r\n\t\t}\r\n\t  });\r\n\t});\r\n</script>\r\n<script>\r\nfunction upImage(){\r\nreturn document.getElementById('imgfield').click();\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
