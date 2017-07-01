
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n    <title></title>\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n    <style>\r\n        tbody tr{ line-height:30px; height:30px;}\r\n    </style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n    <!--start-->\r\n    <table width=\"100%\" cellspacing=\"0\">\r\n        <thead>\r\n        <tr>\r\n            <th width=\"80px\">id</th>\r\n            <th width=\"200px\" align=\"center\">广告名称</th>\r\n            <th width=\"200px\" align=\"center\">广告位置</th>\r\n            <th width=\"\" align=\"center\">广告类型</th>\r\n            <th width=\"\" align=\"center\">开始日期</th>\r\n            <th width=\"\" align=\"center\">结束日期</th>\r\n            <th width=\"30%\" align=\"center\">操作</th>\r\n        </tr>\r\n        </thead>\r\n        <tbody>\r\n        ";
if (is_array($arr) && (0 < count($arr))) {
    foreach ($arr as $v ) {
        echo "            <tr>\r\n                <td align=\"center\">";
        echo $v["id"];
        echo "</td>\r\n                <td align=\"center\">";
        echo _strcut($v["title"], 12);
        echo "</td>\r\n                <td align=\"center\">";
        echo _strcut($v["ad_pos"], 12);
        echo "</td>\r\n                <td align=\"center\">\r\n                    ";

        if ($v["type"] == "text") {
            echo "文字";
        }
        else if ($v["type"] == "img") {
            echo "图片";
        }
        else {
            echo "代码";
        }

        echo "                </td>\r\n                <td align=\"center\">";
        echo date("Y-m-d", $v["addtime"]);
        echo "</td>\r\n                <td align=\"center\">";
        echo date("Y-m-d", $v["endtime"]);
        echo "</td>\r\n                <td align=\"center\">\r\n                    <a href=\"";
        echo G_MODULE_PATH;
        echo "/other/ad_edit/";
        echo $v["id"];
        echo "\">修改</a>\r\n                    <span class='span_fenge lr5'>|</span>\r\n                    <a href=\"";
        echo G_MODULE_PATH;
        echo "/other/ad_del/";
        echo $v["id"];
        echo "\" onClick=\"return confirm('是否真的删除！');\">删除</a>\r\n                </td>\r\n            </tr>\r\n        ";
    }
}

echo "        </tbody>\r\n    </table>\r\n</div>\r\n</body>\r\n</html> ";

?>
