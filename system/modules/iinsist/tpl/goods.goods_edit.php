
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/api-uploadify.js\" type=\"text/javascript\"></script> \r\n<link rel=\"stylesheet\" href=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar-blue.css\" type=\"text/css\"> \r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/calendar/calendar.js\"></script>\r\n <script type=\"text/javascript\" src=\"";
echo G_PLUGIN_PATH;
echo "/kindeditor/kindeditor.js\"></script>\r\n\t    <!-- 编辑器源码文件 -->\r\n<script type=\"text/javascript\" src=\"";
echo G_PLUGIN_PATH;
echo "/kindeditor/lang/zh_CN.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar editurl=Array();\r\nediturl['editurl']='";
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
echo "/global/image/ruler.gif) repeat-x scroll 0 9px }\r\n\t.color_window_td a{ float:left; margin:0px 10px;}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\n$(function(){\r\n\t$(\"#category\").change(function(){ \r\n\tvar parentId=$(\"#category\").val(); \r\n\tif(null!= parentId && \"\"!=parentId){ \r\n\t\t$.getJSON(\"";
echo G_ADMIN_PATH;
echo "/goods/json_brand/\"+parentId,{cid:parentId},function(myJSON){\r\n\t\tvar options=\"\";\r\n\t\tif(myJSON.length>0){ \t\t\t\r\n\t\t\t//options+='<option value=\"0\">≡ 请选择品牌 ≡</option>'; \r\n\t\t\tfor(var i=0;i<myJSON.length;i++){ \r\n\t\t\t\toptions+=\"<option value=\"+myJSON[i].id+\">\"+myJSON[i].name+\"</option>\"; \r\n\t\t\t} \r\n\t\t\t$(\"#brand\").html(options);\t\t} \r\n\t\t}); \r\n\t}  \r\n\t}); \r\n}); \r\n</script>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-listx lr10\">\r\n<form method=\"post\" action=\"\">\r\n\t<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><span color=\"red\">*</span>所属分类：</td>\r\n\t\t\t<td>\r\n             <select name=\"g_cateid\" id=\"category\" class=\"wid100\">\r\n                ";
echo $categoryshtml;
echo "                \r\n             </select> \r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>所属品牌：</td>\r\n\t\t\t<td>\r\n            \t<select id=\"brand\" name=\"g_brandid\" class=\"wid100\">\r\n                \t<option value=\"";
echo $shopinfo["g_brandid"];
echo "\">";
echo $BrandList[$shopinfo["g_brandid"]]["name"];
echo "</option>\r\n\t\t\t\t\t";

foreach ($BrandList as $brand ) {
    echo "\t\t\r\n                    <option value=\"";
    echo $brand["id"];
    echo "\">";
    echo $brand["name"];
    echo "</option>\r\n                    ";
}

echo "\t\t\t\t</select>\r\n\t\t\t</td>\r\n\t\t</tr>      \r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>商品标题：</td>\r\n\t\t\t<td>\r\n            <input  type=\"text\" id=\"title\" value=\"";
echo $shopinfo["g_title"];
echo "\"\r\n             name=\"g_title\" onKeyUp=\"return gbcount(this,100,'texttitle');\"  class=\"input-text wid400 bg\">\r\n                <input type=\"hidden\"  value=\"";
echo $title_color;
echo "\"   name=\"title_style_color\" id=\"title_style_color\"/>\r\n                <input type=\"hidden\" value=\"";
echo $title_bold;
echo "\"  name=\"title_style_bold\" id=\"title_style_bold\"/>\r\n                <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/colorpicker.js\"></script>\r\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/colour.png\" width=\"15\" height=\"16\" onClick=\"colorpicker('title_colorpanel','set_title_color');\" style=\"cursor:hand\"/>\r\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/bold.png\" onClick=\"set_title_bold();\" style=\"cursor:hand\"/>\r\n            <span style=\"margin-left:10px\">还能输入<b id=\"texttitle\">100</b>个字符</span>\r\n           \r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">关键字：</td>\r\n\t\t\t<td><input type=\"text\" value=\"";
echo $shopinfo["g_keyword"];
echo "\" name=\"g_keyword\"   class=\"input-text wid300\" />\r\n            <span class=\"lr10\">多个关键字请用   ,  号分割开</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">商品描述：</td>\r\n\t\t\t<td><textarea name=\"g_description\" class=\"wid400\"  onKeyUp=\"gbcount(this,250,'textdescription');\" style=\"height:60px\">";
echo $shopinfo["g_description"];
echo "</textarea><br /> <span>还能输入<b id=\"textdescription\">250</b>个字符</span>\r\n            </td>\r\n\t\t</tr>\t\r\n\r\n        <tr>\r\n         <td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>缩略图：</td>\r\n        <td>\r\n\t\t\t<img src=\"";
echo G_UPLOAD_PATH . "/" . $shopinfo["g_thumb"];
echo "\" style=\"border:1px solid #eee; padding:1px; width:50px; height:50px;\">\r\n           \t<input type=\"text\" id=\"imagetext\" value=\"";
echo $shopinfo["g_thumb"];
echo "\" name=\"g_thumb\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','goods',1,'imagetext')\"\r\n             value=\"上传图片\"/>\r\n\t\t\t\r\n        </td>\r\n      </tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">展示图片：</td>            \r\n\t\t\t<td><fieldset class=\"uploadpicarr\">\r\n\t\t\t\t\t<legend>列表</legend>\r\n\t\t\t\t\t<div class=\"picarr-title\">最多可以上传<strong>10</strong> 张图片 <a onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','goods',10,'uppicarr')\" style=\"color:#ff0000; padding:10px;\">  <input type=\"button\" class=\"button\" value=\"开始上传\" /></a>\r\n                    </div>\r\n\t\t\t\t\t<ul id=\"uppicarr\" class=\"upload-img-list\">                    \r\n                     ";
if (is_array($shopinfo["picarr"]) && (0 < count($shopinfo["picarr"]))) {
    foreach ($shopinfo["picarr"] as $pic ) {
        echo "                        <li rel=\"";
        echo $pic;
        echo "\">\r\n\t\t\t\t\t\t<input class=\"input-text\" type=\"text\" name=\"uppicarr[]\" value=\"";
        echo $pic;
        echo "\">\r\n\t\t\t\t\t\t<a href=\"javascript:void(0);\" onClick=\"ClearPicArr('";
        echo $pic;
        echo "','";
        echo WEB_PATH;
        echo "')\">删除</a>\r\n\t\t\t\t\t\t</li>\r\n                        ";
    }
}

echo "                    </ul>\r\n\t\t\t\t</fieldset>\r\n             </td>           \r\n\t\t</tr>  \r\n\t\t<tr>\r\n        \t<td height=\"300\" style=\"width:120px\"   align=\"right\"><font color=\"red\">*</font>商品内容详情：</td>\r\n\t\t\t<td>\r\n\t\t\t\t <textarea name=\"g_content\" class='content' id=\"myeditor\" type=\"text/plain\">\r\n\t    \t\t";
echo $shopinfo["g_content"];
echo "\t   \t\t\t </textarea>            \r\n                <div class=\"content_attr\">\r\n                <label><input name=\"sub_text_des\" type=\"checkbox\"  value=\"off\" checked>是否截取内容</label>\r\n                <input type=\"text\" name=\"sub_text_len\" class=\"input-text\" value=\"250\" size=\"3\">字符至内容摘要<label>         \r\n            \t</div>\r\n            </td>        \r\n\t\t</tr>\r\n        <tr>\r\n            <td align=\"right\" style=\"width:120px\">福分购买：</td>\r\n            <td width=\"900\">\r\n                <input name=\"g_ispoints\" value=\"1\" type=\"checkbox\" ";

if ($shopinfo["g_ispoints"] == 1) {
    echo "checked";
}

echo "/>&nbsp;支持福分购买\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        \t<td align=\"right\" style=\"width:120px\">商品属性：</td>\r\n            <td width=\"900\">\r\n\t\t\t <input name=\"g_style[]\" value=\"2\" type=\"checkbox\" ";

if (in_array($shopinfo["g_style"], array(2, 3))) {
    echo "checked";
}

echo "/>&nbsp;推荐&nbsp;&nbsp;\r\n\t\t\t <input name=\"g_style[]\" value=\"1\" type=\"checkbox\" ";

if (in_array($shopinfo["g_style"], array(1, 3))) {
    echo "checked";
}

echo "/>&nbsp;人气&nbsp;&nbsp;\r\n            </td>          \r\n        </tr>\r\n        <tr height=\"60px\">\r\n\t\t\t<td align=\"right\" style=\"width:120px\"></td>\r\n\t\t\t<td><input type=\"submit\" name=\"dosubmit\" class=\"button\" value=\"修改商品\" /></td>\r\n\t\t</tr>\r\n\t</table>\r\n</form>\r\n</div>\r\n <span id=\"title_colorpanel\" style=\"position:absolute; left:568px; top:155px\" class=\"colorpanel\"></span>\r\n<script type=\"text/javascript\">\r\n     var ue = UE.getEditor('myeditor');\r\n    ue.addListener('ready',function(){\r\n        this.focus()\r\n    });\r\n\tvar info=new Array();\r\n    function gbcount(message,maxlen,id){\r\n\t\t\r\n\t\tif(!info[id]){\r\n\t\t\tinfo[id]=document.getElementById(id);\r\n\t\t}\t\t\t\r\n        var lenE = message.value.length;\r\n        var lenC = 0;\r\n        var enter = message.value.match(/\\r/g);\r\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\r\n        if (CJK != null) lenC += CJK.length;\r\n        if (enter != null) lenC -= enter.length;\t\t\r\n\t\tvar lenZ=lenE+lenC;\t\t\r\n\t\tif(lenZ > maxlen){\r\n\t\t\tinfo[id].innerHTML=''+0+'';\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\tinfo[id].innerHTML=''+(maxlen-lenZ)+'';\r\n    }\r\nfunction set_title_color(color) {\r\n\t$('#title2').css('color',color);\r\n\t$('#title_style_color').val(color);\r\n}\r\nfunction set_title_bold(){\r\n\tif($('#title_style_bold').val()=='bold'){\r\n\t\t$('#title_style_bold').val('');\t\r\n\t\t$('#title2').css('font-weight','');\r\n\t}else{\r\n\t\t$('#title2').css('font-weight','bold');\r\n\t\t$('#title_style_bold').val('bold');\r\n\t}\r\n}\r\n</script>\r\n</body>\r\n</html> ";

?>
