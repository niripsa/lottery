
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:100px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n<div class=\"bk10\"></div>\r\n <form action=\"#\" id=\"form\" method=\"post\">\r\n <table width=\"100%\" class=\"table_form\">\r\n <tbody>\r\n  \t\t<tr>\r\n        <th>导航名称：</th>\r\n        <td><input type=\"text\" value=\"";
echo $info["name"];
echo "\" name=\"name\" id=\"catname\" class=\"input-text\">\r\n\t\t</td>\r\n      </tr>  \r\n\t\t<tr>\r\n\t  <th width=\"200\">类别：</th>\r\n        <td>\r\n\t\t <select name=\"type\" id=\"modelid\" onChange=\"\">\r\n         <option value=\"index\" ";

if ($info["type"] == "index") {
    echo " selected=\"\"";
}

echo ">≡ 头部导航 ≡</option>\r\n         <option value=\"foot\"  ";

if ($info["type"] == "foot") {
    echo " selected=\"\"";
}

echo "> ≡ 脚部导航 ≡</option>\r\n         </select>  \r\n      </tr>\t  \r\n      <tr>\r\n       <th>导航URL：</th>\r\n        <td><input type=\"text\" name=\"url\" value=\"";
echo $info["url"];
echo "\" style=\"width:350px;\" id=\"catname\" class=\"input-text\">\r\n        <span><font color=\"#0c0\" size=\"\">※ </font>前面默认会加上: ";
echo WEB_PATH;
echo "</span>\r\n\t\t</td>        \r\n      </tr>      \r\n\t\t<tr>\r\n        <th width=\"200\">是否显示：</th>\r\n        <td>\r\n\t\t <select name=\"status\" id=\"modelid\" onChange=\"\">\r\n         <option value=\"Y\" ";

if ($info["status"] == "Y") {
    echo " selected=\"\" ";
}

echo ">≡ 显示 ≡</option>\r\n         <option value=\"N\" ";

if ($info["status"] == "N") {
    echo " selected=\"\" ";
}

echo ">≡ 隐藏 ≡</option>\r\n         </select>\r\n      </tr>\r\n         <tr>\r\n       <th>排序：</th>\r\n        <td><input type=\"text\" name=\"sort\" value=\"";
echo $info["sort"];
echo "\" id=\"catname\" class=\"input-text\" onKeyUp=\t\t\t\t\t\"value=value.replace(/[^0-9]/g,'')\">\r\n        \t<span><font color=\"#0c0\" size=\"\">※ </font>数值越大越靠前显示</span>\r\n\t\t</td>\r\n      </tr>   \r\n        <tr>\r\n       <th></th>\r\n        <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\"保存\"></td>\r\n      </tr>     \r\n</tbody>\r\n</table>\r\n</form>\r\n</body>\r\n</html> ";

?>
