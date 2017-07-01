<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="Shortcut Icon" href="<?php echo G_WEB_PATH;?>/favicon.ico">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/index.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<title>后台首页</title>
    <script language="javascript">
        function set_div(){
            kj_width=$(window).width();
            kj_height=$(window).height();
            if(kj_width<1000){kj_width=1000;}
            if(kj_height<500){kj_height=500;}

            $(".box").css('height',kj_height-101);
            $(".menu").css('height',kj_height-114);
            $(".menu ul").css('height',kj_height-119);
            //$(".nav").css('width',kj_width-201);
            $(".footer").css('width',kj_width);
            $(".footer .note").css('width',kj_width-850);
            $(".footer .note_box").css('width',kj_width-920);
            $(".footer").css('top',kj_height-55);

            $(".ifr_box").css('width',kj_width-180);
            $(".ifr_box").css('height',kj_height-205);

            $("#iframe_src").css('width',kj_width-182);
            $("#iframe_src").css('height',kj_height-207);

        }
        $(document).ready(function(){
            set_div();
            $("#iframe_src").attr("src","<?php echo G_MODULE_PATH; ?>/index/Tdefault");
            $(".menu li div").click(function(){
                $(this).parent().find("dl").slideToggle();
                $(this).parent().siblings().find('dl').slideUp();
            });
            $(".menu div").click(function(){
                var obj=$(this);
                set_pos(obj);
            });
            $(".menu dd").click(function(){
                var obj=$(this);
                set_pos(obj);
            });
            function set_pos(obj){
                var url= $.trim(obj.attr("src"));
                if(url != '' && url != "undefined"){
                    //var pos_str1=obj.parent().parent().find("div").text();
                    //var pos_str2=obj.text();
                    $("#iframe_src").attr("src",url);
                    $(".nav").hide();
                    //$(".pos").show();
                    $(".menu dd").css("color","#C7C7C7");
                    $(".menu dd").css("fontWeight","normal");
                    obj.css("color","#ffffff");
                    obj.css("fontWeight","bold");
                    //obj.css("color","#fff");
                    //$(".pos .pos_one span").text(pos_str1);
                    //$(".pos .last span").text(pos_str2);
                    $(".ifr_box").css('height',kj_height-120);
                    $("#iframe_src").css('height',kj_height-121);
                }
            }
        });
    </script>
</head>

<?php 

function get_iframe_url($row){
    if($row['url']){
        echo WEB_PATH."/".$row['url'];return;
    }else{
        if($row['m']){
            $url= WEB_PATH."/".$row['m']."/".$row['c']."/".$row['a'];
        }else{
            $url= G_MODULE_PATH."/".$row['c']."/".$row['a'];
        }
        if($row['d']){
            $url=$url."/".$row['d'];
        }
        echo $url;return;
    }

}
?>
<body onResize="set_div();">
<div rel="all">
    <div class="" rel="top">
        <div class="lf" rel="left">
            <div class="header">
                <div class="lf"><p><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/YunGouCMS_logo_white.png" width="130"></p><p>一元夺宝管理系统</p></div>
            </div>
            <div class="menu">
                <ul>
                    <li class="me_mm">
                        <div class="menu0" src="<?php echo G_ADMIN_PATH."/index/Tdefault/"; ?>">后台首页</div>
                    </li>
                    <?php if(is_array($menu) && count($menu)>0){foreach($menu as $k=>$row){?>
                        <li class="me_cc">
                            <div class="menu<?php echo $k+1?>" <?php if(!empty($row['src'])){ echo "src='".$mange_path.$row['src']."'";}?>><?php echo $row['name']?></div>
                            <?php if(is_array($row['sub'])){?>
                                <dl>
                                    <?php foreach($row['sub'] as $rr){?>
                                        <dt><?php echo $rr['name']?></dt>
                                        <?php if(is_array($rr['sub'])){?>
                                            <?php foreach($rr['sub'] as $r){?>
                                                <div class="menu_me"></div>
                                                <dd src="<?php get_iframe_url($r); ?>"><?php echo $r['name']?></dd>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                </dl>
                            <?php }?>
                        </li>
                    <?php } }?>
                </ul>
            </div>
        </div>
        <div class="lf" rel="right">
            <div class="link_list">
                <div class="rf header_case mr20 white">
                    欢迎您：<a href="javascript:;" title="<?php echo $info['username']; ?>"><?php echo $info['username']; ?> [<?php echo $info['group_name']; ?>]</a>
                    <a href="<?php echo G_MODULE_PATH; ?>/user/out" title="退出">[退出]</a>
                    <a href="<?php echo G_WEB_PATH; ?>" title="网站首页" target="_blank">网站首页</a>
                    <a href="<?php echo G_MODULE_PATH; ?>/index/map" title="地图">地图</a>
                </div>
            </div>
            <div class="box">
            <div class="nav">
                <div class="user_info">
                    <div class="lf user_attr">
                        <p class="blue fwb ft14"><?php echo $info['username'];?></p>
                        <p class="blue fwb ft14"><?php echo $info['group_name'];?></p>
                    </div>
                    <div class="lf">
                        <p>上次登录时间：<?php echo date("Y-m-d H:i:s",$info['logintime']);?></p>
                        <p>上次登录IP：<?php echo $info['loginip'];?> (<?php echo $last_ip_addr;?>)</p>
                    </div>
                    <div class="cl"></div>
                </div>
            </div>
            <div class="ifr_box">
                <iframe id="iframe_src" name="iframe" class="iframe"
                        frameborder="no" border="1" marginwidth="0" marginheight="0"
                        src=""
                        scrolling="auto" allowtransparency="yes" style="width:100%; height:100%">
                </iframe>
            </div>
            </div>
        </div>
        <div class="cl"></div>
    </div>
    <div class="" rel="bottom">
        <div class="footer">
            <div class="lf btn_list cir_a">
                <div class="lf btn" onClick="btn_iframef5();">刷新框架</div>
                <div class="lf btn" onClick="btn_caches('<?php echo G_MODULE_PATH; ?>/other/caches');">清空缓存</div>
                <div class="lf btn" onClick="btn_map('<?php echo G_MODULE_PATH; ?>/index/map');">后台地图</div>
                <div class="cl"></div>
            </div>
            <div class="lf search cir_a">
                <form action="<?php echo G_MODULE_PATH; ?>/order/search" method="post" target="iframe" id="search_box">
                    <input type="text" name="s" id="search" rel="查找用户、订单、商品" class="vam wid250"><img id="search_btn" src="<?php echo G_GLOBAL_STYLE; ?>/global/image/search.png" class="vam" height="20px">
                </form>
            </div>
            <div class="lf note cir_a">
                <div class="lf">官方公告：</div><div class="lf note_box"  id="scrollBox2"><div><?php echo $sys_note?></div></div><div class="cl"></div>
            </div>
            <div class="cl"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $.alt("#search");
        $("#search_btn").click(function(){
            if($.trim($("#search").val())!=''){
                $(".nav").hide();
                $("#search_box").submit();
            }
        });
    });
    var upfile_num = 0;
    if(upfile_num){
        //alert("你有"+upfile_num+'个补丁可升级,请到【云应用】-【在线升级】去更新系统！');
        window.message("你有"+upfile_num+'个补丁可升级,请到【云应用】-【在线升级】去更新系统！',8,5);
    }

</script>
<script language="javascript">
    window.onload=function(){
        new Marquee(
            "scrollBox2",  //容器ID
            0,  //向上滚动（0为向上、1为向下、2为向左、3为向右)
            2,  //滚动的步长
            200,  //容器可视宽度
            64,  //容器可视高度
            50,  //定时器 数值越小，滚动的速度越快（1000=1秒，建议不小于20）
            2000,  //间歇停顿时间（0为不停顿，1000=1秒）
            1000,  //开始时的等待时间（0为不等待，1000=1秒）
            34  //间歇滚动间距（可选）
        );
    };
    function Marquee(){
        this.ID=document.getElementById(arguments[0]);
        this.Direction=arguments[1];
        this.Step=arguments[2];
        this.Width=arguments[3];
        this.Height=arguments[4];
        this.Timer=arguments[5];
        this.WaitTime=arguments[6];
        this.StopTime=arguments[7];
        if(arguments[8]){this.ScrollStep=arguments[8];}else{this.ScrollStep=this.Direction>1?this.Width:this.Height;}
        this.CTL=this.StartID=this.Stop=this.MouseOver=0;
        this.ID.style.overflowX=this.ID.style.overflowY="hidden";
        this.ID.noWrap=true;
        this.ID.style.width=this.Width;
        this.ID.style.height=this.Height;
        this.ClientScroll=this.Direction>1?this.ID.scrollWidth:this.ID.scrollHeight;
        this.ID.innerHTML+=this.ID.innerHTML;
        this.Start(this,this.Timer,this.WaitTime,this.StopTime);
    }
    Marquee.prototype.Start=function(msobj,timer,waittime,stoptime){
        msobj.StartID=function(){msobj.Scroll();}
        msobj.Continue=function(){
            if(msobj.MouseOver==1){setTimeout(msobj.Continue,waittime);}
            else{clearInterval(msobj.TimerID); msobj.CTL=msobj.Stop=0; msobj.TimerID=setInterval(msobj.StartID,timer);}
        }
        msobj.Pause=function(){msobj.Stop=1; clearInterval(msobj.TimerID); setTimeout(msobj.Continue,waittime);}
        msobj.Begin=function(){
            msobj.TimerID=setInterval(msobj.StartID,timer);
            msobj.ID.onmouseover=function(){msobj.MouseOver=1; clearInterval(msobj.TimerID);}
            msobj.ID.onmouseout=function(){msobj.MouseOver=0; if(msobj.Stop==0){clearInterval(msobj.TimerID); msobj.TimerID=setInterval(msobj.StartID,timer);}}
        }
        setTimeout(msobj.Begin,stoptime);
    }
    Marquee.prototype.Scroll=function(){
        switch(this.Direction){
            case 0:
                this.CTL+=this.Step;
                if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollTop+=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
                else{if(this.ID.scrollTop>=this.ClientScroll) this.ID.scrollTop-=this.ClientScroll; this.ID.scrollTop+=this.Step;}
                break;
            case 1:
                this.CTL+=this.Step;
                if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollTop-=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
                else{if(this.ID.scrollTop<=0) this.ID.scrollTop+=this.ClientScroll; this.ID.scrollTop-=this.Step;}
                break;
            case 2:
                this.CTL+=this.Step;
                if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollLeft+=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
                else{if(this.ID.scrollLeft>=this.ClientScroll) this.ID.scrollLeft-=this.ClientScroll; this.ID.scrollLeft+=this.Step;}
                break;
            case 3:
                this.CTL+=this.Step;
                if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollLeft-=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
                else{if(this.ID.scrollLeft<=0) this.ID.scrollLeft+=this.ClientScroll; this.ID.scrollLeft-=this.Step;}
                break;
        }
    }
</script>
</body>
</html>