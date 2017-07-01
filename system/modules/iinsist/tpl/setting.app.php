
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
echo "</div>\r\n<div class=\"table-listx lr10\">\r\n<!--start-->\r\n<form name=\"myform\" action=\"\" method=\"post\">\r\n  <table width=\"100%\" cellspacing=\"0\" style=\"border: 0px;\">\r\n      <tr><td><span class=\"fwb lh30 tar\">APP下载</span></td></tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">平台名称1：</td>\r\n          <td><input type=\"text\" name=\"name_1\" value=\"";
echo $web["name_1"];
echo "\"  class=\"input-text\"></td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">二维码：</td>\r\n          <td><div class=\"lf\"><input type=\"text\" id=\"imagetext1\" name=\"img_1\" value=\"";
echo $web["img_1"];
echo "\"  class=\"input-text lh20\"></div>\r\n              <div class=\"button lf\" onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','二维码上传','banner',1,'imagetext1')\">上传</div>\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">平台名称2：</td>\r\n          <td><input type=\"text\" name=\"name_2\" value=\"";
echo $web["name_2"];
echo "\"  class=\"input-text\"></td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">二维码：</td>\r\n          <td><div class=\"lf\"><input type=\"text\" id=\"imagetext2\" name=\"img_2\" value=\"";
echo $web["img_2"];
echo "\"  class=\"input-text lh20\"></div>\r\n              <div class=\"button lf\" onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','二维码上传','banner',1,'imagetext2')\">上传</div>\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">平台名称3：</td>\r\n          <td><input type=\"text\" name=\"name_3\" value=\"";
echo $web["name_3"];
echo "\"  class=\"input-text\"></td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">二维码：</td>\r\n          <td><div class=\"lf\"><input type=\"text\" id=\"imagetext3\" name=\"img_3\" value=\"";
echo $web["img_3"];
echo "\"  class=\"input-text lh20\"></div>\r\n              <div class=\"button lf\" onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','二维码上传','banner',1,'imagetext3')\">上传</div>\r\n          </td>\r\n      </tr>\r\n      <tr><td><span class=\"fwb lh30 tar\">微信端</span></td></tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\">二维码：</td>\r\n          <td><div class=\"lf\"><input type=\"text\" id=\"imagetext4\" name=\"img_wx\" value=\"";
echo $web["img_wx"];
echo "\"  class=\"input-text lh20\"></div>\r\n              <div class=\"button lf\" onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','二维码上传','banner',1,'imagetext4')\">上传</div>\r\n          </td>\r\n      </tr>\r\n      <tr>\r\n          <td width=\"220\" align=\"right\"></td>\r\n          <td><input type=\"submit\" class=\"button\" name=\"dosubmit\"  value=\" 提交 \" ></td>\r\n      </tr>\r\n</table>\r\n</form>\r\n\r\n</div><!--table-list end-->\r\n\r\n<script>\r\n\t\r\n</script>\r\n</body>\r\n</html> ";

?>
