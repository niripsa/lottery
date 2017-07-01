
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n            <th width=\"100px\" align=\"center\">支付名称</th>\r\n            <th width=\"100px\" align=\"center\">图片</th>\r\n\t\t\t<th width=\"100px\" align=\"center\">是否启用</th>\r\n            <th width=\"100px\" align=\"center\">适用平台</th>\r\n            <th width=\"100px\" align=\"center\">支付方式</th>\r\n            <th width=\"100px\" align=\"center\">管理</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";
if (is_array($paylist) && (0 < count($paylist))) {
    foreach ($paylist as $pay ) {
        echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">";
        echo $pay["pay_name"];
        echo "</td>\t\r\n\t\t\t<td align=\"center\"><img height=\"40px\" width=\"80px\" src=\"";
        echo G_UPLOAD_PATH . "/" . $pay["pay_thumb"];
        echo "\"/></td>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t";

        if ($pay["pay_start"] == 1) {
            echo "\t\t\t\t<font color='#0c0'>启用</font>\r\n\t\t\t\t";
        }
        else {
            echo "\t\t\t\t<font color='#ff0000'>关闭</font>\r\n\t\t\t\t";
        }

        echo "\t\t\t</td>\r\n            <td align=\"center\">\r\n                ";

        if (0 < strpos("#," . $pay["pay_mobile"] . ",", ",1,")) {
            echo " pc ";
        }

        echo "                ";

        if (0 < strpos("#" . $pay["pay_mobile"], ",")) {
            echo " , ";
        }

        echo "                ";

        if (0 < strpos("#," . $pay["pay_mobile"] . ",", ",2,")) {
            echo " 手机 ";
        }

        echo "            </td>\r\n            <td align=\"center\">\r\n\t\t\t\t";

        if ($pay["pay_type"] == 1) {
            echo "\t\t\t\t即时到账\r\n\t\t\t\t";
        }
        else if ($pay["pay_type"] == 2) {
            echo "\t\t\t\t担保交易\r\n\t\t\t\t";
        }
        else if ($pay["pay_type"] == 3) {
            echo "\t\t\t\t双接口\r\n\t\t\t\t";
        }

        echo "\t\t\t\r\n\t\t\t</td>\t\r\n\t\t\t<td align=\"center\"><a href=\"";
        echo G_MODULE_PATH;
        echo "/setting/pay_set/";
        echo $pay["pay_id"];
        echo "\">设置</a></td>\r\n\t\t</tr>\r\n\t\t";
    }
}

echo "  \t</tbody>\r\n</table>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\r\n</script>\r\n</body>\r\n</html> ";

?>
