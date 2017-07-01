
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title>后台首页</title>\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script> \n<link rel=\"stylesheet\" href=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar-blue.css\" type=\"text/css\"> \n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar.js\"></script>\n<script type=\"text/javascript\">\nvar editurl=Array();\nediturl['editurl']='";
echo G_PLUGIN_PATH;
echo "/ueditor/';\nediturl['imageupurl']='";
echo G_ADMIN_PATH;
echo "/ueditor/upimage/';\nediturl['imageManager']='";
echo G_ADMIN_PATH;
echo "/ueditor/imagemanager';\n</script>\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.config.js\"></script>\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.all.min.js\"></script>\n<style>\n\t.bg{background:#fff url(";
echo G_GLOBAL_STYLE;
echo "/global/image/ruler.gif) repeat-x scroll 0 9px }\n\t.color_window_td a{ float:left; margin:0px 10px;}\n</style>\n</head>\n<body>\n<script>\n$(function(){\n\t$(\"#category\").change(function(){ \n\tvar parentId=$(\"#category\").val(); \n\tif(null!= parentId && \"\"!=parentId){\n\t\t$.getJSON(\"";
echo G_ADMIN_PATH;
echo "/goods/json_brand/\"+parentId,{cid:parentId},function(myJSON){\n\t\tvar options=\"\";\n\t\tif(myJSON.length>0){ \t\t\t\n\t\t\t//options+='<option value=\"0\">≡ 请选择品牌 ≡</option>'; \n\t\t\tfor(var i=0;i<myJSON.length;i++){ \n\t\t\t\toptions+=\"<option value=\"+myJSON[i].id+\">\"+myJSON[i].name+\"</option>\"; \n\t\t\t} \n\t\t\t$(\"#brand\").html(options);\t\t} \n\t\t}); \n\t}  \n\t}); \t\n}); \n\nfunction CheckForm(){\n\tvar money = $.trim($(\"#money\").val());\n    if(money==''){\n        window.parent.message(\"请填写商品价格!\");\n        return false;\n    }\n}\n</script>\n<div class=\"header lr10\">\n    ";
echo headerment($ments);
echo "</div>\n<div class=\"bk10\"></div>\n<div class=\"table-listx lr10\">\n<form method=\"post\" action=\"\" onSubmit=\"return CheckForm()\">\n\t<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\n\t\t<tr>\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>所属分类：</td>\n\t\t\t<td>\n            <select id=\"category\" name=\"g_cateid\">\n                ";
echo $categoryshtml;
echo "                \n             </select> \n            </td>\n\t\t</tr>\n        <tr>\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>所属品牌：</td>\n\t\t\t<td>\n            \t<select id=\"brand\" name=\"g_brandid\">\n                \t<option value=\"0\">≡ 请选择品牌 ≡</option>\n\t\t\t\t</select>\n\t\t\t</td>\n\t\t</tr>      \n        <tr>\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>商品标题：</td>\n\t\t\t<td>\n            <input  type=\"text\" id=\"title\"  name=\"g_title\" onKeyUp=\"return gbcount(this,100,'texttitle');\"  class=\"input-text wid400 bg\">\n                <input type=\"hidden\" name=\"title_style_color\" id=\"title_style_color\"/>\n                <input type=\"hidden\" name=\"title_style_bold\" id=\"title_style_bold\"/>\n                <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/colorpicker.js\"></script>\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/colour.png\" width=\"15\" height=\"16\" onClick=\"colorpicker('title_colorpanel','set_title_color');\" style=\"cursor:hand\"/>\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/bold.png\" onClick=\"set_title_bold();\" style=\"cursor:hand\"/>\n            <span style=\"margin-left:10px\">还能输入<b id=\"texttitle\">100</b>个字符</span>\n           \n            </td>\n\t\t</tr>\n        <tr>\n\t\t\t<td align=\"right\" style=\"width:120px\">关键字：</td>\n\t\t\t<td><input type=\"text\" name=\"g_keyword\"  name=\"title\"  class=\"input-text wid300\" />\n            <span class=\"lr10\">多个关键字请用   ,  号分割开</span>\n            </td>\n\t\t</tr>\n        <tr>\n\t\t\t<td align=\"right\" style=\"width:120px\">商品描述：</td>\n\t\t\t<td><textarea name=\"g_description\" class=\"wid400\" onKeyUp=\"gbcount(this,250,'textdescription');\" style=\"height:60px\"></textarea><br /> <span>还能输入<b id=\"textdescription\">250</b>个字符</span>\n            </td>\n\t\t</tr>\n\t\t<tr>\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>商品价格：</td>\n\t\t\t<td><input type=\"text\" id=\"money\"  name=\"g_money\" onKeyUp=\"value=value.replace(/\D/g,'')\" style=\"width:65px; padding-left:0px; text-align:center\" class=\"input-text\">元</td>\n\t\t</tr>\n        <tr>\n         <td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>缩略图：</td>\n        <td>\n        \t<img src=\"";
echo G_UPLOAD_PATH;
echo "/photo/goods.jpg\" style=\"border:1px solid #eee; padding:1px; width:50px; height:50px;\">\n           \t<input type=\"text\" id=\"imagetext\" name=\"g_thumb\" value=\"photo/goods.jpg\" class=\"input-text wid300\">\n\t\t\t<input type=\"button\" class=\"button\"\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','goods',1,'imagetext')\"\n             value=\"上传图片\"/>\n\t\t\t\n        </td>\n      </tr>\n        <tr>\n\t\t\t<td align=\"right\" style=\"width:120px\">展示图片：</td>            \n\t\t\t<td><fieldset class=\"uploadpicarr\">\n\t\t\t\t\t<legend>列表</legend>\n\t\t\t\t\t<div class=\"picarr-title\">最多可以上传<strong>10</strong> 张图片 <a onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','goods',10,'uppicarr')\" style=\"color:#ff0000; padding:10px;\">  <input type=\"button\" class=\"button\" value=\"开始上传\" /></a>\n                    </div>\n\t\t\t\t\t<ul id=\"uppicarr\" class=\"upload-img-list\"></ul>\n\t\t\t\t</fieldset>\n             </td>           \n\t\t</tr>        \n\t\t<tr>\n        \t<td height=\"300\" style=\"width:120px\"   align=\"right\"><font color=\"red\">*</font>商品内容详情：</td>\n\t\t\t<td>\n\t\t\t\t <script name=\"g_content\" id=\"myeditor\" type=\"text/plain\"></script>\n\n            \t\n            </td>        \n\t\t</tr>\n        <tr>\n            <td align=\"right\" style=\"width:120px\">福分购买：</td>\n            <td width=\"900\">\n                <input name=\"g_ispoints\" value=\"1\" type=\"checkbox\" ";

if ($shopinfo["g_ispoints"] == 1) {
    echo "checked";
}

echo "/>&nbsp;支持福分购买\n            </td>\n        </tr>\n        <tr>\n        \t<td align=\"right\" style=\"width:120px\">商品属性：</td>\n            <td width=\"900\">\n\t\t\t <input name=\"g_style[]\" value=\"2\" type=\"checkbox\" />&nbsp;推荐&nbsp;&nbsp;\n\t\t\t <input name=\"g_style[]\" value=\"1\" type=\"checkbox\" />&nbsp;人气&nbsp;&nbsp;\n            </td>          \n        </tr>\n\n        <tr height=\"60px\">\n\t\t\t<td align=\"right\" style=\"width:120px\"></td>\n\t\t\t<td><input type=\"submit\" name=\"dosubmit\" class=\"button\" value=\"添加商品\" /></td>\n\t\t</tr>\n\t</table>\n</form>\n</div>\n <span id=\"title_colorpanel\" style=\"position:absolute; left:568px; top:155px\" class=\"colorpanel\"></span>\n<script type=\"text/javascript\">\n    //实例化编辑器\n    var ue = UE.getEditor('myeditor');\n    ue.addListener('ready',function(){\n        this.focus()\n    });\n\tvar info=new Array();\n    function gbcount(message,maxlen,id){ \n\t\tif(!info[id]){\n\t\t\tinfo[id]=document.getElementById(id);\n\t\t}\n        var lenE = message.value.length;\n        var lenC = 0;\n        var enter = message.value.match(/\\r/g);\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\n        if (CJK != null) lenC += CJK.length;\n        if (enter != null) lenC -= enter.length;\t\t\n\t\tvar lenZ=lenE+lenC;\t\t\n\t\tif(lenZ > maxlen){\n\t\t\tinfo[id].innerHTML=''+0+'';\n\t\t\treturn false;\n\t\t}\n\t\tinfo[id].innerHTML=''+(maxlen-lenZ)+'';\n    }\n\t\nfunction set_title_color(color) {\n\t$('#title2').css('color',color);\n\t$('#title_style_color').val(color);\n}\nfunction set_title_bold(){\n\tif($('#title_style_bold').val()=='bold'){\n\t\t$('#title_style_bold').val('');\t\n\t\t$('#title2').css('font-weight','');\n\t}else{\n\t\t$('#title2').css('font-weight','bold');\n\t\t$('#title_style_bold').val('bold');\n\t}\n}\n\n//API JS\n//window.parent.api_off_on_open('open');\n</script>\n</body>\n</html> ";

?>
