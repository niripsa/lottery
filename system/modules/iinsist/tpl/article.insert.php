
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar-blue.css\" type=\"text/css\"> \r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar.js\"></script>\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script> \r\n<script type=\"text/javascript\">\r\nvar editurl=Array();\r\nediturl['editurl']='";
echo G_PLUGIN_PATH;
echo "/ueditor/';\r\nediturl['imageupurl']='";
echo G_ADMIN_PATH;
echo "/ueditor/upimage/';\r\nediturl['imageManager']='";
echo G_ADMIN_PATH;
echo "/ueditor/imagemanager';\r\n</script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.config.js\"></script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.all.min.js\"></script>\r\n<style>\r\n\t.bg{background:#fff url(";
echo G_GLOBAL_STYLE;
echo "/global/image/ruler.gif) repeat-x scroll 0 9px }\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<form method=\"post\" action=\"\">\r\n\t<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" width=\"120\"><font color=\"red\">*</font>所属分类：</td>\r\n\t\t\t<td>            \r\n\t\t\t\t<select name=\"cateid\">               \r\n                ";
echo $categoryshtml;
echo "                \r\n                </select>            \t\r\n            </td>\r\n\t\t</tr>        \r\n\t\t<tr>\r\n\t\t\t<td align=\"right\"><font color=\"red\">*</font>文章标题：</td>\r\n\t\t\t<td><input  type=\"text\"  name=\"title\" id=\"title\" onKeyUp=\"return gbcount(this,100,'texttitle');\"  class=\"input-text wid400 bg\">\r\n           <input type=\"hidden\" name=\"title_color\" id=\"title_style_color\"/>\r\n            <input type=\"hidden\" name=\"title_bold\" id=\"title_style_bold\"/>\r\n            <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/colorpicker.js\"></script>\r\n            <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/colour.png\" width=\"15\" height=\"16\" onClick=\"colorpicker('title_colorpanel','set_title_color');\" style=\"cursor:hand\"/>\r\n             <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/bold.png\" onClick=\"set_title_bold();\" style=\"cursor:hand\"/>\r\n            <span style=\"margin-left:10px\">还能输入<b id=\"texttitle\">100</b>个字符</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\">作者：</td>\r\n\t\t\t<td><input  type=\"text\"  name=\"author\" class=\"input-text wid100\" /></td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\">关键字：</td>\r\n\t\t\t<td><input type=\"text\" name=\"keywords\"  name=\"title\"  class=\"input-text wid400\">\r\n            <span>多个关键字请用   ,  号分割开</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\">摘要：</td>\r\n\t\t\t<td><textarea name=\"description\" class=\"wid400\" onKeyUp=\"gbcount(this,250,'textdescription');\" style=\"height:60px\"></textarea><br> <span>还能输入<b id=\"textdescription\">250</b>个字符</span>\r\n            </td>\r\n\t\t</tr>\r\n     <tr>\r\n      <td align=\"right\">缩略图：</td>\r\n        <td>\r\n           \t<input type=\"text\" id=\"imagetext\" name=\"thumb\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','photo',1,'imagetext')\" value=\"上传图片\"/>\r\n\t\t\t\r\n        </td>                                                                                                                                                                                                                                                                                                                                                                                                                                                               \r\n      </tr>\r\n\t\t<tr>\r\n\t\t\t<td height=\"300\"  align=\"right\"><font color=\"red\">*</font>内容详情：</td>\r\n\t\t\t<td><script name=\"content\" id=\"myeditor\" type=\"text/plain\"></script>\r\n           \t\t <style>\r\n\t\t\t\t.content_attr {\r\n\t\t\t\t\tborder: 1px solid #CCC;\r\n\t\t\t\t\tpadding: 5px 8px;\r\n\t\t\t\t\tbackground: #FFC;\r\n\t\t\t\t\tmargin-top: 6px;\r\n\t\t\t\t\twidth:915px;\r\n\t\t\t\t}\r\n\t\t\t\t</style>\r\n            \t <div class=\"content_attr\">\r\n                <label><input name=\"sub_text_des\" type=\"checkbox\"  value=\"off\" checked />是否截取内容</label>\r\n                <input type=\"text\" name=\"sub_text_len\" class=\"input-text\" value=\"250\" size=\"3\">字符至内容摘要<label>         \r\n            \t</div>\r\n            </td>  \r\n\t\t</tr>        \r\n        <tr>\r\n\t\t\t<td height=\"124\" align=\"right\">组　图：</td>\r\n\t\t\t<td><fieldset class=\"uploadpicarr\">\r\n\t\t\t\t\t<legend>列表</legend>\r\n\t\t\t\t\t<div class=\"picarr-title\">最多可以上传<strong>50</strong> 张图片 <a onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','photo',50,'picarr')\" style=\"color:#ff0000; padding:10px;\">  <input type=\"button\" class=\"button\" value=\"开始上传\" /></a>\r\n                    </div>\r\n\t\t\t\t\t<ul id=\"picarr\" class=\"upload-img-list\"></ul>\t\t\t\t\r\n\t\t\t\t</fieldset>\r\n             </td>\r\n\t\t</tr>\r\n       <tr>\r\n\t\t\t<td align=\"right\">发布时间：</td>\r\n\t\t\t<td>           \r\n            \t<input name=\"posttime\" type=\"text\" id=\"posttime\" value=\"";
echo date("Y-m-d H:i:s");
echo "\" class=\"input-text posttime\"  readonly=\"readonly\" />\r\n\t\t\t\t<script type=\"text/javascript\">\r\n\t\t\t\tdate = new Date();\r\n\t\t\t\tCalendar.setup({\r\n\t\t\t\t\tinputField     :    \"posttime\",\r\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d %H:%M:%S\",\r\n\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t});\r\n\t\t\t\t</script></td>             \r\n\t\t</tr> \r\n        <tr>\r\n\t\t\t<td align=\"right\">点击量：</td>\r\n            <td><input type=\"text\" name=\"hit\" class=\"input-text wid50\" value=\"";
echo rand(1, 100);
echo "\"/></td>\r\n\t\t\t<td>\r\n            </td>             \r\n\t\t</tr>         \r\n        <tr height=\"60px\">\r\n\t\t\t<td align=\"right\"></td>\r\n\t\t\t<td><input type=\"submit\" name=\"dosubmit\" class=\"button\" value=\"添加文章\" /></td>\r\n\t\t</tr>\r\n\t</table>\r\n</form>\r\n</div>\r\n<span id=\"title_colorpanel\" style=\"position:absolute; left:568px; top:115px\" class=\"colorpanel\"></span>\r\n<script type=\"text/javascript\">\r\n    //实例化编辑器\r\n    var ue = UE.getEditor('myeditor');\r\n    ue.addListener('ready',function(){\r\n        this.focus()\r\n    });\r\n\t\r\n\tvar info=new Array();\r\n    function gbcount(message,maxlen,id){\r\n\t\t\r\n\t\tif(!info[id]){\r\n\t\t\tinfo[id]=document.getElementById(id);\r\n\t\t}\t\t\t\r\n        var lenE = message.value.length;\r\n        var lenC = 0;\r\n        var enter = message.value.match(/\\r/g);\r\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\r\n        if (CJK != null) lenC += CJK.length;\r\n        if (enter != null) lenC -= enter.length;\t\t\r\n\t\tvar lenZ=lenE+lenC;\t\t\r\n\t\tif(lenZ > maxlen){\r\n\t\t\tinfo[id].innerHTML=''+0+'';\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\tinfo[id].innerHTML=''+(maxlen-lenZ)+'';\r\n    }\r\n\t\r\nfunction set_title_color(color) {\r\n\t$('#title').css('color',color);\r\n\t$('#title_style_color').val(color);\r\n}\r\n\r\nfunction set_title_bold(){\r\n\tif($('#title_style_bold').val()=='bold'){\r\n\t\t$('#title_style_bold').val('');\t\r\n\t\t$('#title').css('font-weight','');\r\n\t}else{\r\n\t\t$('#title').css('font-weight','bold');\r\n\t\t$('#title_style_bold').val('bold');\r\n\t}\r\n}\r\n\r\n\t//API JS\r\n\t//window.parent.api_off_on_open('open');\r\n</script>\r\n</body>\r\n</html> ";

?>
