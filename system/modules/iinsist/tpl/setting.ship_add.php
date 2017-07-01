
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n    <script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n    <link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"table-listx lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\">\r\n  <table width=\"100%\" cellspacing=\"0\" style=\"border: 0px;\">\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">快递公司：</td>\r\n          <td><input type=\"text\" name=\"ename\" value=\"";
echo $web["ename"];
echo "\"  class=\"input-text\"></td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">代码：</td>\r\n          <td><input type=\"text\" name=\"ecode\" value=\"";
echo $web["ecode"];
echo "\"  class=\"input-text\">\r\n\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">保价费用：</td>\r\n          <td><input type=\"text\" name=\"einsure\" value=\"";
echo $web["einsure"];
echo "\"  class=\"input-text\">\r\n              </td>\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">是否支持货到付款：</td>\r\n          <td><input type=\"hidden\" id=\"is_close1\" name=\"ecod\" rel=\"1\" value=\"";
echo $web["ecod"];
echo "\">\r\n              <script language=\"javascript\">yg_close(\"0,1|不支持,支持\",\"txt\",\"is_close1\",\"";

if ($web["ecod"] == "1") {
    echo "1";
}

echo "\");</script>\r\n              </td>\r\n      </tr>\r\n\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">描述：</td>\r\n          <td><input type=\"text\"  name=\"edesc\" value=\"";
echo $web["edesc"];
echo "\"  class=\"input-text\">\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">是否启用：</td>\r\n          <td><input type=\"hidden\" id=\"is_close2\" name=\"enabled\" rel=\"1\" value=\"";
echo $web["enabled"];
echo "\">\r\n              <script language=\"javascript\">yg_close(\"0,1|关闭,启用\",\"txt\",\"is_close2\",\"";

if ($web["enabled"] == "1") {
    echo "1";
}

echo "\");</script>\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\"></td>\r\n          <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\" 提交 \" ></td>\r\n      </tr>\r\n</table>\r\n</form>\r\n\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html> ";

?>
