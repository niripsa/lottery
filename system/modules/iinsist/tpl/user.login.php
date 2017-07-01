
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>一元夺宝系统后台登陆</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/login.css\" type=\"text/css\">\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery-1.8.3.min.js\"></script>\r\n<script src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/global.js\"></script>\r\n<script src=\"";
echo G_PLUGIN_PATH;
echo "/layer/layer.min.js\"></script>\r\n</head>\r\n<body>\r\n<div class=\"login_box\">\r\n    <div class=\"lh50 tac ft_yh ft20 bg_f63 white\">一元夺宝后台管理系统</div>\r\n    <div class=\"login_ibox\">\r\n        <form action=\"#\" method=\"post\" id=\"form\">\t\t\r\n        <div class=\"input_box\">\r\n            <div class=\"lf input_caption tac\">\r\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/user.png\" rel=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/user_1.png\" class=\"input_caption_img\">\r\n            </div>\r\n            <div class=\"lf input_value\">\r\n                <input type=\"text\" id=\"input-u\" class=\"input_input wid280\" name=\"username\" value=\"\" placeholder=\"请输入帐号\">\r\n            </div>\r\n            <div class=\"cl\"></div>\r\n        </div>\r\n        <div class=\"input_box\">\r\n            <div class=\"lf input_caption tac\">\r\n                <img src=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/pwd.png\" rel=\"";
echo G_GLOBAL_STYLE;
echo "/global/image/pwd_1.png\" class=\"input_caption_img\">\r\n            </div>\r\n            <div class=\"lf input_value\">\r\n                <input type=\"password\" id=\"input-p\" class=\"input_input wid280\" name=\"password\" value=\"\" placeholder=\"请输入登录密码\">\r\n            </div>\r\n            <div class=\"cl\"></div>\r\n        </div>\r\n        <div>\r\n            <div class=\"input_box lf wid260\">\r\n                ";

if (_cfg("web_off")) {
    echo "                <div class=\"lf input_caption tac\">\r\n                    <img src=\"";
    echo G_GLOBAL_STYLE;
    echo "/global/image/yzm.png\" rel=\"";
    echo G_GLOBAL_STYLE;
    echo "/global/image/pwd_1.png\" class=\"input_caption_img\">\r\n                </div>\r\n                <div class=\"lf input_value\">\r\n                    <input type=\"text\" id=\"input-c\" class=\"input_input wid100\" name=\"code\" value=\"\" placeholder=\"请输入验证码\">\r\n                </div>\r\n                <div class=\"lf input_value\">\r\n                    <img id=\"checkcode\" class=\"vam\" src=\"";
    echo G_WEB_PATH;
    echo "/?/plugin=true&api=Captcha/\"/>\r\n                </div>\r\n                <div class=\"cl\"></div>\r\n                ";
}
else {
    echo "                    <div class=\"tac\">网站处于关闭状态,无须验证码直接登录</div>\r\n                ";
}

echo "            </div>\r\n            <div class=\"lf login_btn bg_f63 ft16 ft_yh\" id=\"form_but\">\r\n                    登录\r\n            </div>\r\n            <div class=\"cl\"></div>\r\n        </div>\r\n        </form>\r\n    </div>\r\n</div>\r\n";

echo "\r\n<script type=\"text/javascript\">\r\n    var loading;\r\n    var form_but;\r\n    window.onload=function(){\r\n\r\n        document.onkeydown=function(){\r\n            if(event.keyCode == 13){\r\n                ajaxsubmit();\r\n            }\r\n            return;\r\n        }\r\n        form_but=document.getElementById('form_but');\r\n        form_but.onclick = ajaxsubmit;\r\n        \r\n        ";

if (_cfg("web_off")) {
    echo "        \t\r\n        var checkcode=document.getElementById('checkcode');\r\n        checkcode.src = checkcode.src +\"&\"+ new Date().getTime();\r\n        var src=checkcode.src;\r\n        \r\n        checkcode.onclick=function(){\r\n            this.src=src+'&'+new Date().getTime();\r\n        }\r\n        ";
}

echo "\r\n    }\r\n\r\n    //".'$'."(document).ready(function(){".'$'.".alt(\"#input-u\");".'$'.".alt(\"#input-p\");".'$'.".alt(\"#input-c\");});\r\n\r\n    function ajaxsubmit(){\t\t\r\n        var name=document.getElementById('form').username.value;\r\n        var pass=document.getElementById('form').password.value;\r\n        ";

if (_cfg("web_off")) {
    echo "        var codes=document.getElementById('form').code.value;\r\n        ";
}
else {
    echo "        var codes = '';\r\n        ";
}

echo "        //document.getElementById('form').submit();\r\n        ".'$'.".ajaxSetup({\r\n            async : false\r\n        });\t\t\r\n        ".'$'.".ajax({\r\n            \"url\":window.location.href,\r\n            \"type\": \"POST\",\r\n            \"data\": ({username:name,password:pass,code:codes,ajax:true}),\r\n            //\"beforeSend\":beforeSend, //添加loading信息\r\n            \"success\":success//清掉loading信息\r\n        });\r\n\r\n    }\r\n    function beforeSend(){    \t\r\n        form_but.value=\"登录中...\";\r\n        loading=".'$'.".layer({\r\n            type : 3,\r\n            time : 0,\r\n            shade : [0.5 , '#000' , true],\r\n            border : [5 , 0.5 , '#7298a6', true],\r\n            loading : {type : 4}\r\n        });        \r\n    }\r\n\r\n    function success(data){\t\r\n\t\tconsole.log(data);\r\n        //layer.close(loading);\r\n        form_but.value=\"登录\";\t\t\r\n        var obj = jQuery.parseJSON(data);\r\n        if(!obj.error){\r\n            window.location.href=obj.text;\r\n        }else{\r\n            ".'$'.".layer({\r\n                type :0,\r\n                area : ['auto','auto'],\r\n                title : ['信息',true],\r\n                border : [5 , 0.5 , '#7298a6', true],\r\n                dialog:{msg:obj.text}\r\n            });\r\n            var checkcode=document.getElementById('checkcode');\r\n            var src=checkcode.src;\r\n            checkcode.src='';\r\n            checkcode.src=src;\r\n        }\r\n    }\r\n</script>\r\n";

?>
