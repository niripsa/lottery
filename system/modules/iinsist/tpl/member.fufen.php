
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script> \r\n<style>\r\ntable th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}\r\ntable td{ padding-left:10px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n  \t <tr>\r\n\t\t\t<td width=\"120\" align=\"right\">资料昵称完善奖励：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"f_overziliao\" value=\"";
echo $config["f_overziliao"];
echo "\" class=\"input-text\">(福分)&nbsp;&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t<input type=\"text\" name=\"z_overziliao\" value=\"";
echo $config["z_overziliao"];
echo "\" class=\"input-text\">(经验)\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">商品购买奖励：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"f_shoppay\" value=\"";
echo $config["f_shoppay"];
echo "\" class=\"input-text\">(福分)&nbsp;&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t<input type=\"text\" name=\"z_shoppay\" value=\"";
echo $config["z_shoppay"];
echo "\" class=\"input-text\">(经验)\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">手机验证完善奖励：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"f_phonecode\" value=\"";
echo $config["f_phonecode"];
echo "\" class=\"input-text\">(福分)&nbsp;&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t<input type=\"text\" name=\"z_phonecode\" value=\"";
echo $config["z_phonecode"];
echo "\" class=\"input-text\">(经验)\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">邀请好友奖励：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"f_visituser\" value=\"";
echo $config["f_visituser"];
echo "\" class=\"input-text\">(福分)&nbsp;&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t<input type=\"text\" name=\"z_visituser\" value=\"";
echo $config["z_visituser"];
echo "\" class=\"input-text\">(经验)\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">一元抵扣：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"fufen_yuan\" value=\"";
echo $config["fufen_yuan"];
echo "\" class=\"input-text\">(福分/元)&nbsp;&nbsp;&nbsp;&nbsp;备注：福分请输入10的整数\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">佣金返回：</td>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"fufen_yongjin\" maxlength=\"4\" value=\"";
echo $config["fufen_yongjin"];
echo "\" class=\"input-text\">&nbsp;&nbsp;&nbsp;&nbsp;备注：被邀请好友每消费一元所产生的佣金返回给邀请者！\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<!--\r\n\t\t<tr>\r\n\t\t\t<td width=\"120\" align=\"right\">佣金提现手续费：</td>\r\n\t\t\t<td>-->\r\n\t\t\t\t<input type=\"hidden\" name=\"fufen_yongjintx\" onkeyup=\"value=value.replace(/\D/g,'')\"  value=\"";
echo $config["fufen_yongjintx"];
echo "\" class=\"input-text\">&nbsp;&nbsp;&nbsp;&nbsp;备注：提现一百元佣金所产生的手续费\r\n\t\t<!--\t</td>\r\n\t\t</tr>\r\n\t\t-->\r\n\t\t<tr>\r\n        \t<td width=\"120\" align=\"right\"></td>\r\n            <td>\r\n            <input type=\"submit\" class=\"button\" name=\"submit\" value=\"提交\" >\r\n            </td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n<script>\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
