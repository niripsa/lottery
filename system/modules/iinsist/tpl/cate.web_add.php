
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
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx con-tab lr10\" id=\"con-tab\">\r\n    <div class=\"ml10 ft14 fwb\">基本选项</div>\r\n<div name='con-tabv' class=\"con-tabv\"> \r\n<form id=\"form\" method=\"post\" enctype=\"multipart/form-data\">\r\n <table width=\"100%\" class=\"table_form\">\r\n <tbody>\r\n      <tr>\r\n        <th width=\"120\">上级栏目：</th>\r\n        <td>\r\n\t\t<select name=\"info[parentid]\" id=\"parentid\">\r\n        <option value=\"0\">≡ 作为一级栏目 ≡</option>\r\n        ";
echo $categoryshtml;
echo "        </select></td>\r\n      </tr>     \r\n      <tr>\r\n        <th>栏目名称：</th>\r\n        <td><input type=\"text\" name=\"info[name]\" class=\"input-text\" value=\"";
echo $cateinfo["name"];
echo "\">\r\n        \t<span><font color=\"#0c0\" size=\"\">※ </font>请输入栏目名称</span>\r\n\t\t</td>\r\n      </tr>\r\n\t<tr id=\"catdir_tr\">\r\n      <th>英文目录：</th>\r\n        <td><input type=\"text\" name=\"info[catdir]\" value=\"";
echo $cateinfo["catdir"];
echo "\"\r\n         onKeyUp=\"value=value.replace(/[^\w]/ig,'')\"    class=\"input-text\">\r\n        <span><font color=\"#0c0\" size=\"\">※ </font>请输入目录名称</span> \r\n      </tr>     \r\n\t<tr>\r\n      <th>栏目图片：</th>\r\n        <td>\r\n           \t<input type=\"text\" id=\"imagetext\" name=\"thumb\" urls=\"1\" value=\"";
echo $cateinfo["info"]["thumb"];
echo "\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','栏目图片上传','photo',1,'imagetext')\"\r\n             value=\"上传图片\"/>\r\n        </td>\r\n      </tr>\r\n      <tr><td><div class=\"ml10 ft14 fwb\">SEO设置</div></td><td></td></tr>\r\n      <tr>\r\n          <th width=\"120\">栏目标题：</th>\r\n          <td><input name=\"setting[meta_title]\" type=\"text\" id=\"meta_title\" value=\"";
echo $cateinfo["info"]["meta_title"];
echo "\" size=\"60\" maxlength=\"60\" class=\"input-text\"></td>\r\n      </tr>\r\n      <tr>\r\n          <th>栏目关键词：</th>\r\n          <td><textarea name=\"setting[meta_keywords]\" id=\"meta_keywords\" style=\"width:900px;height:40px\">";
echo $cateinfo["info"]["meta_keywords"];
echo "</textarea></td>\r\n      </tr>\r\n      <tr>\r\n          <th>栏目描述：</th>\r\n          <td><textarea name=\"setting[meta_description]\" id=\"meta_description\" style=\"width:900px;height:50px\">";
echo $cateinfo["info"]["meta_description"];
echo "</textarea></td>\r\n      </tr>\r\n      <tr>\r\n          <th width=\"120\">网页内容：</th>\r\n          <td style=\"margin:10px; width: 900px;\">\r\n                  <script id=\"myeditor\" type=\"text/plain\">";
echo base64_decode($cateinfo["info"]["content"]);
echo "</script>\r\n                  <textarea name=\"setting[content]\" id=\"settingcontent\" style=\"display:none\"></textarea>\r\n\r\n          </td>\r\n      </tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n\r\n</div>\r\n<!--table-list end-->\r\n\r\n   <div class=\"table-button lr10\">\r\n    \t  <input type=\"button\" value=\" 修改 \" onClick=\"checkform();\" class=\"button\">\r\n    \t</form>\r\n   </div>\r\n<script type=\"text/javascript\">\r\nfunction upImage(){\r\n\treturn document.getElementById('imgfield').click();\r\n}\r\n\r\nfunction checkform(){\r\n\tvar form=document.getElementById('form');\r\n\tvar error=null;\r\n\tif(form.elements[1].value==''){error='请输入栏目名称!';}\r\n\tif(form.elements[2].value==''){error='请输入英文目录名称!';}\r\n\r\n\tif(error!=null){window.parent.message(error,8,2);return false;}\r\n\tvar Content=getContent();\r\n\tdocument.getElementById('settingcontent').value=Content;\r\n\tform.submit();\t\r\n}\r\n</script>\r\n<script type=\"text/javascript\">\r\nvar editurl=Array();\r\nediturl['editurl']='";
echo G_PLUGIN_PATH;
echo "/ueditor/';\r\nediturl['imageupurl']='";
echo G_ADMIN_PATH;
echo "/ueditor/upimage/';\r\nediturl['imageManager']='";
echo G_ADMIN_PATH;
echo "/ueditor/imagemanager';\r\n</script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.config.js\"></script>\r\n<script type=\"text/javascript\">\r\nwindow.UEDITOR_CONFIG.initialContent=\"输入栏目内容...\";\r\nwindow.UEDITOR_CONFIG.initialFrameWidth='';\r\nwindow.UEDITOR_CONFIG.initialFrameHeight=450;\r\n</script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.all.min.js\"></script>\r\n<script type=\"text/javascript\">\r\n    //实例化编辑器\r\n    var ue = UE.getEditor('myeditor');\r\n    ue.addListener('ready',function(){\r\n        this.focus()\r\n    });\r\n    function getContent() {\r\n        return ue.getContent();\r\n    }\r\n    function hasContent() {\r\n        var arr = [];\r\n        arr.push( \"使用editor.hasContents()方法判断编辑器里是否有内容\" );\r\n        arr.push( \"判断结果为：\" );\r\n        arr.push(  UE.getEditor('myeditor').hasContents() );\r\n        alert( arr.join( \"\\n\" ) );\r\n    }\r\n</script>\r\n\r\n</body>\r\n</html> ";

?>
