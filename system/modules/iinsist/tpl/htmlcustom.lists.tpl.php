
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title></title>\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\n<style>\ntd,th{text-indent:10px;}\nth{ border-left:1px solid #d5dfe8}\n.btn_a{\n\tborder: 1px solid #ccc;\n\tdisplay: inline-block;\n\tbackground: #eee;\n\tpadding: 2px 8px;\n\ttext-indent: 0px;\n}\n</style>\n</head>\n<body>\n<div class=\"header-title lr10\">\n\t<b>自定义标签</b>\n</div>\n<div class=\"bk10\"></div>\n<div class=\"table-list lr10\">\n <form action=\"";
echo G_MODULE_PATH;
echo "/htmlcustom/updatades\" method=\"post\" >\n\t<table width=\"100%\" cellspacing=\"0\">\n\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t<th align='left'>标签名</th>  \n\t\t\t\t\t<th align='left'>模板调用方式</th>  \t\t\t\t\t\n\t\t\t\t\t<th align='left'>标签说明</th>\n\t\t\t\t\t<th align='left'>操作</th>\n\t\t\t\t\t</tr>\n\t\t\t</thead>\n\t\t\t<tbody>\n\t\t\t\t";

foreach ($TAG as $key => $val ) {
    echo "\t\t\t\t <tr>\t\t\t\n\t\t\t\t\t<td align='left' width=\"30%\">";
    echo $key;
    echo "</td>\n\t\t\t\t\t<td align='left' width=\"30%\">{wc:block name=\"";
    echo $key;
    echo "\"}</td>\n\t\t\t\t\t<td align='left' width=\"30%\">";
    echo $val;
    echo "</td>\n\t\t\t\t\t<td align='left' width=\"10%\">\n\t\t\t\t\t\t<a class=\"btn_a\" href=\"";
    echo G_MODULE_PATH;
    echo "/htmlcustom/edit/";
    echo $key;
    echo "\">修改</a>\n\t\t\t\t\t\t<a class=\"btn_a\" onclick=\"del('";
    echo $key;
    echo "',this);\" href=\"javascript:;\">删除</a>\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t\t";
}

echo "\t\t\t</tbody>\n\t</table>\n\t\t<div class=\"btn_paixu\">        \t\t\n                <a href=\"";
echo G_MODULE_PATH;
echo "/htmlcustom/create\"><input type=\"button\"  class='button' value=\"新建标签\"/></a>\n\t\t</div>\n </form>\n</div><!--table-list end-->\n<script>\nfunction del(key,T){\n\tvar url = \"";
echo G_MODULE_PATH;
echo "/htmlcustom/\";\n\twindow.parent.layer.confirm(\"是否删除`\"+key+\"` 标签\",function(){\n\t\twindow.parent.message(\"点击了确定\"+key,8,2);\t\n\t\t$.post(url+\"del\",{\"key\":key},function(data){\n\t\t\twindow.parent.message(data,8,2);\n\t\t\tif(data == \"yes\"){\n\t\t\t\twindow.parent.message(\"删除成功\",2,2);\n\t\t\t\t$(T).parents(\"tr\").remove();\n\t\t\t}else{\n\t\t\t\twindow.parent.message(data,1,2);\n\t\t\t}\n\t\t})\n\t},\"是否确定\",function(){\n\t\t\n\t});\t\n}\n</script>\n</body>\n</html>";

?>
