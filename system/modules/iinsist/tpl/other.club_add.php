
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
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
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n<style>\r\nbody{ background-color:#fff}\r\n.textarea{width:400px;height:100px;}\r\ninput.button{ display:inline-block}\r\n</style>\r\n</head>\r\n<body>\r\n<script language=\"JavaScript\">\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n$(function(){\r\n\t$(\"form\").submit(function(){\r\n\t\tvar title=$(\"#title\").val();\r\n\t\tvar img=$(\"#img\").val();\r\n\t\tif(title.length<1){\r\n\t\t\talert(\"圈子名不能为空\");\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\treturn true;\r\n\t});\r\n})\r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"myform\">\r\n<table width=\"100%\" >\r\n    <tr>\r\n    \t<td width=\"120\" align=\"right\">圈子名：</td> \r\n   \t\t<td><input type=\"text\" name=\"title\"  class=\"input-text\" id=\"title\" value=\"";
echo $quanzi["title"];
echo "\"></td>\r\n    </tr>\r\n    <tr>\r\n    \t<td width=\"120\" align=\"right\">圈子管理员：</td>\r\n    \t<td>\r\n\t\t\t<input type=\"text\" name=\"guanli\" value=\"";
echo get_user_field($quanzi["guanli"], "email");
echo "\" class=\"input-text\">(请填写邮箱/手机)\r\n\t\t</td>\r\n    </tr>\r\n\t<tr>\r\n    \t<td width=\"120\" align=\"right\">申请加入圈子：</td>\r\n    \t<td>\r\n            <input type=\"hidden\" name=\"jiaru\" id=\"jiaru\" value=\"";
echo $quanzi["jiaru"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|关闭,开启\",\"txt\",\"jiaru\",\"";
echo $quanzi["jiaru"];
echo "\");</script>\r\n\t\t<span style=\" padding-left:30px;\" class=\"lh30\">普通会员是否可以加入圈子</span></td>\r\n\t</tr>\r\n\t<tr>\r\n    \t<td width=\"120\" align=\"right\">发帖权限：</td>\r\n    \t<td>\r\n            <input type=\"hidden\" name=\"glfatie\" id=\"glfatie\" value=\"";
echo $quanzi["glfatie"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|关闭,开启\",\"txt\",\"glfatie\",\"";
echo $quanzi["glfatie"];
echo "\");</script>\r\n\t\t<span style=\" padding-left:30px\" class=\"lh30\">普通会员是否可以发帖</span></td>\r\n\t</tr>\r\n\t<tr>\r\n    \t<td width=\"120\" align=\"right\">帖子是否可回复：</td>\r\n    \t<td>\r\n            <input type=\"hidden\" name=\"huifu\" id=\"huifu\" value=\"";
echo $quanzi["huifu"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|关闭,开启\",\"txt\",\"huifu\",\"";
echo $quanzi["huifu"];
echo "\");</script>\r\n\t\t<span style=\" padding-left:30px\" class=\"lh30\">圈子里面的帖子是否可回复</span></td>\r\n\t</tr>\r\n\t<tr>\r\n    \t<td width=\"120\" align=\"right\">帖子回复审核：</td>\r\n    \t<td>\r\n            <input type=\"hidden\" name=\"shenhe\" id=\"shenhe\" value=\"";
echo $quanzi["shenhe"];
echo "\">\r\n            <script language=\"javascript\">yg_close(\"N,Y|关闭,开启\",\"txt\",\"shenhe\",\"";
echo $quanzi["shenhe"];
echo "\");</script>\r\n\t\t<span style=\" padding-left:30px\" class=\"lh30\">帖子和回复是否需要审核才能显示</span></td>\r\n\t</tr>\r\n    <tr>\r\n    \t<td width=\"120\" align=\"right\">圈子头像：</td>\r\n    \t<td>\r\n           \t<input type=\"text\" id=\"imagetext\" name=\"img\" value=\"";
echo $quanzi["img"];
echo "\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','圈子头像','photo',1,'imagetext')\"\r\n             value=\"上传图片\"/>\t\t\t\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n    \t<td width=\"120\" align=\"right\">圈子介绍：</td>\r\n    \t<td><textarea  name=\"jianjie\" class=\"textarea\"  onKeyUp=\"gbcount(this,200,'textdescription');\">";
echo $quanzi["jianjie"];
echo "</textarea>\r\n            <br> <span>还能输入<b id=\"textdescription\">200</b>个字符</span>\r\n        </td>\r\n\t</tr>\r\n\t<tr>\r\n    \t<td width=\"120\" align=\"right\">圈子公告：</td>\r\n    \t<td><textarea  name=\"gongao\" class=\"textarea\">";
echo $quanzi["gongao"];
echo "</textarea></td>\r\n\t</tr>\r\n</table>\r\n   \t<div class=\"bk15\"></div>\r\n\t<input class=\"button\" type=\"submit\" name=\"submit\" value=\"提交\" />\r\n</form>\r\n</div>\r\n</body>\r\n</html>\r\n<script language=\"javascript\">\r\n    var info=new Array();\r\n    function gbcount(message,maxlen,id){\r\n\r\n        if(!info[id]){\r\n            info[id]=document.getElementById(id);\r\n        }\r\n        var lenE = message.value.length;\r\n        var lenC = 0;\r\n        var enter = message.value.match(/\\r/g);\r\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\r\n        if (CJK != null) lenC += CJK.length;\r\n        if (enter != null) lenC -= enter.length;\r\n        var lenZ=lenE+lenC;\r\n        if(lenZ > maxlen){\r\n            info[id].innerHTML=''+0+'';\r\n            return false;\r\n        }\r\n        info[id].innerHTML=''+(maxlen-lenZ)+'';\r\n    }\r\n</script>\r\n";

?>
