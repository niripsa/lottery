
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
echo "/global/js/global.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<form id=\"form\" method=\"post\">\r\n<div class=\"table-list con-tab lr10\" id=\"con-tab\">\r\n\t<div name='con-tabv' class=\"con-tabv\">\r\n        <table width=\"100%\" class=\"table_form\">\r\n            <tr>\r\n                <th width=\"150\">分组名称：</th>\r\n                <th align=\"left\">";
echo $info["name"];
echo "         </th>\r\n            </tr>\r\n            ";
if (is_array($auth) && (0 < count($auth))) {
    foreach ($auth as $v ) {
        echo "            <tr><td align=\"right\" class=\"p1\"><div class=\"wid90 tal\"> <input type=\"checkbox\" class=\"mr5\" name=\"auth[]\" ";

        if (0 < strpos("#," . $info["ids"] . ",", "," . $v["id"] . ",")) {
            echo "checked";
        }

        echo " value=\"";
        echo str_join("-", $v["id"] . "#", $v["c"], $v["a"], $v["d"]);
        echo "\">";
        echo $v["name"];
        echo "</div></td>\r\n                <td class=\"p2\">\r\n                    ";
        if (is_array($v["sub_data"]) && (0 < count($v["sub_data"]))) {
            foreach ($v["sub_data"] as $vv ) {
                echo "                        <div>\r\n                            <div class=\"fwb p3\"><input type=\"checkbox\" class=\"mr5\" name=\"auth[]\" ";

                if (0 < strpos("#," . $info["ids"] . ",", "," . $vv["id"] . ",")) {
                    echo "checked";
                }

                echo " value=\"";
                echo str_join("-", $vv["id"] . "#", $vv["c"], $vv["a"], $vv["d"]);
                echo "\">";
                echo $vv["name"];
                echo "</div>\r\n                            ";
                if (is_array($vv["sub_data"]) && (0 < count($vv["sub_data"]))) {
                    echo "                                <div class=\"p4\">\r\n                                ";

                    foreach ($vv["sub_data"] as $vvv ) {
                        echo "                                    <div class=\"lf wid90\"><input type=\"checkbox\" class=\"mr5\" name=\"auth[]\" ";

                        if (0 < strpos("#," . $info["ids"] . ",", "," . $vvv["id"] . ",")) {
                            echo "checked";
                        }

                        echo " value=\"";
                        echo str_join("-", $vvv["id"] . "#", $vvv["c"], $vvv["a"], $vvv["d"]);
                        echo "\">";
                        echo $vvv["name"];
                        echo "</div>\r\n                                ";
                    }

                    echo "                                    <div class=\"cl\"></div>\r\n                                </div>\r\n                            ";
                }

                echo "                        </div>\r\n                    ";
            }
        }

        echo "            </td></tr>\r\n            ";
    }
}

echo "        </table>\r\n    </div>\r\n</div>\r\n<div class=\"table-button lr10\">\r\n    <input type=\"submit\" name=\"submit\" value=\" 提交 \" onClick=\"checkform();\" class=\"button\">\r\n</div>\r\n</form>\r\n<script language=\"javascript\">\r\n    $(\".p1 input[type=checkbox]\").click(function(){\r\n        if($(this).attr(\"checked\")==\"checked\"){\r\n            $(this).parent().parent().parent().find(\".p2\").find(\"input[type=checkbox]\").attr(\"checked\",true);\r\n        }else{\r\n            $(this).parent().parent().parent().find(\".p2\").find(\"input[type=checkbox]\").attr(\"checked\",false);\r\n        }\r\n    });\r\n    $(\".p3 input[type=checkbox]\").click(function(){\r\n        if($(this).attr(\"checked\")==\"checked\"){\r\n            $(this).parent().parent().find(\".p4\").find(\"input[type=checkbox]\").attr(\"checked\",true);\r\n            $(this).parent().parent().parent().parent().find(\".p1\").find(\"input[type=checkbox]\").attr(\"checked\",true);\r\n        }else{\r\n            $(this).parent().parent().find(\".p4\").find(\"input[type=checkbox]\").attr(\"checked\",false);\r\n        }\r\n    });\r\n    $(\".p4 input[type=checkbox]\").click(function(){\r\n        if($(this).attr(\"checked\")==\"checked\"){\r\n            $(this).parent().parent().parent().find(\".p3\").find(\"input[type=checkbox]\").attr(\"checked\",true);\r\n            $(this).parent().parent().parent().parent().parent().find(\".p1\").find(\"input[type=checkbox]\").attr(\"checked\",true);\r\n        }else{\r\n            //$(this).parent().parent().parent().find(\".p2\").find(\"input[type=checkbox]\").attr(\"checked\",false);\r\n        }\r\n    });\r\n</script>\r\n\r\n</body>\r\n</html> ";

?>
