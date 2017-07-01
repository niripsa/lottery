
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n <style>\r\n \tth{ border:0px solid #000;}\r\n </style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ment);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n            <tr>\r\n            <th width=\"90\">排序</th>\r\n            <th width=\"60\">catid</th>\r\n            <th align='center'>栏目名称</th>\r\n            <th align='center' width='70'>栏目类型</th>\r\n            <th align='center' width=\"70\">所属模型</th>\r\n            <th align='center'>访问地址</th>\r\n\t\t\t<th align='center'>管理操作</th>\r\n            </tr>\r\n    </thead>\r\n    <tbody>\r\n    \t  <form action=\"#\" method=\"post\" name=\"myform\">\r\n          ";
echo $html;
echo "          </form>\r\n    </tbody>\r\n  </table>\r\n  <div class=\"btn_paixu\">\r\n  \t<div style=\"width:80px; text-align:center;\">\r\n        <input type=\"button\" class=\"button\" value=\" 排序 \"\r\n        onclick=\"myform.action='";
echo G_MODULE_PATH;
echo "/cate/listorder/";
echo $cate_type;
echo "/dosubmit';myform.submit();\"/>\r\n    </div>\r\n  </div>\r\n</div><!--table-list end-->\r\n<script>\r\n//window.parent.message(\"3443\",8,20);\r\n</script>\r\n</body>\r\n</html> ";

?>
