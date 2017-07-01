
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title></title>\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\n<style>\na.tag_bl{ margin-left:10px;line-height:30px; color:#666; border:1px dashed #ccc; padding:4px}\n</style>\n</head>\n<body>\n<div class=\"header-title lr10\">\n\t<b>新建标签</b> \t\n    <span style=\"color:#f60; padding-left:30px;\">调用方式: 在您的模板里面插入 <strong>{wc:block name=\"你的标签名\"}</strong> 即可调用</span>\n</div>\n<div class=\"bk10\"></div>\n<div class=\"bk10\"></div>\n<div class=\"table-form lr10\">\n\t<form name=\"form\" action=\"#\" method=\"post\">\n    ";

if (ROUTE_A == "create") {
    echo "    \t\n    \t<span style=\"color:#f60; padding-left:30px;\"><strong>标签名:　　</strong></span>\n\t\t<input type=\"text\" name=\"tag_name\" onkeyup=\"value=value.replace(/[\W]/g,'')\"  maxlength=15 class=\"input-text wid200\" />\n        <div class=\"bk10\"></div>\t\n\t\t\n\t\t<!--<span style=\"color:#f60; padding-left:30px;\"><strong>可用变量:</strong></span>\n\t\t\t<a class=\"tag_bl\" href=\"#\"><font color=\"red\">{tag:time}</font> 作用:当前时间</a>\t\t\n\t\t<div class=\"bk\"></div>\n\t\t-->\n\t\t<span style=\"color:#f60; padding-left:30px; line-height:30px;\"><strong>标签内容:　</strong></span>\n\t\t<textarea style=\"width:60%;height:250px;\" name=\"tag_val\">\n\t\t<!---------------------------------------------------------------------\n\t\t\n\t\t\t\t可以写入 HTML 代码.\t和 javascript 代码\t\t\t\n\t\t\t\t\n\t\t---------------------------------------------------------------------->\t\t\n\t\t</textarea>\n\t\t<div class=\"bk20\"></div>\n\t\t<span style=\"color:#f60; padding-left:30px; line-height:30px;\"><strong>标签说明:　</strong></span>\n\t\t<input type=\"text\" name=\"tag_des\" maxlength=30 class=\"input-text wid500\" />\n\t\t\n    ";
}

echo "\n\t\n\t<!----------------------------------------->\n\t<!----------------------------------------->\n\t\n\t\n    ";

if (!empty($TAG["key"])) {
    echo "\t\t<span style=\"color:#f60; padding-left:30px;\"><strong>标签名:　　</strong></span><input type=\"text\" value=\"";
    echo $TAG["key"];
    echo "\" name=\"tag_name\" onkeyup=\"value=value.replace(/[\W]/g,'')\"  maxlength=15 class=\"input-text wid200\" /> \n        <div class=\"bk20\"></div>\n\t\t<span style=\"color:#f60; padding-left:30px; line-height:30px;\"><strong>标签内容:　</strong></span><textarea style=\"width:60%;height:250px;\" name=\"tag_val\">";
    echo $TAG["content"];
    echo "</textarea>\n\t\t<div class=\"bk20\"></div>\n\t\t<span style=\"color:#f60; padding-left:30px; line-height:30px;\"><strong>标签说明:　</strong></span><input type=\"text\" value=\"";
    echo $TAG["des"];
    echo "\" name=\"tag_des\" maxlength=30 class=\"input-text wid500\" />\n\t\t\n    ";
}

echo "\t\n\t\n\t\n    <div class=\"btn_paixu\">        \t\t\n\t\t<span style=\"color:#f60; padding-left:30px; line-height:30px;\">　　　</span>\n\t\t<input type =\"submit\" name=\"submit\" class='button' value=\" 提 交 \" style=\"margin-left:10px\"/>\n\t</div>\n</form>\n\n\n<script>\n\n function gbcount(message,maxlen,id){\t\t\n\t\tif(!info[id]){\n\t\t\tinfo[id]=document.getElementById(id);\n\t\t}\t\t\t\n        var lenE = message.value.length;\n        var lenC = 0;\n        var enter = message.value.match(/\\r/g);\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\n        if (CJK != null) lenC += CJK.length;\n        if (enter != null) lenC -= enter.length;\t\t\n\t\tvar lenZ=lenE+lenC;\t\t\n\t\tif(lenZ > maxlen){\n\t\t\tinfo[id].innerHTML=''+0+'';\n\t\t\treturn false;\n\t\t}\n\t\tinfo[id].innerHTML=''+(maxlen-lenZ)+'';\n    }\n</script>\n</body>\n</html>";

?>
