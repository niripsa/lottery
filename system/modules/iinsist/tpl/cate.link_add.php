
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n    <div class=\"ml10 ft14 fwb\">外部链接</div>\r\n\r\n\t<div name='con-tabv' class=\"con-tabv\">\r\n    <form id=\"form\" action=\"\" method=\"post\">\r\n \t<table width=\"100%\" class=\"table_form\">\r\n\t <tbody>\r\n         <tr>\r\n        <th width=\"200\">上级栏目：</th>\r\n        <td>\r\n\t\t<select name=\"info[parentid]\" id=\"parentid\"> \r\n        ";
echo $categoryshtml;
echo "        </select></td>\r\n      </tr>     \r\n      <tr>\r\n        <th>栏目名称：</th>\r\n        <td><input type=\"text\" name=\"info[name]\" class=\"input-text\" value=\"";
echo $cateinfo["name"];
echo "\">\r\n        \t<span><font color=\"#0c0\" size=\"\">※ </font>请输入栏目名称</span>\r\n\t\t</td>\r\n      </tr>\r\n\t<tr id=\"catdir_tr\">\r\n      <th>链接地址：</th>\r\n        <td><input type=\"text\" name=\"info[url]\" value=\"";
echo $cateinfo["url"];
echo "\" class=\"input-text wid300\">\r\n        <span><font color=\"#0c0\" size=\"\">※ </font>请输入链接地址,如：http://www.qq.com/</span> \r\n      </tr>      \r\n\t</table>\r\n    </div>\r\n\r\n</div>\r\n<!--table-list end-->\r\n\r\n   <div class=\"table-button lr10\">\r\n    \t  <input type=\"button\" value=\" 修改 \" onClick=\"checkform();\" class=\"button\">\r\n    \t</form>\r\n   </div>\r\n<script type=\"text/javascript\" src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/GgTab.js\"></script>\r\n<script type=\"text/javascript\">\r\nGg.Tab({i:\"li con-tabk ~on\",o:\"div con-tabv\",events:\"click\",num:1});\r\n\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n\r\n";

if ($catetype == "link") {
    echo "function checkform(){\r\n\tvar form=document.getElementById('form');\r\n\tvar error=null;\t\r\n\tif(form.elements[1].value==''){error='请输入栏目名称!';}\r\n\tif(form.elements[2].value==''){error='请输入链接地址!';}\r\n\tif(error!=null){window.parent.message(error,8,2);return false;}\r\n\tform.submit();\t\r\n}\r\n";
}

echo "</script>\r\n</body>\r\n</html> ";

?>
