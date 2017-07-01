
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
echo "/global/js/jquery-1.8.3.min.js\"></script>\n<style>\n</style>\n</head>\n<body>\n<div class=\"header lr10\">\n    ";
echo headerment($ments);
echo "</div>\n<div class=\"bk10\"></div>\n<div class=\"header-data lr10\">\n<form action=\"#\" method=\"post\">\n 添加时间: <input name=\"posttime1\" type=\"text\" id=\"posttime1\" class=\"input-text posttime\"  readonly=\"readonly\" /> -  \n \t\t  <input name=\"posttime2\" type=\"text\" id=\"posttime2\" class=\"input-text posttime\"  readonly=\"readonly\" />\n<script type=\"text/javascript\">\n\t\tdate = new Date();\n\t\tCalendar.setup({\n\t\t\t\t\tinputField     :    \"posttime1\",\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d %H:%M:%S\",\n\t\t\t\t\tshowsTime      :    true,\n\t\t\t\t\ttimeFormat     :    \"24\"\n\t\t});\n\t\tCalendar.setup({\n\t\t\t\t\tinputField     :    \"posttime2\",\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d %H:%M:%S\",\n\t\t\t\t\tshowsTime      :    true,\n\t\t\t\t\ttimeFormat     :    \"24\"\n\t\t});\n\t\t\t\t\n</script>\n<select name=\"sotype\">\n<option value=\"title\">商品标题</option>\n<option value=\"id\">商品id</option>\n<option value=\"cateid\">栏目id</option>\n<option value=\"catename\">栏目名称</option>\n<option value=\"brandid\">品牌id</option>\n<option value=\"brandname\">品牌名称</option>\n</select>\n<input type=\"text\" name=\"sosotext\" class=\"input-text wid100\"/>\n<input class=\"button\" type=\"submit\" name=\"sososubmit\" value=\"搜索\">\n</form>\n</div>\n<div class=\"bk10\"></div>\n<form action=\"#\" method=\"post\" name=\"myform\">\n<div class=\"table-list lr10\">\n        <table width=\"100%\" cellspacing=\"0\">\n     \t<thead>\n        \t\t<tr>\n                \t<th width=\"5%\">排序</th>\n                    <th width=\"5%\">ID</th>        \n                    <th width=\"25%\">商品标题</th>    \n                    <th width=\"8%\">所属栏目</th>\n                    <th width=\"5%\">价格</th>\n                    <th width=\"10%\">人气/推荐</th>\n                    <th width=\"15%\">管理</th>\n\t\t\t\t</tr>\n        </thead>\n        <tbody>\t\t\t\t\n        \t";
if (is_array($shoplist) && (0 < count($shoplist))) {
    foreach ($shoplist as $v ) {
        echo "            <tr>\n              <td align='center'><input name='listorders[";
        echo $v["gid"];
        echo "]' type='text' size='3' value='";
        echo $v["g_sort"];
        echo "' class='input-text-c'></td>\n                <td>";
        echo $v["gid"];
        echo "</td>\n                <td><a href=\"";
        echo WEB_PATH;
        echo "/goods/";
        echo $v["gid"];
        echo "\" target=\"_blank\">";
        echo _strcut($v["g_title"], 30);
        echo "</a></td>\n                <td>";
        echo $v["cate_name"];
        echo "</td>\n                <td>";
        echo $v["g_money"];
        echo "</td>\n                <td>";
        if (($v["g_style"] == 1) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_unset/renqi/" . $v["gid"] . "' style='color:red;'>取消人气</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_set/renqi/" . $v["gid"] . "'>设为人气</a>";
        }

        echo "                    ";
        if (($v["g_style"] == 2) || ($v["g_style"] == 3)) {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_unset/comm/" . $v["gid"] . "' style='color:red;'>取消推荐</a>";
        }
        else {
            echo "<a href='" . G_MODULE_PATH . "/goods/goods_set/comm/" . $v["gid"] . "'>设为推荐</a>";
        }

        echo "\t\t\t\t</td>\n                <td class=\"action\">\n                [<a href=\"";
        echo G_ADMIN_PATH;
        echo "/goods/goods_edit/";
        echo $v["gid"];
        echo "\">修改</a>]\n\t\t\t\t[<a href=\"javascript:;\"  shopid=\"";
        echo $v["gid"];
        echo "\" class=\"del_good\">删除</a>]\n\t\t\t\t</td>\n            </tr>\n            ";
    }
}

echo "        </tbody>\n     </table>\n    </form>\n   <div class=\"btn_paixu\">\n  \t<div style=\"width:80px; text-align:center;\">\n          <input type=\"button\" class=\"button\" value=\" 排序 \"\n        onclick=\"myform.action='";
echo G_MODULE_PATH;
echo "/goods/goods_listorder/dosubmit';myform.submit();\"/>\n    </div>\n  </div>\n    \t<div id=\"pages\"><ul><li>共 ";
echo $total;
echo " 条</li>";
echo $page;
echo "</ul></div>\n</div>\n<script>\n$(function(){\n\t$(\"a.del_good\").click(function(){\n\t\tvar id = $(this).attr(\"shopid\");\n\t\tvar str = \"";
echo G_ADMIN_PATH;
echo "/goods/goods_del/\"+id;\n\t\tvar o = confirm(\"确认删除该商品.不可恢复\");\n\t\tif(o){\n\t\t\twindow.parent.btn_map(str);\n\t\t}\n\t});\n});\n</script>\n<!--期数修改弹出框-->\n<style>\n#paywindow{position:absolute;z-index:999; display:none}\n#paywindow_b{width:372px;height:360px;background:#2a8aba; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}\n#paywindow_c{width:360px;height:338px;background:#fff;display:block;position:absolute;left:6px;top:6px;}\n.p_win_title{ line-height:40px;height:40px;background:#f8f8f8;}\n.p_win_title b{float:left}\n.p_win_title a{float:right;padding:0px 10px;color:#f60}\n.p_win_content h1{font-size:25px;font-weight:bold;}\n.p_win_but,.p_win_mes,.p_win_ctitle,.p_win_text{ margin:10px 20px;}\n.p_win_mes{border-bottom:1px solid #eee;line-height:35px;}\n.p_win_mes span{margin-left:10px;}\n.p_win_ctitle{overflow:hidden;}\n.p_win_x_b{float:left; width:73px;height:68px;background-repeat:no-repeat;}\n.p_win_x_t{ font-size:18px; font-weight:bold;font-family: \"Helvetica Neue\",\5FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}\n.p_win_but{ height:40px; line-height:40px;}\n.p_win_but a{ padding:8px 15px; background:#f60; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: \"Helvetica Neue\",\5FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }\n.p_win_but a:hover{ background:#f50}\n.p_win_text a{ font-size:13px; color:#f60}\n.pay_window_quit:hover{ color:#f00}\n</style>\n<div id=\"paywindow\">\n\t<div id=\"paywindow_b\"></div>\n\t<div id=\"paywindow_c\">\n\t\t<div class=\"p_win_title\"><a href=\"javascript:void();\" class=\"pay_window_quit\">[关闭]</a><b>：：：重设期数</b></div>\n\t\t<div class=\"p_win_content\">\t\t\t\n\t\t\t<div class=\"p_win_mes\">\n            \t <b>标题:　　　</b><span id=\"max_title\">...</span>   \t\t\n            </div>\n\t\t\t<div class=\"p_win_mes\">\n            \t <b>原最大期数:</b><span id=\"max_qishu\">0</span>　期\n            </div>\n\t\t\t<div class=\"p_win_mes\">\n            \t <b>新最大期数:</b><input type=\"text\" id=\"max_new_qishu\" class=\"input-text\" style=\"width:50px;margin-left:10px;\">需要大于原最大期数\n            </div>\n\t\t\t<div class=\"p_win_mes\">\n            \t <b>新商品售价:</b><input type=\"text\" id=\"max_one_money\" class=\"input-text\" style=\"width:50px;margin-left:10px;\">商品每一次购买的价格\n            </div>\n\t\t\t<div class=\"p_win_mes\">\n            \t <b>新商品总价:</b><input type=\"text\" id=\"max_new_money\" class=\"input-text\" style=\"width:50px;margin-left:10px;\">商品的总价格\t\t\n            </div>\n            <div class=\"p_win_mes\">    \t    \t\n    \t\t\t \t<input type=\"button\" value=\" 更新并新建一期 \" class=\"button\" id=\"max_button\" onclick=\"set_shop_qishu(this)\">              \n            </div>\t\n\t\t</div>\n\t</div>\n</div>\n\n<script>\n$(function(){\n\tvar width = ($(window).width()-372)/2;\n\tvar height = ($(window).height()-360)/2;\n\t$(\"#paywindow\").css(\"left\",width);\n\t$(\"#paywindow\").css(\"top\",height);\n\t\t\n\t$(\".pay_window_quit\").click(function(){\n\t\t$(\"#paywindow\").hide();\t\t\t\t\t\t\t\t \n\t});\n\t$(\".pay_window_show\").click(function(){\n\t\tvar gid    = $(this).attr(\"gid\");\n\t\tvar gtitle = $(this).attr(\"gtitle\");\n\t\tvar gqishu = $(this).attr(\"gqishu\");\n\t\tvar gmoney = $(this).attr(\"gmoney\");\n\t\tvar gonemoney = $(this).attr(\"gonemoney\");\n\t\t$(\"#max_one_money\").val(gonemoney);\n\t\t$(\"#max_new_money\").val(gmoney);\n\t\t\n\t\t$(\"#max_new_qishu\").val(parseInt(gqishu)+100);\n\t\t$(\"#max_qishu\").text(gqishu);\n\t\t$(\"#max_title\").text(gtitle);\n\t\t$(\"#max_button\").attr(\"onclick\",\"set_shop_qishu(\"+gid+\")\");\t\t\n\t\t$(\"#paywindow\").show();\n\t});\n\t\t\n});\nfunction set_shop_qishu(T){\n\t\n\tvar yqishu = parseInt($(\"#max_qishu\").text());\n\tvar tqishu = parseInt($(\"#max_new_qishu\").val());\n\tvar money = parseInt($(\"#max_new_money\").val());\n\tvar onemoney = parseInt($(\"#max_one_money\").val());\n\t\n\tif(!money || !onemoney || (money < onemoney)){\n\t\twindow.parent.message(\"商品价格填写不正确!\",8,2);\n\t\treturn;\n\t}\n\tif(tqishu <= yqishu){\t\n\t\twindow.parent.message(\"新期数不能小于原来的商品期数\",8,2);\n\t\treturn;\n\t}\n\tif(!tqishu){\t\n\t\twindow.parent.message(\"新期数不能为空\",8,2);\n\t\treturn;\n\t}\n\t$.post(\"";
echo G_MODULE_PATH;
echo "/content/goods_max_qishu/dosubmit/\",{\"gid\":T,\"qishu\":tqishu,\"money\":money,\"onemoney\":onemoney},function(datas){\n\t\tvar data = jQuery.parseJSON(datas);\t\t\n\t\tif(data.err == '-1'){\t\t\n\t\t\twindow.parent.message(data.meg,8,2);\t\t\n\t\t\treturn;\n\t\t}else{\t\t\t\n\t\t\twindow.parent.message(data.meg,8,2);\n\t\t\twindow.parent.btn_iframef5();\n\t\t\treturn;\n\t\t}\n\t});\n}\n</script>\n<!--期数修改弹出框-->\n\n</body>\n</html> ";

?>
