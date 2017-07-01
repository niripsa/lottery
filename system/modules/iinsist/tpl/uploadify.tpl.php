
<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n<title>Uploadify</title>\n<link rel=\"stylesheet\" type=\"text/css\" href=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/uploadify.css\" />\n</head>\n<body>\n<div class=\"W\">\n\t<div class=\"Bg\"></div>\n\t<div class=\"Wrap\" id=\"Wrap\">\n\t\t<div class=\"Title\">\n\t\t\t<h3 class=\"MainTit\" id=\"MainTit\">";
echo $title;
echo "</h3>\n\t\t\t<a href=\"javascript:Close();\" title=\"关闭\" class=\"Close\"></a>\n\t\t</div>\n\t\t<div class=\"Cont\">\n\t\t\t<p class=\"Note\">最多上传<strong>";
echo $num;
echo "</strong>个附件,单文件最大<strong>";
echo $size_str;
echo "</strong>,类型<strong>";
echo $uptype;
echo "</strong></p>\n\t\t\t<div class=\"flashWrap\">\n\t\t\t\t<input name=\"uploadify\" id=\"uploadify\" type=\"file\" multiple=\"true\" />\n\t\t\t\t<span>\n                \t";

if ($admincheck) {
    echo "\t\t\t\t\t<input type=\"checkbox\" name=\"iswatermark\" id=\"iswatermark\" /><label>是否添加水印</label>\n\t\t\t\t\t";
}

echo "                </span>\n\t\t\t</div>\n\t\t\t<div class=\"fileWarp\">\n\t\t\t\t<fieldset>\n\t\t\t\t\t<legend>列表</legend>\n\t\t\t\t\t<ul>\n\t\t\t\t\t</ul>\n\t\t\t\t\t<div id=\"fileQueue\">\n\t\t\t\t\t</div>\n\t\t\t\t</fieldset>\n\t\t\t</div>\n\t\t\t<div class=\"btnBox\">\n\t\t\t\t<button class=\"btn\" id=\"SaveBtn\">保存</button>\n\t\t\t\t&nbsp;\n\t\t\t\t<button class=\"btn\" id=\"CancelBtn\">取消</button>\n\t\t\t</div>\n\t\t</div>\n\t\t<!--[if IE 6]>\n\t\t<iframe frameborder=\"0\" style=\"width:100%;height:100px;background-color:transparent;position:absolute;top:0;left:0;z-index:-1;\"></iframe>\n\t\t<![endif]-->\n\t</div><!--Wrap end-->\n</div><!--W end-->\n\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/jquery.min.js\" type=\"text/javascript\"></script> \n<!--防止客户端缓存文件，造成uploadify.js不更新，而引起的“喔唷，崩溃啦”-->\n<script>document.write(\"<script type='text/javascript' \"\n\t\t\t+ \"src='";
echo G_PLUGIN_PATH;
echo "/uploadify/jquery.uploadify.js?\" + new Date()\n\t\t\t+ \"'></s\" + \"cript>\");\n</script>\n\t\t\t\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/uploadify/uploadify-move.js\" type=\"text/javascript\"></script> \n\n\n<script type=\"text/javascript\">\nfunction Close(){\n\t\t$(\"#";
echo $frame;
echo "\", window.parent.document).remove();\n}\n$(\"#CancelBtn\").click(function(){\n\t\t$(\"#";
echo $frame;
echo "\", window.parent.document).remove();\n\t\t//$('#uploadify').uploadifyClearQueue();\n\t\t//$(\".fileWarp ul li\").remove();\n});\n\n/*实例化上传控件 */\n$(function() {\n\t$('#uploadify').uploadify({\n\t\t\t'formData'        : {\n\t\t\t\t'timestamp'   : '";
echo time();
echo "',\n\t\t\t\t'token'       : '";
echo md5("unique_salt" . time());
echo "',\n\t\t\t\t'type'\t\t  : '";
echo _encrypt($type);
echo "',\n\t\t\t\t'path'\t\t  : '";
echo _encrypt($path);
echo "',\n\t\t\t\t'size'\t\t  : '";
echo _encrypt($size);
echo "',\n\t\t\t\t'check'\t\t  : '";
echo $check;
echo "'\n\t\t\t},\n\t\t\t'auto'\t\t\t  : true,\n\t\t\t'method'   \t\t  : 'post',\n\t\t\t'multi'   \t\t  : true,\n\t\t\t'swf'      \t\t  : '";
echo G_PLUGIN_PATH;
echo "/uploadify/uploadify.swf',\n       \t\t'uploader'        : '";
echo G_MODULE_PATH;
echo "/uploadify/insert',\n\t\t\t'queueSizeLimit'  : '";
echo $num;
echo "',\n\t\t\t'fileSizeLimit'   : '";
echo $size;
echo "',\n\t\t\t'fileTypeExts'    : '";
echo $uptype;
echo "',\n\t\t\t'fileTypeDesc'    : '";
echo $desc;
echo "',\n\t\t\t'buttonImage'     : '";
echo G_PLUGIN_PATH;
echo "/uploadify/select.png',\n\t\t\t'queueID'         : 'fileQueue',\n\t\t\t'onUploadStart'   : function(file){\n\t\t\t\t$('#uploadify').uploadify('settings', 'formData', {'iswatermark':$(\"#iswatermark\").is(':checked')});\t\t\t\t\n\t\t\t},\n\t\t\t'onUploadSuccess' : function(file, data, response){\n\t\t\t\t$(\".fileWarp ul\").append(SetImgContent(data));\n\t\t\t\tSetUploadFile();\n\t\t\t}\n\t\t});\t\n\n});\n\n//显示上传的图片\nfunction SetImgContent(data){\n\t\n\tvar obj=eval('('+data+')');  \n\tif(obj.ok == 'no'){\n\t\t//window.parent.message(obj.text,8,2);\n\t\talert(obj.text);\n\t\treturn;\n\t}else{\n\t\tvar sLi = \"\";\n\t\tsLi += '<li class=\"img\">';\n\t\tsLi += '<img src=\"";
echo G_UPLOAD_PATH;
echo "/' + obj.text + '\" width=\"100\" height=\"100\" onerror=\"this.src=\'nopic.png\'\">';\n\t\tsLi += '<input type=\"hidden\" name=\"fileurl_tmp[]\" value=\"' + obj.text + '\">';\n\t\tsLi += '<a href=\"javascript:void(0);\">删除</a>';\n\t\tsLi += '</li>';\n\t\treturn sLi;\n\t}\n}\n\n//删除上传元素DOM并清除目录文件\nfunction SetUploadFile(){\n\t$(\"ul li\").each(function(l_i){\n\t\t$(this).attr(\"id\", \"li_\" + l_i);\n\t})\n\t$(\"ul li a\").each(function(a_i){\n\t\t$(this).attr(\"rel\", \"li_\" + a_i);\n\t}).click(function(){\n\t\t$.get(\n\t\t\t'";
echo WEB_PATH;
echo "/api/uploadify/delupload/',\n\t\t\t{action:\"del\", filename:$(this).prev().val()},\n\t\t\tfunction(){}\n\t\t);\n\t\t$(\"#\" + this.rel).remove();\n\t})\n}\n\t\n\t/*点击保存按钮时\n\t *判断允许上传数，检测是单一文件上传还是组文件上传\n\t *如果是单一文件，上传结束后将地址存入\$input元素\n\t *如果是组文件上传，则创建input样式，添加到\$input后面\n\t *隐藏父框架，清空列队，移除已上传文件样式*/\n$(\"#SaveBtn\").click(function(){\t\n var callback = \"";
echo $func;
echo "\";\n var num = ";
echo $num;
echo ";\n var fileurl_tmp = [];\n  \n\tif(callback != \"undefined\"){\t\n\t\tif(num > 1){\t\n\t\t\t $(\"input[name^='fileurl_tmp']\").each(function(index,dom){\n\t\t\t\tfileurl_tmp[index] = dom.value;\n\t\t\t });\t\n\t\t}else{\n\t\t\tfileurl_tmp = $(\"input[name^='fileurl_tmp']\").val();\t\n\t\t}\t\n\t\teval('window.parent.'+callback+'(fileurl_tmp)');\n\t\t$(window.parent.document).find(\"#";
echo $frame;
echo "\").remove();\n\t\treturn;\n\t}\t\t\t\t\t \n\tif(num > 1){\n\t\t\tvar fileurl_tmp = \"\";\n\t\t\t$(\"input[name^='fileurl_tmp']\").each(function(){\n\t\t\t\tfileurl_tmp += '<li rel=\"'+ this.value +'\"><input class=\"input-text\" type=\"text\" name=\"";
echo $input;
echo "[]\" value=\"'+ this.value +'\" /><a href=\"javascript:void(0);\" onclick=\"ClearPicArr(\''+ this.value +'\',\'";
echo WEB_PATH;
echo "\')\">删除</a></li>';\t\n\t\t\t});\t\t\t\n\t\t\t$(window.parent.document).find(\"#";
echo $input;
echo "\").append(fileurl_tmp);\n\t}else{\n\t\t\t$(window.parent.document).find(\"#";
echo $input;
echo "\").val($(\"input[name^='fileurl_tmp']\").val());\n\t}\n\n\t$(window.parent.document).find(\"#";
echo $frame;
echo "\").remove();\n});\n\n\n</script>\n</body>\n</html>";

?>
