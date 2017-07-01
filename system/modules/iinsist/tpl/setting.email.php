
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<form action=\"\" method=\"post\" id=\"myform\">\r\n<table width=\"100%\" cellspacing=\"0\" style=\"border: 0px;\">\r\n  <tr>\r\n    <td width=\"100\">邮件发送模式</td>\r\n    <td>\r\n     <input checkbox=\"mail_type\" value=\"1\" onClick=\"showsmtp(this)\" type=\"radio\"  checked> SMTP 函数发送    \t\t     \r\n\t</td>\r\n  </tr>\r\n  <tr>\r\n    <td>邮件服务器</td>\r\n    <td><input type=\"text\" class=\"input-text\" name=\"stmp_host\" size=\"30\" value=\"";
echo $info["stmp_host"];
echo "\"/></td>\r\n  </tr>  \r\n  <tr>\r\n    <td>发件人地址</td>\r\n    <td><input type=\"text\" class=\"input-text\" name=\"from\" size=\"30\" value=\"";
echo $info["from"];
echo "\"/></td>\r\n  </tr> \r\n  <tr>\r\n    <td>发件人姓名</td>\r\n    <td><input type=\"text\" class=\"input-text wid80\" name=\"fromName\" size=\"30\" value=\"";
echo $info["fromName"];
echo "\"/></td>\r\n  </tr>\r\n   <tr>\r\n    <td>发送编码</td>\r\n    <td>\r\n        <input type=\"hidden\" name=\"big\" id=\"big\"  value=\"";
echo $web["big"];
echo "\">\r\n        <script language=\"javascript\">yg_select(";
echo $big;
echo ",\"big\",\"";
echo $web["big"];
echo "\");</script>\r\n    </td>\r\n  </tr> \r\n  <tr>\r\n\t <td>邮箱用户名</td>\r\n\t <td><input type=\"text\" class=\"input-text\" name=\"user\" size=\"30\" value=\"";
echo $info["user"];
echo "\"/></td>\r\n  </tr> \r\n  <tr>\r\n\t <td>邮箱密码</td>\r\n\t <td><input type=\"password\" class=\"input-text\" name=\"pass\" size=\"30\" value=\"";
echo $info["pass"];
echo "\"/></td>\r\n  </tr>  \r\n  <tr>\r\n\t <td>测试邮件</td>\r\n\t <td>\r\n     <input type=\"text\" id=\"ceshi\" class=\"input-text\"  size=\"30\" value=\"输入测试邮箱地址...\"/>\r\n     <input type=\"button\" value=\" 测试邮件 \" onClick=\"sendemail();\" class=\"button\">\r\n     </td>\r\n  </tr> \r\n\t<tr>\r\n    \t<td width=\"100\"></td> \r\n   \t\t<td> <input type=\"submit\" value=\" 提交 \" name=\"dosubmit\" class=\"button\"></td>\r\n    </tr>\r\n</table>\r\n</form>\r\n</div><!--table-list end-->\r\n<script>\r\nfunction sendemail(){\r\n\tvar dizhi=document.getElementById('ceshi');\r\n\tvar email=dizhi.value;\r\n\tvar\txinemail=email.replace('.',\"|\");\r\n\t$.post(\"";
echo G_MODULE_PATH;
echo "/setting/email/cesi/\"+xinemail,function(data){\r\n\t\talert(data);\r\n\t});\t\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
