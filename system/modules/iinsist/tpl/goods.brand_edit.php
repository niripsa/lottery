
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<style>\r\nbody{ background-color:#fff}\r\n#category_select span{\r\n\tborder:1px solid #ccc;\r\n\tbackground:#eee;\r\n\tpadding:3px;\r\n}\r\n#category_select b{\r\n color:#f00;cursor:pointer;\r\n}\r\n#category_select input{\r\n\twidth:0px;border:0px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n";

if (ROUTE_A == "brand_edit") {
    echo "<form name=\"form\" action=\"\" method=\"post\">\r\n<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n\t<tr>\r\n    \t\t<td align=\"right\" class=\"wid100\">所属栏目：</td>\r\n\t\t\t<td>\t\r\n\t\t\t\t<div id=\"category_select\" style=\"float:left\">\t\r\n\t\t\t\t\t";

    foreach ($cateid_arr as $row ) {
        echo "<span><input name='cateid[]' value='" . $row["cateid"] . "'/>" . $row["cate_name"] . "<b>&nbsp;×</b></span>&nbsp;";
    }

    echo "\t\t\t\t</div>\r\n\t\t\t\t<a style=\"color:#f00;float:left;\"  class=\"pay_window_show\"> 【继续添加所属栏目】 </a>    \t\t\r\n    \t\t</td>\r\n    </tr>\r\n    <tr>\r\n\t\t\t<td align=\"right\">品牌名称：</td>\r\n\t\t\t<td><input type=\"text\"  name=\"name\" class=\"input-text wid100\" value=\"";
    echo $brands["name"];
    echo "\"></td>\r\n\t</tr>\r\n    <tr>\r\n\t\t\t<td align=\"right\">排序：</td>\r\n\t\t\t<td><input type=\"text\"  name=\"sort\" onKeyUp=\"value=value.replace(/[^\d]/ig,'')\" class=\"input-text wid100\" value=\"";
    echo $brands["sort"];
    echo "\">\r\n            <span>数值越大,排序越靠前</span>\r\n            </td>\r\n\t</tr>\r\n    <tr height=\"60px\">\r\n\t\t\t<td align=\"right\"></td>\r\n\t\t\t<td><input class=\"button\" type=\"submit\" name=\"dosubmit\" value=\" 修改 \" /></td>\r\n\t</tr>\r\n</form>\r\n</table>\r\n";
}

if (ROUTE_A == "brand_add") {
    echo "<form name=\"form\" action=\"\" method=\"post\">\r\n<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n\t<tr>\r\n    \t\t<td align=\"right\" class=\"wid100\">所属栏目：</td>\r\n\t\t\t<td>\r\n\t\r\n\t\t\t\t<div id=\"category_select\" style=\"float:left\">\t\t\t\t\t\r\n\t\t\t\t</div>\r\n\t\t\t\t<a style=\"color:#f00;float:left;\"  class=\"pay_window_show\"> 【添加所属栏目】 </a>\r\n    \t\t</td>\t\t\t\r\n\t\t\t\r\n    </tr>\r\n    <tr>\r\n\t\t\t<td align=\"right\">品牌名称：</td>\r\n\t\t\t<td><input type=\"text\"  name=\"name\" class=\"input-text wid100\"></td>\r\n\t</tr>\r\n    <tr>\r\n\t\t\t<td align=\"right\">排序：</td>\r\n\t\t\t<td><input type=\"text\"  name=\"sort\" onKeyUp=\"value=value.replace(/[^\d]/ig,'')\" class=\"input-text wid100\">\r\n            <span>数值越大,排序越靠前</span>\r\n            </td>\r\n\t</tr>\r\n    <tr height=\"60px\">\r\n\t\t\t<td align=\"right\"></td>\r\n\t\t\t<td><input class=\"button\" type=\"submit\" name=\"dosubmit\" value=\" 添加 \" /></td>\r\n\t</tr>\r\n</form>\r\n</table>\r\n";
}

echo "</div>\r\n\r\n<!--期数修改弹出框-->\r\n<style>\r\n#paywindow{position:absolute;z-index:999; display:none}\r\n#paywindow_b{width:372px;height:160px;background:#2a8aba; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}\r\n#paywindow_c{width:360px;height:138px;background:#fff;display:block;position:absolute;left:6px;top:6px;}\r\n.p_win_title{ line-height:40px;height:40px;background:#f8f8f8;}\r\n.p_win_title b{float:left}\r\n.p_win_title a{float:right;padding:0px 10px;color:#f60}\r\n.p_win_content h1{font-size:25px;font-weight:bold;}\r\n.p_win_but,.p_win_mes,.p_win_ctitle,.p_win_text{ margin:10px 20px;}\r\n.p_win_mes{border-bottom:1px solid #eee;line-height:35px;}\r\n.p_win_mes span{margin-left:10px;}\r\n.p_win_ctitle{overflow:hidden;}\r\n.p_win_x_b{float:left; width:73px;height:68px;background-repeat:no-repeat;}\r\n.p_win_x_t{ font-size:18px; font-weight:bold;font-family: \"Helvetica Neue\",\5FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}\r\n.p_win_but{ height:40px; line-height:40px;}\r\n.p_win_but a{ padding:8px 15px; background:#f60; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: \"Helvetica Neue\",\5FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }\r\n.p_win_but a:hover{ background:#f50}\r\n.p_win_text a{ font-size:13px; color:#f60}\r\n.pay_window_quit:hover{ color:#f00}\r\n</style>\r\n<div id=\"paywindow\">\r\n\t<div id=\"paywindow_b\"></div>\r\n\t<div id=\"paywindow_c\">\r\n\t\t<div class=\"p_win_title\"><a href=\"javascript:void();\" class=\"pay_window_quit\">[关闭]</a><b>：：：选择所属分类栏目</b></div>\r\n\t\t<div class=\"p_win_content\">\t\r\n\t\t\t\r\n\t\t\t<div class=\"p_win_mes\">\r\n            \t <b>选择栏目:</b>\r\n\t\t\t\t 　<select id=\"selectcateid\">\r\n\t\t\t\t\t<option value=\"-1\">≡ 请选择分类 ≡</option>\r\n\t\t\t\t\t";
echo $categoryshtml;
echo "    \t\t\t</select>\t\r\n            </div>\r\n\t\t\r\n            <div class=\"p_win_mes\">    \t    \t\r\n    \t\t\t \t <b>　　　　　</b><input type=\"button\" value=\" 确定 \" class=\"button\" id=\"set_select_cateid\">          \r\n            </div>\t\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n\r\n\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script>\r\n$(function(){\r\n\tvar width = ($(window).width()-572)/2;\r\n\tvar height = ($(window).height()-460)/2;\r\n\t$(\"#paywindow\").css(\"left\",width);\r\n\t$(\"#paywindow\").css(\"top\",height);\r\n\t\t\r\n\t$(\".pay_window_quit\").click(function(){\r\n\t\t$(\"#paywindow\").hide();\t\t\t\t\t\t\t\t \r\n\t});\r\n\t\t\r\n\t$(\".pay_window_show\").click(function(){\r\n\t\t$(\"#paywindow\").show();\t\r\n\t});\r\n\t\t\r\n\t\t\r\n\t$(\"#category_select b\").click(function(){\t\t\t\r\n\t\t\t$(this).parent().remove();\t\t\t\r\n\t});\r\n\t\r\n\tvar sid_arr = [];\r\n\t$(\"#set_select_cateid\").click(function(){\r\n\t\tvar select = $(\"#selectcateid option:selected\");\t\r\n\t\tvar a_html = '<span>&nbsp;<input name=\"cateid[]\" value=\"'+select.val()+'\"/>'+select.text()+'<b>&nbsp;×</b></span>&nbsp;';\r\n\t\r\n\t\tif(select.val() == '-1'){\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\tif(sid_arr[select.val()]){\r\n\t\t\talert(\"已经添加\");\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\tsid_arr[select.val()] = true;\r\n\t\t$(\"#category_select\").append(a_html);\r\n\t\t\t\r\n\t\t$(\"#category_select b\").click(function(){\r\n\t\t\t$(this).parent().remove();\r\n\t\t\tsid_arr[select.val()] = false;\r\n\t\t});\r\n\t\r\n\t});\r\n});\r\n</script>\r\n<!--期数修改弹出框-->\r\n\r\n</body>\r\n</html> \r\n";

?>
