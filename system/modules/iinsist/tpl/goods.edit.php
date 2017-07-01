
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
echo "/kindeditor/kindeditor.js\"></script>\r\n\t    <!-- 编辑器源码文件 -->\r\n\t    <script type=\"text/javascript\" src=\"";
echo G_PLUGIN_PATH;
echo "/kindeditor/lang/zh_CN.js\"></script>\r\n<!--\r\n<script type=\"text/javascript\">\r\nvar editurl=Array();\r\nediturl['editurl']='";
echo G_PLUGIN_PATH;
echo "/ueditor/';\r\nediturl['imageupurl']='";
echo G_ADMIN_PATH;
echo "/ueditor/upimage/';\r\nediturl['imageManager']='";
echo G_ADMIN_PATH;
echo "/ueditor/imagemanager';\r\n</script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.config.js\"></script>\r\n<script type=\"text/javascript\" charset=\"utf-8\" src=\"";
echo G_PLUGIN_PATH;
echo "/ueditor/ueditor.all.min.js\"></script>-->\r\n<style>\r\n\t.bg{background:#fff url(";
echo G_GLOBAL_STYLE;
echo "/global/image/ruler.gif) repeat-x scroll 0 9px }\r\n\t.color_window_td a{ float:left; margin:0px 10px;}\r\n</style>\r\n</head>\r\n<body>\r\n<script>\r\n$(function(){ \r\n\r\n\t$(\"#category\").change(function(){ \r\n\tvar parentId=$(\"#category\").val(); \r\n\tif(null!= parentId && \"\"!=parentId){ \r\n\t\t$.getJSON(\"";
echo WEB_PATH;
echo "/api/brand/json_brand/\"+parentId,{cid:parentId},function(myJSON){\r\n\t\tvar options=\"\";\r\n\t\tif(myJSON.length>0){ \t\t\t\r\n\t\t\t//options+='<option value=\"0\">≡ 请选择品牌 ≡</option>'; \r\n\t\t\tfor(var i=0;i<myJSON.length;i++){ \r\n\t\t\t\toptions+=\"<option value=\"+myJSON[i].id+\">\"+myJSON[i].name+\"</option>\"; \r\n\t\t\t} \r\n\t\t\t$(\"#brand\").html(options);\t\t} \r\n\t\t}); \r\n\t}  \r\n\t}); \r\n}); \r\n</script>\r\n<div class=\"header lr10\">\r\n\t";
echo $this->headerment();
echo "    \r\n</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table_form lr10\">\r\n<form method=\"post\" action=\"\">\r\n\t<table width=\"100%\"  cellspacing=\"0\" cellpadding=\"0\">\r\n    \t<tr style=\"background-color:#FFe;height:50px;\">\r\n\t\t\t<td align=\"right\" style=\"width:120px\" style=\"font-weight:bold\"></td>\r\n\t\t\t<td>\r\n            \t<a target=\"_blank\" href=\"";
echo WEB_PATH;
echo "/goods/";
echo $shopinfo["id"];
echo "\"><b>第(<font color=\"red\">";
echo $shopinfo["qishu"];
echo "</font>)期  ";
echo $shopinfo["title"];
echo "</b></a>\r\n\t\t\t\t<br />\r\n\t\t\t\t总价格 <b style=\"color:red\">";
echo $shopinfo["money"];
echo "</b>&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t单次夺宝价格\t<b style=\"color:red\">";
echo $shopinfo["yunjiage"];
echo "</b>&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t已参与 <b style=\"color:red\">";
echo $shopinfo["canyurenshu"];
echo "</b> 人次&nbsp;&nbsp;&nbsp;\r\n\t\t\t\t期数 <b style=\"color:red\">";
echo $shopinfo["qishu"];
echo "/";
echo $shopinfo["maxqishu"];
echo "</b>&nbsp;&nbsp;&nbsp;\r\n            </td>\r\n\t\t\t\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>所属分类：</td>\r\n\t\t\t<td>\r\n             <select name=\"cateid\" id=\"category\" class=\"wid100\">               \r\n                ";
echo $categoryshtml;
echo "                \r\n             </select> \r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>所属品牌：</td>\r\n\t\t\t<td>\r\n            \t<select id=\"brand\" name=\"brand\" class=\"wid100\">                \t\r\n                \t<option value=\"";
echo $shopinfo["brandid"];
echo "\">";
echo $BrandList[$shopinfo["brandid"]]["name"];
echo "</option>                    \r\n\t\t\t\t\t";

foreach ($BrandList as $brand ) {
    echo "\t\t\r\n                    <option value=\"";
    echo $brand["id"];
    echo "\">";
    echo $brand["name"];
    echo "</option>\r\n                    ";
}

echo "\t\t\t\t</select>\r\n\t\t\t</td>\r\n\t\t</tr>      \r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>商品标题：</td>\r\n\t\t\t<td>\r\n            <input  type=\"text\" id=\"title\" value=\"";
echo $shopinfo["title"];
echo "\" \r\n             name=\"title\" onKeyUp=\"return gbcount(this,100,'texttitle');\"  class=\"input-text wid400 bg\">\r\n            <span style=\"margin-left:10px\">还能输入<b id=\"texttitle\">100</b>个字符</span>\r\n           \r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">副标题：</td>\r\n\t\t\t<td><input  type=\"text\" value=\"";
echo $shopinfo["title2"];
echo "\" style=\"";
echo $shopinfo["title_style"];
echo "\" name=\"title2\" id=\"title2\" onKeyUp=\"return gbcount(this,100,'texttitle2');\"  class=\"input-text wid400\">\r\n\t\t\t             <input type=\"hidden\"  value=\"";
echo $title_color;
echo "\"   name=\"title_style_color\" id=\"title_style_color\"/>\r\n            <input type=\"hidden\" value=\"";
echo $title_bold;
echo "\"  name=\"title_style_bold\" id=\"title_style_bold\"/>\r\n            <script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/colorpicker.js\"></script>\r\n            <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/colour.png\" width=\"15\" height=\"16\" onClick=\"colorpicker('title_colorpanel','set_title_color');\" style=\"cursor:hand\"/>\r\n             <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/bold.png\" onClick=\"set_title_bold();\" style=\"cursor:hand\"/>\r\n            <span class=\"lr10\">还能输入<b id=\"texttitle2\">100</b>个字符</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">关键字：</td>\r\n\t\t\t<td><input type=\"text\" value=\"";
echo $shopinfo["keywords"];
echo "\" name=\"keywords\"  name=\"title\"  class=\"input-text wid300\" />\r\n            <span class=\"lr10\">多个关键字请用   ,  号分割开</span>\r\n            </td>\r\n\t\t</tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">商品描述：</td>\r\n\t\t\t<td><textarea name=\"description\" class=\"wid400\"  onKeyUp=\"gbcount(this,250,'textdescription');\" style=\"height:60px\">";
echo $shopinfo["description"];
echo "</textarea><br /> <span>还能输入<b id=\"textdescription\">250</b>个字符</span>\r\n            </td>\r\n\t\t</tr>\t\r\n        <tr>      \r\n\t\t\t<td align=\"right\" style=\"width:120px\">最大期数：</td>     \r\n            <td><input type=\"text\" name=\"maxqishu\" value=\"";
echo $shopinfo["maxqishu"];
echo "\" class=\"input-text\" style=\"width:50px; text-align:center\" onKeyUp=\"value=value.replace(/\D/g,'')\">期,\t&nbsp;&nbsp;&nbsp;期数上限为65535期,每期揭晓后会根据此值自动添加新一期商品！</td>\r\n\t\t</tr>\t\r\n        <tr>\r\n         <td align=\"right\" style=\"width:120px\"><font color=\"red\">*</font>缩略图：</td>\r\n        <td>\r\n\t\t\t<img src=\"";
echo G_UPLOAD_PATH . "/" . $shopinfo["thumb"];
echo "\" style=\"border:1px solid #eee; padding:1px; width:50px; height:50px;\">\r\n           \t<input type=\"text\" id=\"imagetext\" value=\"";
echo $shopinfo["thumb"];
echo "\" name=\"thumb\" class=\"input-text wid300\">\r\n\t\t\t<input type=\"button\" class=\"button\"\r\n             onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','image','shopimg',1,500000,'imagetext')\" \r\n             value=\"上传图片\"/>\r\n\t\t\t\r\n        </td>\r\n      </tr>\r\n        <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">展示图片：</td>            \r\n\t\t\t<td><fieldset class=\"uploadpicarr\">\r\n\t\t\t\t\t<legend>列表</legend>\r\n\t\t\t\t\t<div class=\"picarr-title\">最多可以上传<strong>10</strong> 张图片 <a onClick=\"GetUploadify('";
echo WEB_PATH;
echo "','uploadify','缩略图上传','image','shopimg',10,500000,'uppicarr')\" style=\"color:#ff0000; padding:10px;\">  <input type=\"button\" class=\"button\" value=\"开始上传\" /></a>\r\n                    </div>\r\n\t\t\t\t\t<ul id=\"uppicarr\" class=\"upload-img-list\">                    \r\n                     ";

foreach ($shopinfo["picarr"] as $pic ) {
    echo "                        \r\n                        <li rel=\"";
    echo $pic;
    echo "\"><input class=\"input-text\" type=\"text\" name=\"uppicarr[]\" value=\"";
    echo $pic;
    echo "\"><a href=\"javascript:void(0);\" onClick=\"ClearPicArr('";
    echo $pic;
    echo "','";
    echo WEB_PATH;
    echo "')\">删除</a></li>\r\n                        ";
}

echo "  \r\n                    </ul>\r\n\t\t\t\t</fieldset>\r\n             </td>           \r\n\t\t</tr>  \r\n\t\t<tr>\r\n        \t<td height=\"300\" style=\"width:120px\"   align=\"right\"><font color=\"red\">*</font>商品内容详情：</td>\r\n\t\t\t<td>\r\n            \t<style>\r\n\t\t\t\t.content_attr {\r\n\t\t\t\t\tborder: 1px solid #CCC;\r\n\t\t\t\t\tpadding: 5px 8px;\r\n\t\t\t\t\tbackground: #FFC;\r\n\t\t\t\t\tmargin-top: 6px;\r\n\t\t\t\t\twidth:915px; \r\n\t\t\t\t}\r\n\t\t\t\t.content{\r\n\t\t\t\t\twidth: 915px;\r\n\t\t\t\t\theight:300px;\r\n\r\n\t\t\t\t}\r\n\t\t\t\t</style>\r\n\t\t\t\t <textarea name=\"content\" class='content' id=\"myeditor\" type=\"text/plain\">\r\n\t    \t\t";
echo $shopinfo["content"];
echo "\t   \t\t\t </textarea>\r\n                <div class=\"content_attr\">\r\n                <label><input name=\"sub_text_des\" type=\"checkbox\"  value=\"off\" checked>是否截取内容</label>\r\n                <input type=\"text\" name=\"sub_text_len\" class=\"input-text\" value=\"250\" size=\"3\">字符至内容摘要<label>         \r\n            \t</div>\r\n            \t\r\n            </td>        \r\n\t\t</tr>         \r\n        <tr>\r\n        \t<td align=\"right\" style=\"width:120px\">商品属性：</td>\r\n            <td width=\"900\">\r\n\t\t\t <input name=\"goods_key[pos]\" value=\"1\" type=\"checkbox\" ";

if ($shopinfo["pos"]) {
    echo "checked";
}

echo "/>&nbsp;推荐&nbsp;&nbsp;\r\n\t\t\t <input name=\"goods_key[renqi]\" value=\"1\" type=\"checkbox\" ";

if ($shopinfo["renqi"]) {
    echo "checked";
}

echo "/>&nbsp;人气&nbsp;&nbsp; \r\n            </td>          \r\n        </tr>\r\n         <tr>\r\n\t\t\t<td align=\"right\" style=\"width:120px\">限时揭晓：</td>\r\n\t\t\t<td>          \r\n             选择日期：\r\n              <input name=\"xsjx_time\" type=\"text\" id=\"xsjx_time\" value=\"";
echo $shopinfo["xsjx_time"];
echo "\" class=\"input-text posttime\"  readonly=\"readonly\" />\r\n\t\t\t\t<script type=\"text/javascript\">\r\n\t\t\t\tdate = new Date();\r\n\t\t\t\tCalendar.setup({\r\n\t\t\t\t\tinputField     :    \"xsjx_time\",\r\n\t\t\t\t\tifFormat       :    \"%Y-%m-%d\",\r\n\t\t\t\t\tshowsTime      :    true,\r\n\t\t\t\t\ttimeFormat     :    \"24\"\r\n\t\t\t\t});\r\n\t\t\t\t</script>\r\n             <label><input name=\"xsjx_time_h\" type=\"radio\" value=\"36000\" ";

if ($shopinfo["xsjx_time_h"] == 36000) {
    echo "checked";
}

echo "> 上午10点 </label>           \r\n             <label><input name=\"xsjx_time_h\" type=\"radio\" value=\"54000\" ";

if ($shopinfo["xsjx_time_h"] == 54000) {
    echo "checked";
}

echo "> 下午3点 </label>\r\n             <label><input name=\"xsjx_time_h\" type=\"radio\" value=\"79200\" ";

if ($shopinfo["xsjx_time_h"] == 79200) {
    echo "checked";
}

echo "> 晚上10点 </label> \r\n             <span class=\"lr10\">&nbsp;</span>\t<b>不选择时间则不参与限时揭晓, 本期揭晓后自动添加的下一期不是限时揭晓商品！</b>\r\n            </td>        \r\n\t\t</tr>\r\n        <tr height=\"60px\">\r\n\t\t\t<td align=\"right\" style=\"width:120px\"></td>\r\n\t\t\t<td><input type=\"submit\" name=\"dosubmit\" class=\"button\" value=\"修改商品\" /></td>\r\n\t\t</tr>\r\n\t</table>\r\n</form>\r\n</div>\r\n <span id=\"title_colorpanel\" style=\"position:absolute; left:568px; top:155px\" class=\"colorpanel\"></span>\r\n<script type=\"text/javascript\">\r\n    //实例化编辑器\r\n     KindEditor.ready(function(K) {\r\n\t\twindow.editor = K.create('#myeditor');\r\n\t});\r\n\t /*\r\n    var ue = UE.getEditor('myeditor');\r\n\r\n    ue.addListener('ready',function(){\r\n        this.focus()\r\n    });\r\n    function getContent() {\r\n        var arr = [];\r\n        arr.push( \"使用editor.getContent()方法可以获得编辑器的内容\" );\r\n        arr.push( \"内容为：\" );\r\n        arr.push(  UE.getEditor('myeditor').getContent() );\r\n        alert( arr.join( \"\\n\" ) );\r\n    }\r\n    function hasContent() {\r\n        var arr = [];\r\n        arr.push( \"使用editor.hasContents()方法判断编辑器里是否有内容\" );\r\n        arr.push( \"判断结果为：\" );\r\n        arr.push(  UE.getEditor('myeditor').hasContents() );\r\n        alert( arr.join( \"\\n\" ) );\r\n    }\r\n\t*/\r\n\tvar info=new Array();\r\n    function gbcount(message,maxlen,id){\r\n\t\t\r\n\t\tif(!info[id]){\r\n\t\t\tinfo[id]=document.getElementById(id);\r\n\t\t}\t\t\t\r\n        var lenE = message.value.length;\r\n        var lenC = 0;\r\n        var enter = message.value.match(/\\r/g);\r\n        var CJK = message.value.match(/[^\\x00-\\xff]/g);//计算中文\r\n        if (CJK != null) lenC += CJK.length;\r\n        if (enter != null) lenC -= enter.length;\t\t\r\n\t\tvar lenZ=lenE+lenC;\t\t\r\n\t\tif(lenZ > maxlen){\r\n\t\t\tinfo[id].innerHTML=''+0+'';\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\tinfo[id].innerHTML=''+(maxlen-lenZ)+'';\r\n    }\r\n\t\r\nfunction set_title_color(color) {\r\n\t$('#title2').css('color',color);\r\n\t$('#title_style_color').val(color);\r\n}\r\nfunction set_title_bold(){\r\n\tif($('#title_style_bold').val()=='bold'){\r\n\t\t$('#title_style_bold').val('');\t\r\n\t\t$('#title2').css('font-weight','');\r\n\t}else{\r\n\t\t$('#title2').css('font-weight','bold');\r\n\t\t$('#title_style_bold').val('bold');\r\n\t}\r\n}\r\n\r\n//API JS\r\n//window.parent.api_off_on_open('open');\r\n</script>\r\n</body>\r\n</html> ";

?>
