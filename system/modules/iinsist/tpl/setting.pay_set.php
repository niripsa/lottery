
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n    <script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n<style>\r\n    tr{height:40px;line-height:40px}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\">\r\n  <table width=\"100%\" cellspacing=\"0\">\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" align=\"right\">支付名称：</td>\r\n\t\t\t<td><input type=\"text\" name=\"pay_name\" value=\"";
echo $info["pay_name"];
echo "\" class=\"input-text\">\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" align=\"right\">支付方式：</td>\r\n\t\t\t<td>\r\n                <input type=\"hidden\" id=\"pay_type\" name=\"pay_type\"  value=\"";
echo $info["pay_type"];
echo "\"  class=\"input-text\">\r\n                <script language=\"javascript\">yg_radio(";
echo $pay_type;
echo ",\"pay_type\",\"";
echo $info["pay_type"];
echo "\");</script>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" height=\"80\" align=\"right\">图片：</td>\r\n\t\t\t<td>\r\n                ";

if (!empty($info["pay_thumb"])) {
    echo "                <img height='40xp' src = \"";
    echo G_UPLOAD_PATH.'/'.$info["pay_thumb"];
    echo "\">\r\n                <span class=\"lr5\"></span>\r\n                ";
}

echo "           \t<input type=\"text\" id=\"imagetext\" name=\"pay_thumb\" value=\"";
echo $info["pay_thumb"];
echo "\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','photo',1,'imagetext')\" value=\"上传图片\"/>\r\n\t\t\t</td>\r\n\t\t</tr>\t\r\n\t\t\t\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" align=\"right\">适用平台：</td>\r\n\t\t\t<td>\r\n                <input type=\"hidden\" id=\"pay_mobile\" name=\"pay_mobile\"  value=\"";
echo $info["pay_mobile"];
echo "\"  class=\"input-text\">\r\n                <script language=\"javascript\">yg_checkbox(";
echo $pay_mobile;
echo ",\"pay_mobile\",\"";
echo $info["pay_mobile"];
echo "\");</script>\r\n\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" align=\"right\">是否开启：</td>\r\n\t\t\t<td>\r\n                <input type=\"hidden\" name=\"pay_start\" id=\"pay_start\" value=\"";
echo $info["pay_start"];
echo "\">\r\n                <script language=\"javascript\">yg_close(\"0,1|关闭,开启\",\"txt\",\"pay_start\",\"";
echo $info["pay_start"];
echo "\");</script>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td width=\"220\" align=\"right\" style=\"padding-right:5px;\">帐户名</td>\r\n\t\t\t<td><input class=\"input-text wid300\" value=\"";
echo $info["pay_account"];
echo "\" name=\"pay_account\" /></td>\r\n\t\t</tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\" style=\"padding-right:5px;\">帐户ID</td>\r\n          <td><input class=\"input-text wid300\" value=\"";
echo $info["pay_uid"];
echo "\" name=\"pay_uid\" /></td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\" style=\"padding-right:5px;\">帐户KEY</td>\r\n          <td><input class=\"input-text wid300\" value=\"";
echo $info["pay_key"];
echo "\" name=\"pay_key\" /></td>\r\n      </tr>\r\n        <tr>\r\n        \t<td width=\"220\" align=\"right\"></td>\r\n            <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\" 提交 \" ></td>\r\n\t\t</tr>\r\n</table>\r\n</form>\r\n\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html> ";

?>
