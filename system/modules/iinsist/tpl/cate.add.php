
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n\t";
echo headerment($ment);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n    <div class=\"ml10 ft14 fwb\">基本选项</div>\r\n    <div name='con-tabv' class=\"con-tabv\">\r\n         <form action=\"\" id=\"form\" method=\"post\" enctype=\"multipart/form-data\">\r\n         <table width=\"100%\">\r\n              <tr>\r\n              <td width=\"200\" class=\"tar\">上级栏目：</td>\r\n              <td>\r\n              <select name=\"info[parentid]\" class=\"wid150\">\r\n              <option value=\"\">≡ 作为一级栏目 ≡</option>\r\n              ";
echo $categoryshtml;
echo "              </select>\r\n              </td>\r\n              </tr>\r\n              <tr>\r\n                <td class=\"tar\">栏目名称：</td>\r\n                <td><input type=\"text\" name=\"info[name]\" class=\"input-text wid140\" value=\"";
echo $cateinfo["name"];
echo "\">\r\n                    <span><font color=\"#0c0\">※ </font>请输入栏目名称</span>\r\n                </td>\r\n              </tr>\r\n            <tr>\r\n              <td class=\"tar\">英文名称：</td>\r\n                <td><input type=\"text\" name=\"info[catdir]\"  value=\"";
echo $cateinfo["catdir"];
echo "\"  onKeyUp=\"value=value.replace(/[^\w]/ig,'')\" class=\"input-text wid140\">\r\n                <span><font color=\"#0c0\">※ </font>请输入英文名称</span>\r\n              </tr>\r\n            <tr>\r\n              <td class=\"tar\">栏目图片：</td>\r\n                <td>\r\n                   <input type=\"text\" id=\"imagetext\" name=\"thumb\" value=\"";
echo $cateinfo["info"]["thumb"];
echo "\" class=\"input-text wid300\">\r\n                   <input type=\"button\" class=\"button\"  onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','栏目图片上传','photo',1,'imagetext')\" value=\"上传图片\"/>\r\n                </td>\r\n              </tr>\r\n              <tr><td><div class=\"ml10 ft14 fwb\">SEO 设置</div></td><td></td></tr>\r\n              <tr>\r\n                  <td width=\"200\" class=\"tar\">栏目标题：</td>\r\n                  <td><input name=\"setting[meta_title]\" type=\"text\" id=\"meta_title\" value=\"";
echo $cateinfo["info"]["meta_title"];
echo "\" size=\"60\" maxlength=\"60\" class=\"input-text\"></td>\r\n              </tr>\r\n              <tr>\r\n                  <td class=\"tar\">栏目关键词：</td>\r\n                  <td><textarea name=\"setting[meta_keywords]\" id=\"meta_keywords\" style=\"width:90%;height:40px\">";
echo $cateinfo["info"]["meta_keywords"];
echo "</textarea></td>\r\n              </tr>\r\n              <tr>\r\n                  <td class=\"tar\">栏目描述：</td>\r\n                  <td><textarea name=\"setting[meta_description]\" id=\"meta_description\" style=\"width:90%;height:50px\">";
echo $cateinfo["info"]["meta_description"];
echo "</textarea></td>\r\n              </tr>\r\n         </table>\r\n         <div class=\"table-button lr10\">   <input type=\"button\" value=\" 修改 \" onClick=\"checkform();\" class=\"button\">  </div>\r\n        </form>\r\n    </div>\r\n</div>\r\n<script type=\"text/javascript\">\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\nfunction checkform(){\r\n\tvar form=document.getElementById('form');\r\n\tvar error=null;\r\n\tif(form.elements[1].value==''){error='请输入栏目名称!';}\r\n\tif(form.elements[2].value==''){error='请输入英文目录名称!';}\r\n\tif(error!=null){window.parent.message(error,8,2);return false;}\r\n\tform.submit();\t\r\n}\r\n\r\n</script>\r\n</body>\r\n</html> ";

?>
