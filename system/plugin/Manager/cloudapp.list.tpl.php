<?php defined('G_IN_ADMIN') or die ('No permission resources.'); ?>
<?php defined("G_EXECMODE") or die ("I'm sorry, you don't have the access"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云应用中心</title>

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
    .font{font-family: 'Microsoft Yahei',"微软雅黑",arial,"宋体",sans-serif;}
    .font_en{font-family:arial}
    body{ background:#f7f7f7;color:#666;width: auto;  padding: 0px 20px;}
    #header{ margin-top: 20px; padding: 5px 0px;position: relative; overflow:visible;}

    #header span{
        display:block;
        float:left;
        text-align:center;
        background: #f8f8f8;
        font-size: 14px;
        width:20%;
        line-height: 40px;
        color: #666;
        cursor: pointer;
        border: 1px solid #e5e5e5;
    }
    #header span.border-left{border-radius: 3px 0px 0px 3px;}
    #header span.border-right{border-radius: 0px 3px 3px 0px;}
    #header span.hover{ background: #f60; color:#fff;}

    span#back{position:absolute; top:5px;left:90px;z-index: -1;background: #f60;text-indent:-9999px;}


    /***************/


    #loading{
        font-weight: bold; color:#f2f2f2;  background: #f60; display: none;
        border-radius: 10px; text-indent: 70px; padding-right: 30px;
        position: relative;

        animation: myfirst 1s;
        -moz-animation: myfirst 1s; /* Firefox */
        -webkit-animation: myfirst 1.5s ;   /* Safari 和 Chrome */
        -o-animation: myfirst 1s;   /* Opera */
        -webkit-animation-iteration-count: infinite;/*定义循环资料，infinite为无限次*/
    }

    @-webkit-keyframes myfirst
    {
        0%{
            width:0%;
        }
        100%{
            width:100%;
        }
    }

    @keyframes myfirst
    {
        from {width:0%;}
        to {width:100%;}
    }

    @-moz-keyframes myfirst
    {
        from {width:0%;}
        to {width:100%;}
    }


    @-o-keyframes myfirst
    {
        from {width:0%;}
        to {width:100%;}
    }


    /********************/

     .footer{ background: #eee; text-align: center; padding: 15px 0px;
             box-shadow:0px 0px 0px 3px #f8f8f8;border-radius: 10px; margin: 15px 0;
     }

    /****************/
    img.thumb{
        width:99%;

    }
    span.btn{
        padding: 5px 8px;border-radius: 3px;display:inline-block; margin: 5px;
    }

    .C_f60{color:#f60}
    .C_f00{color:#f00}

    .B_ccc{ background: #ccc; color:#fff;cursor:pointer;}
    .B_09c{ background: #09c; color:#fff;cursor:pointer;}
    .B_f00{ background: #f00; color:#fff;cursor:pointer;}
    .B_f60{ background: #f60; color:#fff;cursor:pointer;}
    .B_notall{cursor: not-allowed;}


    /************/
    #content{
        background:#f2f2f2;
        padding: 10px;
        box-shadow:0px 0px 0px 3px #eee;
    }
    #content h1{letter-spacing:8px;width: 40%; display: inline-block;}
    #content h2{letter-spacing:5px;display: inline-block;}

    .boxlist{ padding: 20px 0px; overflow: hidden; width: 100%;}
    .null{
        text-align: center; font-size: 25px; display: block;
    }

    .app{
        float:left;
        border:1px solid #e5e5e5;
        width:24%;
        height:auto;
        background: #fff;
        padding: 20px 0px;
        #margin-right: 1%;
        #margin-bottom: 1%;
    }
    .app:hover{
        border: 1px solid #f63;
    }
    .app p{
        width:90%;
        margin: auto; margin-bottom: 10px;
    }
    .app p i{
        color: #f60; font-size: 13px;
        display:inline-block; width: 15%;
        vertical-align: top;
    }
    .app p span.text{
         display:inline-block; max-width: 80%;
    }
    .app .lists{
        border: 1px solid #f00;  display:inline-block; width:60px;

    }


    #box .box_background{
        background:#000; filter:alpha(opacity=50);opacity:0.5;width:100%; height:100%;overflow: hidden;
        position: fixed;top:0px;left:0px;
    }
    #box .box_content{
        background: #fff;  width: 300px; position:fixed;left:38%;top:25%;
        box-shadow:0px 0px 15px 3px #555; padding: 20px 10px;
        border-radius: 3px;text-align: center;
    }
    #box .box_iframe{
        background: #fff;  width: 96%;height:94%; position:fixed;left:2%;top:3%;
        box-shadow:0px 0px 15px 3px #555;border-radius: 3px; border: 0px;
    }

    #box .box_content p{ font-size: 20px;color:#f60; margin-bottom: 15px;}


</style>
</head>
<body class="font">


<div id="header">
    <span class="hover border-left" callback="install_app" data="yes">已安装</span>
    <span class="border-left" callback="install_app" data="no">应用市场</span>
    <span class="border-left" callback="install_app" data="template">我的模板</span>
    <span class="border-right" callback="install_app" data="webtemplate">模板市场</span>
</div>


<div id="content">
    <h1 class="icon">应用列表</h1>
    <h2 class="font_en"><div id="loading">Loading</div></h2>
    <div id="boxlist" class="boxlist"></div>
</div>

<div id="box" style="display: none;">
    <div class="box_background"></div>
    <div class="box_content">
        <p>付款完成前,请不要关闭本页面~！</p>
        <span class="btn B_ccc pay" style="width: 40%;">付款失败</span>
        <span class="btn B_f60 pay" style="width: 40%;">付款成功</span>
    </div>
</div>


<script src="http://jq22.qiniudn.com/masonry-docs.min.js"></script>
<script type="text/javascript">
    var plugins = {};
        plugins.heredoc = function(fn) {
             //return fn.toString().split('\n').slice(1,-1).join('\n') + '\n'
              return fn.toString().match(/\/\*!?(?:\@preserve)?[ \t]*(?:\r\n|\n)([\s\S]*?)(?:\r\n|\n)\s*\*\//)[1];
        }

        Plugin.Action = function(urls){
            location.href = urls;
        }

        plugins.view = function(data,i,keys){
            var html = "";
            if(data.Status==1){
                var status="已启用";
            }else{
                var status="未启用";
            }
            if(keys=="yes"){
                html += '<div class="app">';
                    html += '<p><img class="thumb" width="350px" height="350px" onerror=javascript:this.src="<?php echo G_UPLOAD_PATH; ?>/banner/def.png" src="'+data.Photo+'"/></p>';
                    html += '<p><i>名称:</i> <span class="text">'+data.Name+'</span></p>';
                    html += '<p><i>作者:</i> <span class="text">'+data.Author+'</span></p>';
                    html += '<p><i>版本:</i> <span class="text">'+data.Version+'</span></p>';
                    html += '<p><i>介绍:</i> <span class="text">'+data.Desc+'</span></p>';
                    html += '<p>';
                        html += '<i>操作:</i>';
                        html += '<span class="text">';
                        if(data.Status==0){
                            html += '<span class="btn B_f60" onclick=Plugin.Action("<?php echo G_WEB_PATH; ?>'+data.Install+'")>安  装</span>';
                        }else{
                            html += '<span class="btn B_notall B_ccc">已安装</span>';
                            if(data.Action){
                                html += '<span class="btn B_f60" onclick=Plugin.Action("<?php echo G_WEB_PATH; ?>'+data.Action+'")>配　置</span>';
                            }                           
                        }
                        html += '</span>';
                    html += '</p>';
                html += "</div>";
            }else if(keys=="template"){

                console.log(data)
                html += '<div class="app">';
                html += '<p><img class="thumb" src="'+data.photo+'"/></p>';
                html += '<p><i>名称:</i> <span class="text">'+data.name+'</span></p>';
                html += '<p><i>作者:</i> <span class="text">'+data.author +'</span></p>';
                html += '<p><i>版本:</i> <span class="text">'+data.version+'</span></p>';
                html += '<p><i>介绍:</i> <span class="text">'+data.desc+'</span></p>';
                html += '<p>';
                html += '<i>操作:</i>';
                html += '<span class="text">';
                html += '<span class="btn B_notall B_ccc">'+status+'</span>';
                html += '<span class="btn B_f60" onclick=Plugin.Action("<?php echo G_WEB_PATH; ?>/?plugin=1&api=Template&action=edit&type='+data.dir+'")>配　置</span>';
                html += '</span>';
                html += '</p>';
                html += "</div>";
            }else if(keys=="no"){
                html += '<div class="app">';
                    html += '<p><img class="thumb" src="'+data.Photo+'"/></p>';
                    html += '<p><i>名称:</i> <span class="text">'+data.Name+'</span></p>';
                    html += '<p><i>作者:</i> <span class="text">'+data.Author+'</span></p>';
                    html += '<p><i>版本:</i> <span class="text">'+data.Version+'</span></p>';
                    html += '<p><i>价格:</i> <span class="text C_f00">'+"￥0.00"+'</span></p>';
                    html += '<p><i>介绍:</i> <span class="text">'+data.Desc+'</span></p>';
                    html += '<p>';
                        html += '<i>操作:</i>';
                        html += '<span class="text">';
                            html += '<span class="btn B_ccc">已　装</span>';
                            html += '<span class="btn B_f60" onclick=plugins_btn_pay("'+i+'")>购　买</span>';
                        html += '</span>';
                    html += '</p>';
                html += "</div>";

            }else{
                    html += '<div class="app">';
                    html += '<p><img class="thumb" src="http://www.yungoucms.com/'+data.photo+'"/></p>';
                    html += '<p><i>名称:</i> <span class="text">'+data.name+'</span></p>';
                    html += '<p><i>作者:</i> <span class="text">'+data.author+'</span></p>';
                    html += '<p><i>类型:</i> <span class="text">'+data.type +'</span></p>';
                    html += '<p><i>版本:</i> <span class="text">'+data.version+'</span></p>';
                    html += '<p><i>价格:</i> <span class="text C_f00">￥'+data.price+'</span></p>';
                    html += '<p><i>介绍:</i> <span class="text">'+data.description+'</span></p>';
                    html += '<p>';
                    html += '<i>操作:</i>';
                    html += '<span class="text">';
                    if(data.Status==1){
                        html += '<span class="btn B_f60" onclick=Plugin.Action("<?php echo G_WEB_PATH; ?>/?plugin=1&api=Template&action=install&name='+data.name+'&dir='+data.dir+'&type='+data.type+'&author='+data.author+'&version='+data.version+'&desc='+data.description+'&id='+data.id+'")>安  装</span>';
                    }else{
                        html += '<span class="btn B_f60" onclick=plugins_btn_pay("'+data.name+'")>购　买</span>';
                    }
                    html += '</span>';
                    html += '</p>';
                    html += "</div>";
            }

            return html;
        };

    plugins.Notview = function(){
        var html = "";
            html += '<div class="null">';
                    html += "什么都没有找到....";
            html += "</div>";
            return html;
    };

    function plugins_btn_pay(name){
        $("#box span.pay").attr("name",name);
        $("#box").show();window.open("http://www.yungoucms.com/plugin/plugin.php?action=pay_template&name="+name);
    }




    function install_app(dom){
        var keys = dom.attr("data");
        $("#header span").removeClass("hover");
        dom.addClass("hover");
        var urls =  "";
        if(keys == "yes"){
            urls = "<?php echo G_WEB_PATH; ?>/?plugin=1&api=Manager&action=listinfo";
        }else if(keys == "no") {
            urls = "<?php echo G_WEB_PATH; ?>/?plugin=1&api=Manager&action=weblistinfo";
        }else if(keys=="template"){
            urls = "<?php echo G_WEB_PATH; ?>/?plugin=1&api=Manager&action=template";
        }else if(keys=="webtemplate"){
            urls = "<?php echo G_WEB_PATH; ?>/?plugin=1&api=Manager&action=webtemplate";
        }

        $.post(urls,{},function(data){
                data = data.msg;    plugins['local_view'] = "";

                for(var i in data){
                    plugins['local_view'] += plugins.view(data[i],i,keys);
                }
                if(!plugins['local_view']){
                    $boxlist1.css("height","auto")
                    return $boxlist1.html(plugins.Notview());
                }

                setTimeout(function(){
                        $boxlist1.masonry('destroy')
                        $boxlist1.html(plugins['local_view']).imagesLoaded(function() {
                           $boxlist1.masonry({
                                itemSelector: '.app',
                                isAnimated: true,
                            });
                        });
                },100)
        },'json');

    }


    $(function(){
        $boxlist1 = $("#boxlist");
                $boxlist1.imagesLoaded(function() {
                $boxlist1.masonry({
                        itemSelector: '.app',
                        isAnimated: false,
                });
        });



        $("#header span").click(function(){
            $("#loading").show();

            setTimeout(function(){
                $("#loading").hide();
            },1000)
            eval($(this).attr("callback")+"($(this))");

        });

        $("#box span.pay").click(function(){
            var name = $(this).attr("name");
            if(name == undefined){return}
            $.get("<?php echo G_WEB_PATH; ?>/?plugin=1&api=Manager&action=add&name="+name,{},function(data){
                alert(data.msg)
            },'json');

        });

        install_app($("#header span").eq(0));
    });

</script>
</body>
</html>