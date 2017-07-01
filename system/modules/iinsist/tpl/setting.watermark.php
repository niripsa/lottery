
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10 lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\">\r\n  <table width=\"100%\" cellspacing=\"0\" >\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\">开启水印:</td>\r\n\t\t\t<td>\r\n                <input type=\"hidden\" name=\"watermark_off\" id=\"watermark_off\">\r\n                <script language=\"javascript\">yg_close(\"0,1|关闭,开启\",\"txt\",\"watermark_off\",\"";
echo $web["watermark_off"];
echo "\");</script>\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n        <tr>\r\n\t\t\t<td width=\"120\">水印类型:</td>\r\n\t\t\t<td>\r\n                <input type=\"hidden\" id=\"watermark_type\" name=\"watermark_type\" callback=\"show_div\" value=\"";
echo $web["watermark_type"];
echo "\"  class=\"input-text\">\r\n                <script language=\"javascript\">yg_radio(";
echo $watermark_type;
echo ",\"watermark_type\",\"";
echo $web["watermark_type"];
echo "\");</script>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n        <tr class=\"watermark_text\">\r\n\t\t\t<td width=\"120\">水印文字:</td>\r\n\t\t\t<td>\r\n            <input type=\"text\" name=\"watermark_text[text]\" value=\"";
echo $web["watermark_text"]["text"];
echo "\"  class=\"input-text wid200\"><br/>\r\n            </td>\r\n\t\t</tr>\r\n        <tr class=\"watermark_text\">\r\n\t\t\t<td width=\"120\">文字颜色:</td>\r\n\t\t\t<td>\r\n            <input type=\"text\" name=\"watermark_text[color]\" value=\"";
echo $web["watermark_text"]["color"];
echo "\" id=\"set_color\"  class=\"input-text\">\r\n                <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/colorpicker.js\"></script>\r\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/colour.png\" width=\"15\" height=\"16\" onClick=\"colorpicker('title_colorpanel','set_color');\" style=\"cursor:hand\"/>\r\n            \t<span> 填写如: #ff0000 的颜色值</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr class=\"watermark_text\">\r\n\t\t\t<td width=\"120\">文字字号:</td>\r\n\t\t\t<td>\r\n            <input type=\"text\" name=\"watermark_text[size]\" value=\"";
echo $web["watermark_text"]["size"];
echo "\"  class=\"input-text\"><br/>\r\n            </td>\r\n\t\t</tr>\r\n\t\t<tr class=\"watermark_text\">\r\n\t\t\t<td width=\"120\">文字字体:</td>\r\n\t\t\t<td>\r\n            <input type=\"text\" name=\"watermark_text[font]\" value=\"";
echo $web["watermark_text"]["font"];
echo "\"  class=\"input-text\">\r\n\t\t\t\t字体文件的位置,请确定是否存在该文件\r\n\t\t\t<br/>\t\t\t\r\n            </td>\r\n\t\t</tr>\r\n\t\t\r\n        </div>\r\n\t\t<tr class=\"watermark_image\">\r\n\t\t\t<td width=\"120\">水印添加条件:</td>\r\n\t\t\t<td>\r\n            <input type=\"text\" name=\"watermark_condition[width]\" value=\"";
echo $web["watermark_condition"]["width"];
echo "\"  class=\"input-text\">图片宽度 单位像素(px) <br/><br/>\r\n            <input type=\"text\" name=\"watermark_condition[height]\" value=\"";
echo $web["watermark_condition"]["height"];
echo "\"  class=\"input-text\">图片高度 单位像素(px)\r\n            </td>\r\n\t\t</tr>\t \r\n\t<tr class=\"watermark_image\">\r\n\t\t\t<td width=\"120\">水印图片:</td>\r\n\t\t\t<td>    \r\n           \t<input type=\"text\" id=\"imagetext\" value=\"";
echo $web["watermark_image"];
echo "\" name=\"watermark_image\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','banner',1,'imagetext')\"\r\n             value=\"上传图片\"/>\t\t      \r\n            </td>           \r\n\t\t</tr>\t\r\n\t\t<tr class=\"watermark_image\">\r\n\t\t\t<td width=\"120\">水印透明度:</td>\r\n\t\t\t<td><input type=\"text\" name=\"watermark_apache\" value=\"";
echo $web["watermark_apache"];
echo "\"  class=\"input-text\"><span> 请设置为0-100之间的数字，0代表完全透明，100代表不透明</span></td>\r\n\t\t</tr>\t\r\n\t\t<tr class=\"watermark_image\">\r\n\t\t\t<td width=\"120\">JPEG 水印质量:</td>\r\n\t\t\t<td><input type=\"text\" name=\"watermark_good\" value=\"";
echo $web["watermark_good"];
echo "\"  class=\"input-text\"><span> 水印质量请设置为0-100之间的数字,决定 jpg 格式图片的质量</span></td>\r\n\t\t</tr>\t\t\r\n\t\t<tr class=\"watermark_image\">\t\t\t\r\n\t\t\t<td width=\"120\" height=\"120\">水印位置:</td>\t\t\t\r\n\t\t\t<td>\r\n\t\t\t<div style=\"width:400px;height:150px; background:#fff\">\r\n\t\t\t<div style=\"line-height:150px;float:left; margin-left:10px; height:150px; width:100px;\">\r\n\t\t\t\t<input type=\"radio\" id=\"sel_s\" name=\"watermark_position\" value=\"s\" ";

if ($web["watermark_position"] == "s") {
    echo "checked";
}

echo ">&nbsp;随机位置\r\n\t\t\t</div>\r\n\t\t\r\n\t\t\t\t\t<span class=\"span_1\" >\r\n\t\t\t\t\t\t<ul style=\"list-style:nome;\">\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_lt\" name=\"watermark_position\" value=\"lt\" ";

if ($web["watermark_position"] == "lt") {
    echo "checked";
}

echo ">&nbsp;顶部居左</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_lc\" name=\"watermark_position\" value=\"lc\" ";

if ($web["watermark_position"] == "lc") {
    echo "checked";
}

echo ">&nbsp;中部居左</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_lb\" name=\"watermark_position\" value=\"lb\" ";

if ($web["watermark_position"] == "lb") {
    echo "checked";
}

echo ">&nbsp;底部居左</li>\r\n\t\t\t\t\t\t</ul>\t\r\n\t\t\t\t\t</span>\t\r\n\t\t\t\t\t<span class=\"span_1\">\r\n\t\t\t\t\t\t<ul style=\"list-style:nome;\">\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_t\" name=\"watermark_position\" value=\"t\" ";

if ($web["watermark_position"] == "t") {
    echo "checked";
}

echo ">&nbsp;顶部居中</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_c\" name=\"watermark_position\" value=\"c\" ";

if ($web["watermark_position"] == "c") {
    echo "checked";
}

echo ">&nbsp;中部居中</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_b\" name=\"watermark_position\" value=\"b\" ";

if ($web["watermark_position"] == "b") {
    echo "checked";
}

echo ">&nbsp;底部居中</li>\r\n\t\t\t\t\t\t</ul>\t\r\n\t\t\t\t\t</span>\r\n\t\t\t\t\t<span class=\"span_1\">\r\n\t\t\t\t\t\t<ul style=\"list-style:nome;\">\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_rt\" name=\"watermark_position\" value=\"rt\" ";

if ($web["watermark_position"] == "rt") {
    echo "checked";
}

echo ">&nbsp;顶部居右</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_rc\" name=\"watermark_position\" value=\"rc\" ";

if ($web["watermark_position"] == "rc") {
    echo "checked";
}

echo ">&nbsp;中部居右</li>\r\n\t\t\t\t\t\t\t<li><input type=\"radio\" id=\"sel_rb\" name=\"watermark_position\" value=\"rb\" ";

if ($web["watermark_position"] == "rb") {
    echo "checked";
}

echo ">&nbsp;底部居右</li>\r\n\t\t\t\t\t\t</ul>\t\r\n\t\t\t\t\t</span>\t\r\n\t\t\t\t\t<div style=\"clear:both;\"></div>\r\n\t\t\t</div>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t\t\r\n\t\t<tr>\r\n        \t<td width=\"120\"></td>\r\n            <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\" 提交 \" ></td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n<div clear=\"clear:both;\"></div>\r\n</div><!--table-list end-->\r\n<span id=\"title_colorpanel\" style=\"position:absolute; left:568px; top:155px\" class=\"colorpanel\"></span>\r\n<script type=\"text/javascript\">\r\n    function set_color(color){\r\n        $('#set_color').val(color);\r\n    }\r\n    function show_div(){\r\n        var t=$(\"#watermark_type\").val();\r\n        if(t=='text'){\r\n            $(\".watermark_text\").show();\r\n            $(\".watermark_image\").hide();\r\n        }else{\r\n            $(\".watermark_text\").hide();\r\n            $(\".watermark_image\").show();\r\n        }\r\n    }\r\n    ";

if ($web["watermark_type"] == "text") {
    echo "        show_div();\r\n    ";
}

echo "</script>\r\n</body>\r\n</html> ";

?>
