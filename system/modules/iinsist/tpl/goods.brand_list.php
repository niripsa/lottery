
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n <style>\r\n \tth{ border:0px solid #000;}\r\n\ttr{ line-height:30px;}\r\n </style>\r\n\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n            <tr>\r\n            <th width=\"90\">排序</th>\r\n            <th width=\"100\">id</th>\r\n            <th align='center'>品牌名称</th>\r\n            <th align='center'>所属栏目</th>\r\n\t\t\t<th align='center'>管理操作</th>\r\n            </tr>\r\n    </thead>\r\n    <form action=\"#\" method=\"post\" name=\"myform\">\r\n   <tbody>\r\n   \t";

foreach ($brands as $brand ) {
    echo "       <tr>\r\n         <td align='center'><input name='listorders[";
    echo $brand["id"];
    echo "]' type='text' size='3' value='";
    echo $brand["sort"];
    echo "' class='input-text-c'></td>\r\n         <td align='center'>";
    echo $brand["id"];
    echo "</td>\r\n         <td align='center'>";
    echo $brand["name"];
    echo "</td>\r\n         <td align='center'>\t\t\t\r\n\t\t\t";
    $cateids = explode(",", $brand["cateid"]);

    foreach ($cateids as $v ) {
        if (isset($categorys[$v]["name"])) {
            echo "[" . $categorys[$v]["name"] . "]　";
        }
        else {
            echo "<font color='red'>不存在</font>";
        }
    }

    echo "\t\t </td>\r\n\t\t <td align='center'>\r\n         \t[<a href=\"";
    echo G_ADMIN_PATH;
    echo "/goods/brand_edit/";
    echo $brand["id"];
    echo "\">修改</a>]\r\n            [<a class=\"bands_del_btn\" href=\"";
    echo G_ADMIN_PATH;
    echo "/goods/brand_del/";
    echo $brand["id"];
    echo "\">删除</a>]\r\n         </td>\r\n      </tr>\r\n     ";
}

echo "   </table>\r\n   </form>\r\n   <div class=\"btn_paixu\">\r\n  \t<div style=\"width:80px; text-align:center;\">\r\n          <input type=\"button\" class=\"button\" value=\" 排序 \"\r\n        onclick=\"myform.action='";
echo G_MODULE_PATH;
echo "/goods/brand_listorder/';myform.submit();\"/>\r\n    </div>\r\n  </div>\r\n <div id=\"pages\"><ul><li>共 ";
echo $total;
echo " 条</li>";
echo $page;
echo "</ul></div>\r\n\r\n</div><!--table-list end-->\r\n\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script>\r\n\t$(\"a.bands_del_btn\").click(function(){\r\n\t\t\r\n\t\tif(!confirm(\"确认删除？\")){\r\n\t\t\treturn false;\r\n\t\t\t\r\n\t\t}\r\n\t\t\r\n\t});\r\n</script>\r\n</body>\r\n</html> \r\n";

?>
