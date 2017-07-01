
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
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<form id=\"form\" method=\"post\">\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n\t<div name='con-tabv' class=\"con-tabv\">\r\n        <table width=\"100%\" class=\"table_form\">\r\n            <tr>\r\n                <th>用户名：</th>\r\n                <td><input type=\"text\" name=\"username\" class=\"input-text\" value=\"";
echo $info["username"];
echo "\">\r\n\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th>所属分组：</th>\r\n                <td>\r\n                    <input type=\"hidden\" name=\"gid\" id=\"gid\" value=\"";
echo $web["gid"];
echo "\">\r\n                    <script language=\"javascript\">yg_select(";
echo $group;
echo ",\"gid\",\"";
echo $info["gid"];
echo "\");</script>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th>密码：</th>\r\n                <td><input type=\"password\" name=\"pwd\" class=\"input-text\" value=\"\">\r\n\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th>确认密码：</th>\r\n                <td><input type=\"password\" name=\"repwd\" class=\"input-text\" value=\"\">\r\n\r\n                </td>\r\n            </tr>\r\n        </table>\r\n    </div>\r\n</div>\r\n<div class=\"table-button lr10\">\r\n    <input type=\"submit\" name=\"submit\" value=\" 提交 \" onClick=\"checkform();\" class=\"button\">\r\n</div>\r\n</form>\r\n</body>\r\n</html> ";

?>
