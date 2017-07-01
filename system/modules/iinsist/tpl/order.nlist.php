
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "\t<span class=\"lr10\"> </span><span class=\"lr10\"> </span>\r\n    <form action=\"\" method=\"post\" style=\"display:inline-block; \">\r\n\t<select name=\"paixu\">\r\n    \t<option value=\"time1\" ";

if ($paixu == "time1") {
    echo "selected";
}

echo "> 按购买时间倒序 </option>\r\n        <option value=\"time2\" ";

if ($paixu == "time2") {
    echo "selected";
}

echo "> 按购买时间正序 </option>\r\n        <option value=\"money1\" ";

if ($paixu == "money1") {
    echo "selected";
}

echo "> 按购买总价倒序 </option>\r\n        <option value=\"money2\" ";

if ($paixu == "money2") {
    echo "selected";
}

echo "> 按购买总价正序 </option>\r\n        \r\n\t</select>    \r\n\t<input type =\"submit\" value=\" 排序 \" name=\"paixu_submit\" class=\"button\"/>\r\n    </form>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n        \t<th align=\"center\">订单号1</th>\r\n            <th align=\"center\">商品标题</th>\r\n            <th align=\"center\">购买用户</th>           \r\n            <th align=\"center\">购买日期</th>\r\n            <th align=\"center\">发货状态</th>\r\n            <th align=\"center\">订单状态</th>\r\n            <th align=\"center\">管理</th>\r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n\t\t";
if (is_array($recordlist) && (0 < count($recordlist))) {
    foreach ($recordlist as $v ) {
        echo "            <tr>\r\n                <td align=\"center\">";
        echo $v["ocode"];
        echo " ";

        if ($v["code_tmp"]) {
            echo " <font color='#ff0000'>[多]</font>";
        }

        echo "</td>\r\n                <td align=\"center\">\r\n                <a  target=\"_blank\" href=\"";
        echo WEB_PATH . "/cgoods/" . $v["ogid"];
        echo "\">\r\n                ";
        echo _strcut($v["g_title"], 0, 25);
        echo "</a>\r\n                </td>              \r\n                 <td align=\"center\">";
        echo get_user_name($v["ouid"]);
        echo "</td>                \r\n                <td align=\"center\">";
        echo date("Y-m-d H:i:s", $v["otime"]);
        echo "</td>\r\n                 <td align=\"center\">";
        echo $v["ofstatus_txt"];
        echo "</td>\r\n                <td align=\"center\">";
        echo $v["status_txt"];
        echo "</td>\r\n                <td align=\"center\"><a href=\"";
        echo G_MODULE_PATH;
        echo "/order/ndetail/";
        echo $v["oid"];
        echo "\">详细</a></td>\r\n            </tr>\r\n            ";
    }
}

echo "  \t</tbody>\r\n</table>\r\n<div id=\"pages\"><ul>共";
echo $page;
echo "</ul></div>\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
