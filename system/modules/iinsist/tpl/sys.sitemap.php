
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n    <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/from.js\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<form action=\"\" method=\"post\" class=\"lh30\">\r\n    <div class=\"bk10\"></div>\r\n    <div>\r\n        <div class=\"lf tar wid150\">地图位置：</div>\r\n        <div class=\"lf\">\r\n            <input type=\"text\" name=\"map_path\" class=\"input-text\" value=\"/\">\r\n            <span>填入地图存放的位置，默认为网站根目录,以\"/\"结尾</span>\r\n        </div>\r\n        <div class=\"cl\"></div>\r\n    </div>\r\n    <div class=\"bk10\"></div>\r\n\r\n    <div>\r\n        <div class=\"lf tar wid150\">地图内容：</div>\r\n        <div class=\"lf\">\r\n            <input type=\"hidden\" name=\"map_link\" id=\"map_link\" value=\"";
echo $web["map_link"];
echo "\">\r\n            <script language=\"javascript\">yg_checkbox(";
echo $link;
echo ",\"map_link\",\"\");</script>\r\n        </div>\r\n        <div class=\"cl\"></div>\r\n    </div>\r\n    <div class=\"bk10\"></div>\r\n    <div>\r\n        <div class=\"lf tar wid150\">&nbsp;</div>\r\n        <div class=\"lf\">\r\n            <input type=\"submit\" value=\" 提交 \" name=\"dosubmit\" class=\"button\">\r\n        </div>\r\n        <div class=\"cl\"></div>\r\n    </div>\r\n</form>\r\n</body>\r\n</html> ";

?>
