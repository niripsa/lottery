
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
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<form id=\"form\" method=\"post\">\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n\t<div name='con-tabv' class=\"con-tabv\">\r\n        <table width=\"100%\" class=\"table_form\">\r\n            <tr>\r\n                <th>功能名称：</th>\r\n                <td><input type=\"text\" name=\"name\" class=\"input-text\" value=\"";
echo $info["name"];
echo "\">\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th>上级功能：</th>\r\n                <td>\r\n                    <select name=\"pid\">\r\n                        ";
if (is_array($p_fun) && (0 < count($p_fun))) {
    foreach ($p_fun as $v ) {
        echo "                        <option value=\"";
        echo $v["id"];
        echo "\" ";

        if ($v["id"] == $info["pid"]) {
            echo "selected";
        }

        echo ">";
        echo $v["name"];
        echo "</option>\r\n                            ";
        if (is_array($v["sub_data"]) && (0 < count($v["sub_data"]))) {
            foreach ($v["sub_data"] as $vv ) {
                echo "                                <option value=\"";
                echo $vv["id"];
                echo "\" ";

                if ($vv["id"] == $info["pid"]) {
                    echo "selected";
                }

                echo "> ├ ";
                echo $vv["name"];
                echo "</option>\r\n                            ";
            }
        }

        echo "                        ";
    }
}

echo "                    </select>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n              <th>是否启用：</th>\r\n              <td>\r\n                  <input type=\"hidden\" name=\"is_enable\" id=\"is_enable\" value=\"";
echo $info["is_enable"];
echo "\">\r\n                  <script language=\"javascript\">yg_close(\"0,1|关闭,开启\",\"txt\",\"is_enable\",\"";
echo $info["is_enable"];
echo "\");</script>\r\n              </td>\r\n             </tr>\r\n            <tr>\r\n                <th>是否菜单：</th>\r\n                <td>\r\n                    <input type=\"hidden\" name=\"type\" id=\"type\" value=\"";
echo $info["type"];
echo "\">\r\n                    <script language=\"javascript\">yg_close(\"0,1|关闭,开启\",\"txt\",\"type\",\"";
echo $info["type"];
echo "\");</script>\r\n\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th>模块：</th>\r\n                <td><input type=\"text\" name=\"c\" class=\"input-text\" value=\"";
echo $info["c"];
echo "\">  </td>\r\n            </tr>\r\n            <tr>\r\n                <th>功能：</th>\r\n                <td><input type=\"text\" name=\"a\" class=\"input-text\" value=\"";
echo $info["a"];
echo "\">  </td>\r\n            </tr>\r\n            <tr>\r\n                <th>参数：</th>\r\n                <td><input type=\"text\" name=\"d\" class=\"input-text\" value=\"";
echo $info["d"];
echo "\">  </td>\r\n            </tr>\r\n        </table>\r\n    </div>\r\n</div>\r\n<div class=\"table-button lr10\">\r\n    <input type=\"submit\" name=\"submit\" value=\" 提交 \" onClick=\"checkform();\" class=\"button\">\r\n</div>\r\n</form>\r\n</body>\r\n</html> ";

?>
