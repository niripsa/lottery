
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
echo "/global/js/from.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<form id=\"form\" method=\"post\">\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n\t<div name='con-tabv' class=\"con-tabv\">\r\n        <table width=\"100%\" class=\"table_form\">\r\n          <tr>\r\n            <th>分组名称：</th>\r\n            <td><input type=\"text\" name=\"name\" class=\"input-text\" value=\"";
echo $info["name"];
echo "\">\r\n                <span>请输入分组名称</span>\r\n            </td>\r\n          </tr>\r\n          <tr>\r\n              <th>是否启用：</th>\r\n              <td>\r\n                  <input type=\"hidden\" name=\"disabled\" id=\"disabled\" value=\"";
echo $info["disabled"];
echo "\">\r\n                  <script language=\"javascript\">yg_close(\"0,1|关闭,开启\",\"txt\",\"disabled\",\"";
echo $info["disabled"];
echo "\");</script>\r\n              </td>\r\n          </tr>\r\n          <tr id=\"catdir_tr\">\r\n            <th>分组说明：</th>\r\n            <td><textarea name=\"description\" rows=\"5\" cols=\"50\" style=\"width: 500px; height: 100px;\">";
echo $info["description"];
echo "</textarea>\r\n          </tr>\r\n        </table>\r\n    </div>\r\n</div>\r\n<div class=\"table-button lr10\">\r\n    <input type=\"submit\" name=\"submit\" value=\" 提交 \" onClick=\"checkform();\" class=\"button\">\r\n</div>\r\n</form>\r\n\r\n\r\n</body>\r\n</html> ";

?>
