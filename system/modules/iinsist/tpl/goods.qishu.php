
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\nfunction qishu(id){\r\n\tif(confirm(\"确定删除该晒单\")){\r\n\t\twindow.location.href=\"";
echo G_MODULE_PATH;
echo "/goods/qishu_del/qishu/\"+id;\r\n\t}\r\n}\r\n</script>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\">\r\n     \t<thead>\r\n        \t\t<tr>\r\n                    <th width=\"5%\">ID</th>        \r\n                    <th width=\"20%\">商品标题</th>    \r\n                    <th width=\"4%\">所属栏目</th>             \r\n                    <th width=\"10%\">已参与/总需</th>\r\n                    <th width=\"5%\">单价/元</th>\r\n                    <th width=\"5%\">期数</th>\r\n                    <th width=\"5%\">人气</th>\r\n                    <th width=\"5%\">限时</th>\r\n                    <th width=\"10%\">揭晓状态</th>\r\n                    <th width=\"15%\">管理</th>\r\n\t\t\t\t</tr>\r\n        </thead>\r\n        <tbody style=\"text-align:center\">\t\t\t\t\r\n        \t";

foreach ($qishu as $v ) {
    echo "            <tr>\r\n                <td>";
    echo $v["id"];
    echo "</td>\r\n                <td>第(";
    echo $v["qishu"];
    echo ")期 <span style=\"";
    echo $v["title_style"];
    echo "\">\r\n                <a target=\"_blank\" href=\"";
    echo WEB_PATH;
    echo "/cgoods/";
    echo $v["id"];
    echo "\">";
    echo _strcut($info["g_title"], 30);
    echo "</a></span></td>\r\n                <td>";
    echo $v["cate_name"];
    echo "</td>\r\n                <td><font color=\"#ff0000\">";
    echo $v["canyurenshu"];
    echo "</font>/";
    echo $v["zongrenshu"];
    echo "</td>\r\n                <td>";
    echo $v["price"];
    echo "</td>\r\n                <td>";
    echo $v["qishu"];
    echo "/";
    echo $v["maxqishu"];
    echo "</td>\r\n                <td>";
    if (($info["g_style"] == 1) || ($info["g_style"] == 3)) {
        echo "<font color=\"#ff0000\">人气</font>";
    }
    else {
        echo "未设置";
    }

    echo "</td>\r\n                <td>";

    if ($v["xsjx_time"]) {
        echo "<font color=\"#ff0000\">限时</font>";
    }
    else {
        echo "未设置";
    }

    echo "</td>\r\n                <td>";

    if (!empty($v["q_uid"])) {
        $v["q_user"] = unserialize($v["q_user"]);
        echo "<font color=\"#0c0\">已揭晓</font>";
        echo "<br>";
        echo "<a href='" . WEB_PATH . "/uname/" . idjia($v["q_uid"]) . "' target='_blank'>" . get_user_name($v["q_user"]) . "</a>";
    }
    else {
        echo "未揭晓";
    }

    echo "\t\t\t\t</td>\r\n                <td class=\"action\">\r\n                [  <a href=\"javascript:window.parent.Del('";
    echo G_ADMIN_PATH . "/goods/cloud_goods_del/" . $v["id"] . "/" . $v["gid"];
    echo "', '确认删除这个商品吗？');\">删除</a>]\r\n                [<a href=\"";
    echo G_ADMIN_PATH;
    echo "/goods/goods_go_one/";
    echo $v["id"];
    echo "\">购买详细</a>]\r\n\t\t\t\t</td>\r\n            </tr>\r\n            ";
}

echo "        </tbody>\r\n     </table>\r\n<div id=\"pages\"><ul><li>共 ";
echo $total;
echo " 条</li>";
echo $page;
echo "</ul></div>\r\n\r\n\r\n</div><!--table_list end-->\r\n\r\n</body>\r\n</html> \r\n";

?>
