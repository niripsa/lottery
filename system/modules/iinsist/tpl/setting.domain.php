<?php
defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n</head>\r\n<body>\r\n<div class=\"header lr10\">\r\n    ";
echo headerment($ments);
echo "</div>\r\n<div class=\"bk10\"></div>\r\n<div class=\"table-list lr10\">\r\n  <table width=\"100%\" cellspacing=\"0\">\r\n    <thead>\r\n\t\t<tr>\r\n        <th width=\"17%\">绑定的域名</th>\r\n\t\t<th width=\"17%\">绑定的模块</th>\r\n        <th width=\"17%\">绑定的控制器</th>\r\n        <th width=\"17%\">绑定的函数</th>\r\n        <th width=\"17%\">绑定的模板</th>\r\n\t\t<th width=\"15%\">管理操作</th>\r\n\t\t</tr>\r\n    </thead>\r\n  \t<tbody id=\"trlist\">\r\n    \t";
if (is_array($domain) && (0 < count($domain))) {
    foreach ($domain as $key => $do ) {
        echo "        <tr>\r\n        <td align=\"center\">";
        echo $key;
        echo "</td>\r\n        <td align=\"center\">";
        echo "移动端";
        echo "</td>\r\n        <td align=\"center\">";
        echo "移动端";
        echo "</td>\r\n        <td align=\"center\">";
        echo "移动端";
        echo "</td>\r\n        <td align=\"center\">";
        echo "默认";
        echo "</td>\r\n        <td align=\"center\">\r\n        \t<a href=\"javascript:void(0)\" onclick=\"updata(this)\">修改</a>\r\n            <span class=\"lr10\">|</span>\r\n            <a href=\"javascript:void(0)\" onclick=\"removes(this)\">删除</a>\r\n        </td>\r\n        </tr>\r\n        ";
    }
}

echo "\t</tbody>\r\n</table>\r\n</div><!--table-list end-->\r\n<div class=\"bk10\"></div>\r\n<div class=\"header lr10\">\r\n\t<input type=\"button\" class=\"button\" style=\" margin-top:4px;margin-top:4px; margin-left:20px;\" onClick=\"add_band()\" name=\"install\" value=\" 添加新的绑定 \" />\r\n\t<input type=\"button\" class=\"button\" style=\" margin-top:4px;margin-top:4px; margin-left:20px;\" onClick=\"add_mobileband()\" name=\"install\" value=\" 添加移动端绑定 \" />\r\n</div>\r\n<script>\r\nfunction input_to_string(obj,A,t){\r\n\tif(t == 'string'){\r\n\t\tobj.each(function(i){\r\n\t\t\t$(this).parent().text($(this).val());\r\n\t\t});\r\n\t\t$(A).text(\"修改\");\r\n\t}\r\n\t/************************************************************/\r\n\r\n\tif(t == 'input'){\r\n\r\n\t\tvar tds = obj.find(\"td\");\r\n\t\tvar upkey = $(tds[0]).text();\r\n\r\n\t\ttds.each(function(i){\r\n\t\t\tif(i < 5)\r\n\t\t\t$(this).html('<input class=\"input-text\" type=\"text\" style=\"width:70%\" value=\"'+$(this).text()+'\">');\r\n\t\t});\r\n\t\t$(A).text(\"确定\");\r\n\t\t$(A).attr(\"onclick\",\"install(this,'\"+upkey+\"')\");\r\n\t}\r\n}\r\n\r\n\r\nfunction updata(T){\r\n\tvar tr = $($(T).parent().parent());\r\n\tinput_to_string(tr,T,'input');\r\n}\r\nfunction install(T,y){\r\n\tvar domain = '';\r\n    var yy   = '';\r\n\tvar values = new Array();\r\n    var regex  = /^[A-Za-z0-9]*[a-z0-9_.\-]*$/ ;\r\n\tvar ret    = false;\r\n\r\n\tvar tr = $($(T).parent().parent());\r\n\tvar input = tr.find(\"input\");\r\n\r\n\tinput.each(function(i){\r\n\r\n\t\tret = regex.test($(this).val());\r\n\t\tif(!ret){\r\n\t\t\twindow.parent.message(\"格式不正确,只能输入字母,下划线,和点!\");\r\n\t\t\t$(this).css(\"border\",\"1px solid #ff0000\");\r\n\t\t\treturn;\r\n\t\t}\r\n\t\t\r\n\t\r\n\t\t$(this).css(\"border\",\"1px solid #0c0\");\r\n\t\tvalues[i] =  $(this).val();\r\n    });\r\n\tvar submit_name = '';\r\n\tif(y != '' && y != null){\r\n\t\tyy = 'edit';\r\n\t}else{\r\n\t\tyy = 'add';\r\n\t}\r\n\tif(ret){\r\n\t\t$.post(\"";
echo G_MODULE_PATH;
echo "/setting/domain/\",{'domain':values[0],'module':values[1],'action':values[2],'func':values[3],'templates':values[4],'edit_key':y,'dosubmit':yy},function(data){            \r\n               if(data == 'ok'){\r\n\t\t\t\t\twindow.parent.message(\"绑定成功！\",1);\r\n\t\t\t\t\tinput_to_string(input,T,'string');\t\t\t\t\t\r\n\t\t\t\t}else{\r\n\t\t\t\t\twindow.parent.message(data,8);\r\n\t\t\t\t}\r\n\t\t\t\twindow.location.reload();\r\n\t\t});\r\n\t}\r\n\r\n}\r\n\r\nfunction removes(T){\r\n\tvar tr = $(T).parent().parent();\r\n\tvar domain = $(tr.find(\"td\")[0]).text();\r\n\t$.post(\"";
echo G_MODULE_PATH;
echo "/setting/domain/\",{'domain':domain,'dosubmit':'del'},function(data){\r\n                console.log(data);\r\n\t\t\t\tif(data == 'ok'){\r\n\t\t\t\t\twindow.parent.message(\"删除成功！\",1);\r\n\t\t\t\t\ttr.remove();\r\n\t\t\t\t}else{\r\n\t\t\t\t\twindow.parent.message(data,8);\r\n\t\t\t\t}\r\n\t\t\t\twindow.location.reload();\r\n\t});\r\n}\r\n\r\nfunction add_band(){\r\n\tif(!this.n){\r\n\t\tthis.n = 0;\r\n\t}\r\n\tthis.n++;\r\n\tvar html = '';\r\n\t\thtml+='<tr>';\r\n\t\thtml+='<td align=\"center\"><input class=\"input-text\" style=\"width:70%\" value=\"输入域名...\" type=\"text\"></td>';\r\n\t\thtml+='<td align=\"center\"><input class=\"input-text\" style=\"width:70%\" value=\"输入模块名...\" type=\"text\"></td>';\r\n\t\thtml+='<td align=\"center\"><input class=\"input-text\" style=\"width:70%\" value=\"输入控制器名...\" type=\"text\"></td>';\r\n\t\thtml+='<td align=\"center\"><input class=\"input-text\" style=\"width:70%\" value=\"输入函数名...\" type=\"text\"></td>';\r\n\t\thtml+='<td align=\"center\"><input class=\"input-text\" style=\"width:70%\" value=\"\" type=\"text\"></td>';\r\n\t\thtml+='<td align=\"center\">';\r\n\t\thtml+='<a href=\"javascript:void(0)\" onclick=\"install(this)\">添加</a>';\r\n\t\thtml+='<span class=\"lr10\">|</span>';\r\n\t\thtml+='<a href=\"javascript:void(0)\" onclick=\"removes(this)\">删除</a>';\r\n\t\thtml+='</td>';\r\n\t\thtml+='</tr>';\r\n\r\n\t$(\"#trlist\").append(html);\r\n}\r\n\r\nfunction add_mobileband(){\r\n\tvar domain = prompt(\"输入你的移动端域名:\");\r\n\twhile(!domain){\r\n\t\tdomain =  prompt(\"输入你的移动端域名:\");\r\n\t}\r\n\r\n\tvar templates = prompt(\"域名单独绑定模板:\");\r\n\r\n\tif(!confirm(\"是否启用 \"+domain+\" 为移动端域名?\")){\r\n\t\treturn;\r\n\t}\r\n\r\n\r\n\t$.post(\"";
echo G_MODULE_PATH;
echo "/setting/domain/\",{'domain':domain,'templates':templates,'dosubmit':'mobile'},function(data){\r\n\t\tconsole.log(data)\r\n\t\t\t\tif(data == 'ok'){\r\n\t\t\t\t\twindow.parent.message(\"成功！\",1);\t\t\r\n\t\t\t\t}else{\r\n\t\t\t\t\twindow.parent.message(data,8);\r\n\t\t\t\t}\r\n\t\t\t\twindow.location.reload();\r\n\t});\r\n\r\n}\r\n\r\n</script>\r\n</body>\r\n</html>";

