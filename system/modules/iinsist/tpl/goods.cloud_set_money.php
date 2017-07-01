
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<style>\r\n\t.bg{background:#fff url(";
echo G_GLOBAL_STYLE;
echo "/global/image/ruler.gif) repeat-x scroll 0 9px }\r\n\t.color_window_td a{ float:left; margin:0px 10px;}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"header-data lr10\">\r\n\t<b>提示:</b> <font color=\"red\">重置本期商品的【价格】 会导致已经生成的交易记录清空！ （已参与人数为【0】的商品不受影响）</font>\r\n\t<br/>\r\n\t<b>提示:</b> <font color=\"red\">下一期商品的价格也会是本次设置的新价格！</font>\r\n</div>\r\n\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<form method=\"post\" action=\"#\" id=\"form_post\">\r\n\t<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">商品标题：</td>\r\n\t\t\t<td>\r\n            \t<a target=\"_blank\" href=\"";
echo WEB_PATH;
echo "/goods/";
echo $shopinfo["gid"];
echo "\"><b>第(<font color=\"red\">";
echo $shopinfo["qishu"];
echo "</font>)期  ";
echo $shopinfo["title"];
echo "</b></a>\r\n            </td>\t\t\t\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">商品单次售价：</td>\r\n\t\t\t<td><b style=\"color:red\">";
echo $shopinfo["price"];
echo "</b> <b>元</b></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">商品总售价：</td>\r\n\t\t\t<td><b style=\"color:red\">";
echo $shopinfo["g_money"];
echo "</b> <b>元</b></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">已参与人数：</td>\r\n\t\t\t<td><b style=\"color:red\">";
echo $shopinfo["canyurenshu"];
echo "</b> <b>人</b></td>\t\t\t\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">总需要人数：</td>\r\n\t\t\t<td><b style=\"color:red\">";
echo $shopinfo["zongrenshu"];
echo "</b> <b>人</b> 　　　向上取整(";
echo $shopinfo["g_money"];
echo " / ";
echo $shopinfo["price"];
echo ") = ";
echo $shopinfo["zongrenshu"];
echo "</td>\r\n\t\t</tr>\r\n\t\t\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px;color:red\">新单次售价：</td>\r\n\t\t\t<td><input type=\"text\" id=\"yunjiage\"  name=\"price\" onKeyUp=\"value=value.replace(/\D/g,'')\" style=\"width:65px; padding-left:0px; text-align:center\" value=\"1\" class=\"input-text\"> 元</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px;color:red\">新总售价：</td>\r\n\t\t\t<td><input type=\"text\" name=\"g_money\" id=\"money\" onKeyUp=\"value=value.replace(/\D/g,'')\" style=\"width:65px;padding-left:0px;text-align:center\" class=\"input-text\"> 元</td>\r\n\t\t</tr>\r\n       \r\n      \r\n\r\n        <tr height=\"60px\">\r\n\t\t\t<td align=\"right\" style=\"width:120px\"></td>\r\n\t\t\t<td><input type=\"submit\" name=\"dosubmit\" class=\"button\" value=\" 确认更改 \" /></td>\r\n\t\t</tr>\r\n\t</table>\r\n</form>\r\n</div>\r\n<!--JS-->\r\n<script type=\"text/javascript\">\r\n$(\"#form_post\").submit(function(){\r\n\t\t\t\t\t\t\t\t\r\n\tvar canyurenshu = ";
echo $shopinfo["canyurenshu"];
echo ";\r\n\tvar zongrenshu = ";
echo $shopinfo["zongrenshu"];
echo ";\r\n\tvar y_money = ";
echo $shopinfo["money"];
echo ";\r\n\tvar y_yunjiage = ";
echo $shopinfo["yunjiage"];
echo ";\r\n\t\r\n\tvar money = parseInt($(\"#money\").val());\t\r\n\tvar yunjiage = parseInt($(\"#yunjiage\").val());\r\n\t\r\n\tif((y_money == money) && (y_yunjiage == yunjiage)){\r\n\t\twindow.parent.message(\"商品价格没有改变!\",8,2);\r\n\t\treturn false;\r\n\t}\r\n\t\r\n\tif(!money || !yunjiage){\r\n\t\twindow.parent.message(\"商品价格输入不正确!\",8,2);\r\n\t\treturn false;\r\n\t}\r\n\tif(yunjiage > money){\r\n\t\twindow.parent.message(\"单次价格不能大于总价格\",8,2);\r\n\t\treturn false;\r\n\t}\r\n\tif(canyurenshu>0){\r\n\t\tvar s = confirm(\"参与人数不为0,将会清空会员购买记录，是否继续\");\r\n\t\tif(!s){\r\n\t\t\treturn false;\t\t\t\r\n\t\t}\r\n\t}\r\n\treturn true;\r\n\t\t\r\n\t\r\n});\r\n</script>\r\n<!--JS-->\r\n</body>\r\n</html> ";

?>
