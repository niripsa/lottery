
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style type=\"text/css\">\r\ntr{height:40px;line-height:40px}\r\n.dingdan_content{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}\r\n.dingdan_content li{ float:left;width:310px;}\r\n.dingdan_content_user{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}\r\n.dingdan_content_user li{ line-height:25px;}\r\n\r\n.api_b{width:80px; display:inline-block;font-weight:normal}\r\n.yun_ma{ word-break:break-all; width:200px; background:#fff; overflow:auto; height:100px; border:5px solid #09F; padding:5px;}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header-title lr10\">\r\n\t<b>订单详情</b>\r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n\t\t<div class=\"dingdan_content\">\r\n\t\t\t<h3 style=\"clear:both;display:block; line-height:30px;\">";
echo $goods["g_title"];
echo "</h3>\t\t\t\r\n\t\t\t<li><b class=\"api_b\">商品价格：</b>";
echo $goods["g_money"];
echo "</li>\t\t\t\r\n\t\t\t\r\n\t\t</div>\r\n\t\t<div class=\"bk10\"></div>\r\n\t\t<div class=\"dingdan_content_user\">\r\n\t\t\t<li><b class=\"api_b\">购买人ID：</b> ";
echo $user["uid"];
echo "</li>\r\n\t\t\t<li><b class=\"api_b\">购买人昵称：</b> ";
echo $user["username"];
echo "</li>\r\n\t\t\t<li><b class=\"api_b\">购买人邮箱：</b>";
echo $user["email"];
echo "</li>\t\t\r\n\t\t\t<li><b class=\"api_b\">购买人手机：</b>";
echo $user["mobile"];
echo "</li>\t\t\t\t\t\r\n\t\t\t<li><b class=\"api_b\">购买时间：</b>";
echo date("Y-m-d H:i:s", $record["otime"]);
echo "</li>\r\n            <li><b class=\"api_b\">收货信息：</b><div class=\"ml20\">";
if (is_array($user_add) && (0 < count($user_add))) {
    foreach ($user_add as $row ) {
        echo $row["sheng"] . " - " . $row["shi"] . " - " . $row["xian"] . " - " . $row["jiedao"];
        echo "&nbsp;&nbsp;&nbsp;邮编:" . $row["youbian"];
        echo "&nbsp;&nbsp;&nbsp;收货人:" . $row["shouhuoren"];
        echo "&nbsp;&nbsp;&nbsp;手机:" . $row["mobile"];
        echo "<br>";
    }
}
else {
    echo "该用户未填写收货信息,请自行联系买家！";
}

echo "            </div></li>\r\n\t\t</div>\t\t\t\r\n\t\t<div class=\"bk10\"></div>\r\n        ";

if (2 <= $record["ostatus"]) {
    echo "        \r\n\t\t<div class=\"dingdan_content_user\">\r\n\t\t\t<form action=\"\" method=\"post\">\r\n\t\t\t<input type=\"hidden\" name=\"oid\" value=\"";
    echo $record["oid"];
    echo "\"/>\r\n\t\t\t<li><b class=\"api_b\">当前状态:</b> <font color=\"#0c0\">";
    echo $record["status_txt"];
    echo "</font></li>\r\n\t\t\t<li><b class=\"api_b\">订单状态:</b><select name=\"ofstatus\">\r\n\t\t\t\t\t\t\t\t\t\t\t<option value=\"1\" ";

    if ($record["ofstatus"] == 1) {
        echo "selected";
    }

    echo ">未发货</option>\r\n\t\t\t\t\t\t\t\t\t\t\t<option value=\"2\" ";

    if ($record["ofstatus"] == 2) {
        echo "selected";
    }

    echo ">已发货</option>\r\n\t\t\t\t\t\t\t\t\t\t\t<option value=\"3\" ";

    if ($record["ofstatus"] == 3) {
        echo "selected";
    }

    echo ">已完成</option>\r\n\t\t\t\t\t\t\t\t\t\t\t<option value=\"-1\" ";

    if ($record["ofstatus"] == -1) {
        echo "selected";
    }

    echo ">已作废</option>\r\n\t\t\t\t\t\t\t\t\t\t  </select>\r\n\t\t\t</li>\r\n\t\t\t<li><b class=\"api_b\">物流公司:</b><select name=\"eid\">\r\n                                            ";
    if (is_array($ems) && (0 < count($ems))) {
        foreach ($ems as $row ) {
            echo "                                                <option value=\"";
            echo $row["eid"];
            echo "\" ";

            if ($row["eid"] == $ship["eid"]) {
                echo "selected";
            }

            echo ">";
            echo $row["ename"];
            echo "</option>\r\n                                            ";
        }
    }

    echo "\t\t\t                            </select>\r\n            </li>\r\n\t\t\t<li><b class=\"api_b\">快递单号:</b><input type=\"text\" name=\"ecode\" value=\"";
    echo $ship["ecode"];
    echo "\" class=\"input-text wid150\"> 填写物流公司快递单号</li>\r\n\t\t\t<li><b class=\"api_b\">快递运费:</b><input type=\"text\" name=\"emoney\" value=\"";
    echo $ship["emoney"];
    echo "\"  class=\"input-text wid150\"> 元 </li>\r\n\t\t\t\r\n\t\t\t<li><input type=\"submit\" class=\"button\" value=\"  更新  \" name=\"submit\" /></li>\t\t\r\n\t\t\t</form>\r\n\t\t</div>\r\n        ";
}

echo "</div><!--table-list end-->\r\n\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html> ";

?>
