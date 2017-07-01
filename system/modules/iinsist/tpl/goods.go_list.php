<?php
defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n.a_button{color:#fff; font-weight:bold; cursor:pointer; text-decoration:none;border:1px solid #090;padding:5px; background-color:#0c0}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"bk10\"></div>\r\n<div class=\"header-data lr10\">\r\n\t<b>商品&nbsp;&nbsp;&nbsp;ID:</b><b style=\"color:red;padding-left:10px;\">";
echo $ginfo["id"];
echo "</b><br/>\r\n    <b>商品期数:</b><b style=\"color:red; padding-left:10px;\">第(";
echo $ginfo["qishu"];
echo ")期, 最大期数";
echo $info["maxqishu"];
echo "期</b><br/>\r\n    <b>商品标题:</b><b style=\"color:red;padding-left:10px;\">";
echo $info["g_title"];
echo "</b><br/>\r\n    <b>商品信息:</b><b style=\"color:red;padding-left:10px;\">";
echo "总价格:" . sprintf("%.2f", $info["zongrenshu"] * $info["price"]) . "&nbsp;&nbsp;&nbsp;&nbsp;";
echo "单价:" . $info["price"] . "&nbsp;&nbsp;&nbsp;&nbsp;";
echo "总需人次:" . $ginfo["zongrenshu"] . "&nbsp;&nbsp;&nbsp;&nbsp;";
echo "参与人次:" . $ginfo["canyurenshu"] . "&nbsp;&nbsp;&nbsp;&nbsp;";
echo "剩余人次:" . $ginfo["shenyurenshu"] . "&nbsp;&nbsp;&nbsp;&nbsp;";
echo "</b><br/>\r\n    <b>开奖状态:</b><b style=\"color:red;padding-left:10px;\">\r\n\t\t";
if ( $ginfo["q_uid"] && ($ginfo["shenyurenshu"] == 0) ) {
    echo "已揭晓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "中奖人: " . get_user_name($ginfo["q_uid"], "username", "all") . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "中奖夺宝码: " . $ginfo["q_user_code"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "揭晓时间: " . date("Y-m-d H:i:s", $ginfo["q_end_time"]);
}
else {
    if (($ginfo["shenyurenshu"] != 0) && ($ginfo["xsjx_time"] == 0)) {
        echo "商品还在进行中...";
    }
    else {
        if (($ginfo["shenyurenshu"] == 0) && ($ginfo["xsjx_time"] != 0)) {
            echo "限时揭晓商品未到揭晓时间...";
        }
        else {
            if (($ginfo["shenyurenshu"] == 0) && ($ginfo["xsjx_time"] == 0)) {
                echo "<a href='" . G_MODULE_PATH . "/goods/goods_one_ok/" . $ginfo["id"] . "' class='a_button'>手动揭晓</a>";
            }
        }
    }
}

echo "    \r\n    </b>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\">\r\n     \t<thead>\r\n        \t\t<tr>\r\n                \t<th width=\"10%\">订单号</th>\r\n                    <th width=\"20%\">购买时间</th>        \r\n                    <th width=\"5%\">购买次数</th>    \r\n                    <th width=\"25%\">购买人</th>             \r\n                    <th width=\"20%\">来自</th>\r\n\t\t\t\t</tr>\r\n        </thead>\r\n        <tbody style=\"text-align:center\">\r\n        \t";

if ( $go_list ) {
    foreach ( $go_list as $go ) {
        echo "        \t<tr>\r\n            \t<td>";
        echo $go["ocode"];
        echo "</td>\r\n                <td>";
        echo date("Y-m-d H:i:s", $go["otime"]);
        echo "</td>\r\n                <td>";
        echo $go["onum"];
        echo "</td>\r\n                <td>";
        echo $go["ou_name"];
        echo "</td>\r\n                <td>";
        echo $go["oip"];
        echo "</td>\r\n            </tr>\r\n            ";
    }
}
else {
    echo "            <tr>\r\n            \t<td colspan=\"5\">还没有购买记录！</td>\r\n            </tr>\r\n            ";
}

echo "        </tbody>\r\n     </table>     \r\n\r\n\r\n</div><!--table_list end-->\r\n<div id=\"pages\"><ul><li>共 ";
echo $total;
echo " 条</li>";
echo $page;
echo "</ul></div>\r\n</body>\r\n</html> \r\n";

?>