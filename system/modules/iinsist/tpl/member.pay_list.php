
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
echo "/calendar/calendar.js\"></script>\r\n<style>\r\ntbody tr{ line-height:30px; height:30px;} \r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n\r\n\r\n<div class=\"header-data lr10\">\r\n<div style=\"margin-bottom:5px;\">搜索的总消费金额：<span style=\"color:red;font-weight:bold;\">";
echo $summoeny;
echo "元</span></div>\r\n<form action=\"\" method=\"post\">\r\n 时间搜索: <input name=\"posttime1\" type=\"text\" id=\"posttime1\" class=\"input-text posttime\"  readonly=\"readonly\" value=\"";
echo !empty($posttime1) ? date("Y-m-d H:i:s", $posttime1) : "";
echo "\"/> -  \r\n \t\t  <input name=\"posttime2\" type=\"text\" id=\"posttime2\" class=\"input-text posttime\"  readonly=\"readonly\" value=\"";
echo !empty($posttime2) ? date("Y-m-d H:i:s", $posttime2) : "";
echo "\"/>\r\n\t\t\t<script type=\"text/javascript\">\r\n\t\t\t\t\tdate = new Date();\r\n\t\t\t\t\tCalendar.setup({\r\n\t\t\t\t\t\t\t\tinputField     :    \"posttime1\",\r\n\t\t\t\t\t\t\t\tifFormat       :    \"%Y-%m-%d %H:%M:%S\",\r\n\t\t\t\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t\t});\r\n\t\t\t\t\tCalendar.setup({\r\n\t\t\t\t\t\t\t\tinputField     :    \"posttime2\",\r\n\t\t\t\t\t\t\t\tifFormat       :    \"%Y-%m-%d %H:%M:%S\",\r\n\t\t\t\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t\t});\r\n\t\t\t\t\t\t\t\r\n\t\t\t</script>\r\n\r\n\t\t\t<select name=\"yonghu\">\r\n\t\t\t<option value=\"请选择用户类型\" \r\n\t\t\t";

if (isset($yonghu)) {
    echo $yonghu == "请选择用户类型" ? "selected" : "";
}

echo "\t\t\t>请选择用户类型</option>\r\n\t\t\t<option value=\"用户id\" \r\n\t\t\t";

if (isset($yonghu)) {
    echo $yonghu == "用户id" ? "selected" : "";
}

echo "\t\t\t>用户id</option>\r\n\t\t\t<option value=\"用户名称\" \r\n\t\t\t";

if (isset($yonghu)) {
    echo $yonghu == "用户名称" ? "selected" : "";
}

echo "\t\t\t>用户名称</option>\r\n\t\t\t<option value=\"用户邮箱\" \r\n\t\t\t";

if (isset($yonghu)) {
    echo $yonghu == "用户邮箱" ? "selected" : "";
}

echo "\t\t\t>用户邮箱</option>\r\n\t\t\t<option value=\"用户手机\" \r\n\t\t\t";

if (isset($yonghu)) {
    echo $yonghu == "用户手机" ? "selected" : "";
}

echo "\t\t\t>用户手机</option>\r\n\t\t\t</select>\r\n\t\t\t<input type=\"text\" name=\"yonghuzhi\" class=\"input-text wid100\" value=\"";
echo !empty($yonghuzhi) ? $yonghuzhi : "";
echo "\"/>\r\n\t\t\t<input class=\"button\" type=\"submit\" name=\"sososubmit\" value=\"搜索\">\r\n</form>\r\n</div>\r\n\r\n\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n            <th width=\"100px\" align=\"center\">用户名</th>\r\n            <th width=\"100px\" align=\"center\">商品名称</th>\r\n            <th width=\"100px\" align=\"center\">商品期数</th>\r\n            <th width=\"100px\" align=\"center\">夺宝次数</th>\r\n            <th width=\"100px\" align=\"center\">消费金额</th>\r\n            <th width=\"100px\" align=\"center\">时间</th>  \r\n\t\t</tr>\r\n    </thead>\r\n    <tbody>\r\n    \t";

if ($pay_list) {
    for ($j = 0; $j < count($pay_list); $j++) {
        echo "\t\t<tr>\r\n\t\t\t<td align=\"center\">\r\n\t\t\t\t";
        echo $members[$j];
        echo "\t\t\t</td>\r\n\t\t\t<td align=\"center\">";
        echo useri_title($pay_list[$j]["og_title"], "g_title");
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $pay_list[$j]["oqishu"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $pay_list[$j]["onum"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo $pay_list[$j]["omoney"];
        echo "</td>\r\n\t\t\t<td align=\"center\">";
        echo date("Y-m-d H:i:s", $pay_list[$j]["otime"]);
        echo "</td>\r\n\t\t</tr>\r\n       ";
    }
}

echo "\t\r\n  \t</tbody>\r\n\t\r\n</table>\r\n</div><!--table-list end-->\r\n<div id=\"pages\" style=\"margin:10px 10px\">\t\t\r\n\t<ul><li>共 ";
echo $total;
echo " 条</li>";
echo $page;
echo "</ul>\r\n</div>\r\n<script>\r\n</script>\r\n</body>\r\n</html> ";

?>
