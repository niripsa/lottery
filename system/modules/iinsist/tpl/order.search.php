
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title>后台首页</title>\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\n<link rel=\"stylesheet\" href=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar-blue.css\" type=\"text/css\"> \n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar.js\"></script>\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\n<style>\n</style>\n</head>\n<body>\n<div class=\"bk10\"></div>\n<div class=\"header-data lr10\">\n<form action=\"\" method=\"post\">\n<input type=\"text\" name=\"s\" class=\"input-text wid100\" value=\"";
echo $s;
echo "\"/>\n<input class=\"button\" type=\"submit\" name=\"sososubmit\" value=\"搜索\">\n</form>\n</div>\n<div class=\"bk10\"></div>\n<div class=\"table-list lr10\">\n    ";
if (is_array($search_user) && (0 < count($search_user))) {
    echo "        <div class=\"ft14 fwb lh20\">用户搜索结果</div>\n        <table width=\"100%\" cellspacing=\"0\">\n            <thead>\n            <tr>\n                <th align=\"center\">UID</th>\n                <th align=\"center\">用户名</th>\n                <th align=\"center\">邮箱</th>\n                <th align=\"center\">手机</th>\n                <th align=\"center\">余额</th>\n                <th align=\"center\">福分</th>\n                <th align=\"center\">经验值</th>\n                <th align=\"center\">登陆时间,地址,IP</th>\n                <th align=\"center\">注册时间</th>\n                <th align=\"center\">管理</th>\n            </tr>\n            </thead>\n            <tbody>\n            ";

    foreach ($search_user as $v ) {
        echo "                <tr>\n                    <td align=\"center\">";
        echo $v["uid"];
        echo "</td>\n                    <td align=\"center\"><a href=\"";
        echo WEB_PATH;
        echo "/uname/";
        echo idjia($v["uid"]);
        echo "\" target=\"_blank\">";
        echo $v["username"];
        echo "</a></td>\n                    <td align=\"center\">";
        echo $v["email"];
        echo " ";

        if ($v["emailcode"] == 1) {
            echo "<span style=\"color:#0c0\">√</span>";
        }
        else {
            echo "<span style=\"color:red\">×</span>";
        }

        echo "</td>\n                    <td align=\"center\">";
        echo $v["mobile"];
        echo " ";

        if ($v["mobilecode"] == 1) {
            echo "<span style=\"color:#0c0\">√</span>";
        }
        else {
            echo "<span style=\"color:red\">×</span>";
        }

        echo "</td>\n                    <td align=\"center\">";
        echo $v["money"];
        echo "</td>\n                    <td align=\"center\">";
        echo $v["score"];
        echo "</td>\n                    <td align=\"center\">";
        echo $v["jingyan"];
        echo "</td>\n                    <td align=\"center\">";
        echo _put_time($v["login_time"], "未登录");
        echo ",";
        echo trim($v["user_ip"], ",");
        echo "</td>\n                    <td align=\"center\">";
        echo _put_time($v["time"]);
        echo "</td>\n                    <td align=\"center\">\n                        ";

        if ($v["status"] == "-1") {
            echo "                            <a href=\"";
            echo G_MODULE_PATH;
            echo "/member/huifu/";
            echo $v["uid"];
            echo "\">恢复</a>\n                            <a href=\"";
            echo G_MODULE_PATH;
            echo "/member/del_true/";
            echo $v["uid"];
            echo "\" onClick=\"return confirm('是否真的删除！');\">删除</a>\n                        ";
        }
        else {
            echo "                            [<a href=\"";
            echo G_MODULE_PATH;
            echo "/index/manage/";
            echo $v["uid"];
            echo "\" target=\"_blank\">代管</a>]\n                                                                                                                               [<a href=\"";
            echo G_MODULE_PATH;
            echo "/member/modify/";
            echo $v["uid"];
            echo "\">改</a>]\n                                                                                                                                                                                                                  [<a href=\"";
            echo G_MODULE_PATH;
            echo "/member/del/";
            echo $v["uid"];
            echo "\" onClick=\"return confirm('是否真的删除！');\">删</a>]\n                        ";
        }

        echo "                    </td>\n                </tr>\n            ";
    }

    echo "            </tbody>\n        </table>\n    ";
}

echo "    ";
if (is_array($search_order) && (0 < count($search_order))) {
    echo "        <div class=\"ft14 fwb lh20\">订单搜索结果</div>\n    <table width=\"100%\" cellspacing=\"0\">\n        <thead>\n        <tr>\n            <th align=\"center\">订单号</th>\n            <th align=\"center\">商品标题</th>\n            <th align=\"center\">购买用户</th>\n            <th align=\"center\">购买总价</th>\n            <th align=\"center\">购买日期</th>\n            <th align=\"center\">中奖</th>\n            <th align=\"center\">订单状态</th>\n            <th align=\"center\">管理</th>\n        </tr>\n        </thead>\n        <tbody>\n        ";

    foreach ($search_order as $v ) {
        echo "            <tr>\n                <td align=\"center\">";
        echo $v["ocode"];
        echo " ";

        if ($v["code_tmp"]) {
            echo " <font color='#ff0000'>[多]</font>";
        }

        echo "</td>\n                <td align=\"center\">\n                    <a  target=\"_blank\" href=\"";
        echo WEB_PATH . "/cgoods/" . $v["info"]["id"];
        echo "\">\n                        第(";
        echo $v["info"]["qishu"];
        echo ")期";
        echo _strcut($v["info"]["g_title"], 0, 25);
        echo "</a>\n                </td>\n                <td align=\"center\">";
        echo get_user_name($v["ouid"]);
        echo "</td>\n                <td align=\"center\">￥";
        echo $v["omoney"];
        echo "元</td>\n                <td align=\"center\">";
        echo date("Y-m-d H:i:s", $v["otime"]);
        echo "</td>\n                <td align=\"center\">";
        echo 0 < $v["ofstatus"] ? "中奖" : "未中奖";
        echo "</td>\n                <td align=\"center\">";
        echo $v["status_txt"];
        echo "</td>\n                <td align=\"center\"><a href=\"";
        echo G_MODULE_PATH;
        echo "/order/detail/";
        echo $v["oid"];
        echo "\">详细</a></td>\n            </tr>\n        ";
    }

    echo "        </tbody>\n    </table>\n    ";
}

echo "    ";
if (is_array($search_goods) && (0 < count($search_goods))) {
    echo "        <div class=\"ft14 fwb lh20\">普通商品搜索结果</div>\n        <table width=\"100%\" cellspacing=\"0\">\n            <thead>\n            <tr>\n                <th width=\"5%\">排序</th>\n                <th width=\"5%\">ID</th>\n                <th width=\"25%\">商品标题</th>\n                <th width=\"8%\">所属栏目</th>\n                <th width=\"5%\">价格</th>\n                <th width=\"10%\">人气/推荐</th>\n                <th width=\"15%\">管理</th>\n            </tr>\n            </thead>\n            <tbody>\n            ";

    foreach ($search_goods as $v ) {
        echo "                <tr>\n                    <td align='center'><input name='listorders[";
        echo $v["gid"];
        echo "]' type='text' size='3' value='";
        echo $v["g_sort"];
        echo "' class='input-text-c'></td>\n                    <td>";
        echo $v["gid"];
        echo "</td>\n                    <td><a href=\"";
        echo WEB_PATH;
        echo "/goods/";
        echo $v["gid"];
        echo "\" target=\"_blank\">";
        echo _strcut($v["g_title"], 30);
        echo "</a></td>\n                    <td>";
        echo $v["cate_name"];
        echo "</td>\n                    <td>";
        echo $v["g_money"];
        echo "</td>\n                    <td>";
        if (($v["g_style"] == 1) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_unset/renqi/" . $v["gid"] . "' style='color:red;'>取消人气</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_set/renqi/" . $v["gid"] . "'>设为人气</a>";
        }

        echo "                        ";
        if (($v["g_style"] == 2) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_unset/comm/" . $v["gid"] . "' style='color:red;'>取消推荐</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_set/comm/" . $v["gid"] . "'>设为推荐</a>";
        }

        echo "                    </td>\n                    <td class=\"action\">\n                        [<a href=\"";
        echo G_ADMIN_PATH;
        echo "/goods/goods_edit/";
        echo $v["gid"];
        echo "\">修改</a>]\n                        [<a href=\"javascript:;\"  shopid=\"";
        echo $v["gid"];
        echo "\" class=\"del_good\">删除</a>]\n                    </td>\n                </tr>\n            ";
    }

    echo "            </tbody>\n        </table>\n    ";
}

echo "    ";
if (is_array($search_cgoods) && (0 < count($search_cgoods))) {
    echo "        <div class=\"ft14 fwb lh20\">夺宝商品搜索结果</div>\n        <table width=\"100%\" cellspacing=\"0\">\n            <thead>\n            <tr>\n                <th width=\"5%\">ID</th>\n                <th width=\"25%\">商品标题</th>\n                <th width=\"8%\">所属栏目</th>\n                <th width=\"10%\">已参与/总需</th>\n                <th width=\"5%\">单价/元</th>\n                <th width=\"10%\">期数/最大期数</th>\n                <th width=\"10%\">人气商品</th>\n                <th width=\"15%\">管理</th>\n            </tr>\n            </thead>\n            <tbody>\n            ";

    foreach ($search_cgoods as $v ) {
        echo "                <tr>\n                    <td>";
        echo $v["gid"];
        echo "</td>\n                    <td><a href=\"";
        echo WEB_PATH;
        echo "/cgoods/";
        echo $v["id"];
        echo "\" target=\"_blank\">";
        echo _strcut($v["g_title"], 30);
        echo "</a></td>\n                    <td>";
        echo $v["cate_name"];
        echo "</td>\n                    <td><font color=\"#ff0000\">";
        echo $v["canyurenshu"];
        echo "</font> / ";
        echo $v["zongrenshu"];
        echo "</td>\n                    <td>";
        echo $v["price"];
        echo "</td>\n                    <td>";
        echo $v["qishu"];
        echo "/";
        echo $v["maxqishu"];
        echo "</td>\n                    <td>\n                        ";
        if (($v["g_style"] == 1) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/cloud_goods_unset/renqi/" . $v["gid"] . "' style='color:red;'>取消人气</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/cloud_goods_set/renqi/" . $v["gid"] . "'>设为人气</a>";
        }

        echo "                        ";
        if (($v["g_style"] == 2) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/cloud_goods_unset/comm/" . $v["gid"] . "' style='color:red;'>取消推荐</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/cloud_goods_set/comm/" . $v["gid"] . "'>设为推荐</a>";
        }

        echo "                    </td>\n                    <td class=\"action\">\n                        [<a href=\"";
        echo G_ADMIN_PATH;
        echo "/goods/goods_set_money/";
        echo $v["gid"];
        echo "\">重置价格</a>]\n                        [<a href=\"";
        echo G_ADMIN_PATH;
        echo "/goods/cloud_goods_edit/";
        echo $v["gid"];
        echo "\">修改</a>]\n                        [<a href=\"";
        echo G_ADMIN_PATH;
        echo "/goods/qishu_list/";
        echo $v["gid"];
        echo "\">往期</a>]\n                        [<a href=\"javascript:;\"  shopid=\"";
        echo $v["id"];
        echo "\" class=\"del_good\">删除</a>]\n                    </td>\n                </tr>\n            ";
    }

    echo "            </tbody>\n        </table>\n    ";
}

echo "</body>\n</html> ";

?>
