<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/from.js"></script>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
</div>
<div class="nav_tab wid203">
    <div class="btn_list">
        <div class="lf btn cir_l active">短信接口设置</div>
        <!-- <div class="lf cir_r btn" onClick="mobile_check();">测试短信发送</div> -->
        <div class="cl"></div>
    </div>
</div>

<div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <!-- <tr>
            <td>
                <form action="" method="post" id="myform">
                    <div class="mobile_box">
                        <div class="switch_box lf">
                            <input type="hidden" id="is_close3" callback="open_mobile" rel="3" value="<?php echo $mobiles["cfg_mobile_on"]; ?>">
                            <script language="javascript">yg_close("0,1|关闭中,开启中","txt","is_close3","<?php echo $mobiles["cfg_mobile_on"] == "3" ? '1' : ''; ?>");</script>
                        </div>
                        <div class="msg_box lf ml15">
                            <span>互亿无线</span>
                        </div>
                    </div>
                    <div class="from_box lf ml15">
                        用户名：<input type="text" name="mid" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_3"]["mid"]; ?>">
                        密码：<input type="password" name="mpass" class="input-text"  value="******">
                    </div>
                    <div class="button_box lf ml15">
                        <input type="hidden" name="interface" value="3" />
                        <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
                    </div>
                    <div class="cl"></div>
                </form>
            </td>
        </tr> -->
        <!-- <tr>
            <td>
                <form action="" method="post" id="myform">
                    <div class="mobile_box">
                        <div class="switch_box lf">
                            <input type="hidden" id="is_close1" callback="open_mobile" rel="1" value="<?php echo $mobiles["cfg_mobile_on"]; ?>">
                            <script language="javascript">yg_close("0,1|关闭中,开启中","txt","is_close1","<?php echo $mobiles["cfg_mobile_on"] == "1" ? '1' : ''; ?>");</script>
                        </div>
                        <div class="msg_box lf ml15">
                            <span>郑州商讯</span>
                        </div>
                    </div>
                    <div class="from_box lf ml15">
                        用户名：<input type="text" name="mid" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_1"]["mid"]; ?>">
                        密码：<input type="password" name="mpass" class="input-text" style="width: 150px;"  value="******">
                    </div>
                    <div class="button_box lf ml15">
                        <input type="hidden" name="interface" value="1" />
                        <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
                    </div>
                    <div class="cl"></div>
                </form>
            </td>
        </tr> -->
        <!-- <tr>
            <td>
                <form action="" method="post">
                    <div class="mobile_box">
                        <div class="switch_box lf">
                            <input type="hidden" id="is_close2" callback="open_mobile" rel="2" value="<?php echo $mobiles["cfg_mobile_on"]; ?>">
                            <script language="javascript">yg_close("0,1|关闭中,开启中","txt","is_class2","<?php echo $mobiles["cfg_mobile_on"] == "2" ? '1' : ''; ?>");</script>
                        </div>
                        <div class="msg_box lf ml15">
                            <span>北京漫道</span>
                        </div>
                    </div>
                    <div class="from_box lf ml15">
                        用户名：<input type="text" name="mid" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_2"]["mid"]; ?>">
                        密码：<input type="password" name="mpass" class="input-text" style="width: 150px;" value="******">
                        签名：<input type="text" name="mqianming" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_2"]["mqianming"]; ?>">
                    </div>
                    <div class="button_box lf ml15">
                        <input type="hidden" name="interface" value="2" />
                        <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
                    </div>
                    <div class="cl"></div>
                </form>
            </td>
        </tr> -->
        <!-- <tr>
            <td>
                <form action="" method="post" id="myform">
                    <div class="mobile_box">
                        <div class="switch_box lf">
                            <input type="hidden" id="is_close4" callback="open_mobile" rel="4" value="<?php echo $mobiles["cfg_mobile_on"]; ?>">
                            <script language="javascript">yg_close("0,1|关闭中,开启中","txt","is_class4","<?php echo $mobiles["cfg_mobile_on"] == "4" ? '1' : ''; ?>");</script>
                        </div>
                        <div class="msg_box lf ml15">
                            <span>亿美软通</span>
                        </div>
                    </div>
                    <div class="from_box lf ml15">
                        用户名：<input type="text" name="mid" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_4"]["mid"]; ?>">
                        密码：<input type="password" name="mpass" class="input-text" style="width: 150px;" value="******">
                        签名：<input type="text" name="mqianming" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_4"]["mqianming"]; ?>">
                    </div>
                    <div class="button_box lf ml15">
                        <input type="hidden" name="interface" value="4" />
                        <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
                    </div>
                    <div class="cl"></div>
                </form>
            </td>
        </tr> -->
        <tr>
            <td>
                <form action="" method="post" id="myform">
                    <div class="mobile_box">
                        <div class="switch_box lf">
                            <input type="hidden" id="is_close5" callback="open_mobile" rel="5" value="<?php echo $mobiles["cfg_mobile_on"]; ?>">
                            <script language="javascript">yg_close("0,1|关闭中,开启中","txt","is_class5","<?php echo $mobiles["cfg_mobile_on"] == "5" ? '1' : ''; ?>");</script>
                        </div>
                        <div class="msg_box lf ml15">
                            <span>安徽创瑞</span>
                        </div>
                    </div>
                    <div class="from_box lf ml15">
                        用户名：<input type="text" name="mid" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_5"]["mid"]; ?>">
                        密码：<input type="password" name="mpass" class="input-text" style="width: 150px;" value="******">
                        签名：<input type="text" name="mqianming" class="input-text" style="width: 150px;" value="<?php echo $mobiles["cfg_mobile_5"]["mqianming"]; ?>">
                    </div>
                    <div class="button_box lf ml15">
                        <input type="hidden" name="interface" value="5" />
                        <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
                    </div>
                    <div class="cl"></div>
                </form>
            </td>
        </tr>
    </table>
</div><!--table-list end-->
<form action="" method="post" id="status">
    <input type="hidden" name="interface" id="status_id" value="">
    <input type="hidden" name="chg_status" value="submit">
</form>
<!--支付弹出框-->
<style>
#paywindow{position:absolute;z-index:999; display:none}
#paywindow_b{width:372px;height:362px;background:#ff6633; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}
#paywindow_c{width:360px;height:350px;background:#fff;display:block;position:absolute;left:6px;top:6px;}
.p_win_title{ line-height:40px;height:40px;background:#eee;}
.p_win_title b{float:left}
.p_win_title a{float:right;padding:0px 10px;color:#f60}
.p_win_content h1{font-size:25px;font-weight:bold;}
.p_win_ctitle{overflow:hidden;}
.p_win_x_t{ font-size:18px; font-weight:bold;font-family: "Helvetica Neue",FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}
.p_win_but a{ padding:8px 15px; background:#f60; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: "Helvetica Neue",FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }
.p_win_but a:hover{ background:#f50}
.p_win_text a{ font-size:13px; color:#f60}
.pay_window_quit:hover{ color:#f00;}
</style>
<div id="paywindow">
    <div id="paywindow_b"></div>
    <div id="paywindow_c">
        <div class="p_win_title"><div class="lf wid300 tac ft16 fwb">发送测试短信</div><div class="lf"><a href="javascript:void();" class="pay_window_quit">[关闭]</a><b></b></div><div class="cl"></div></div>
        <div class="p_win_content ml20 mt20">
            <div class="p_win_mes ml20">
                 <input type="text" id="ceshi_haoma" class="input-text" style="width:280px;" value="输入测试手机号码..."/>          
            </div>
            <div class="p_win_mes mt20 ml20">
                    <textarea id="ceshi_con" style="width:280px; height:150px;">输入测试内容...(只能检测到新短信接口的内容是否合法)</textarea>
            </div>
            <div class="p_win_mes tar mt20 mr20 ml20">
                <input type="button" value=" 测试短信功能与内容 " class="button" id="ceshi_form">
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    var width = ($(window).width()-372)/2;
    var height = ($(window).height()-442)/2;
    $("#paywindow").css("left",width);
    $("#paywindow").css("top",height);
        
    $(".pay_window_quit").click(function(){
        $("#paywindow").hide();                              
    });

    var mobile = '<?php echo $mobiles["cfg_mobile_on"]; ?>';
    
    $("td.mobile_on_off").each(function(i){
        if($(this).attr("key") == mobile){
            $(this).html("<span class=\"on\">开启中...</span>");
        }else{
            $(this).html("<span class=\"off\">关闭中...</span>");
        }
    });

});
function open_mobile(id){
    $("#status_id").val($("#"+id).attr("rel"));
    $("#status").submit();
}
</script>
<!--支付弹出框-->


<script type="text/javascript">
function mobile_check(){
    $("#paywindow").show();
    return true;
}
$("#ceshi_form").click(function(){
    $.ajaxSetup({
        async : false
    }); 
    var ceshi_haoma=document.getElementById('ceshi_haoma').value;
    var ceshi_con=document.getElementById('ceshi_con').value;   
    if(ceshi_con == ''){
        window.parent.message("内容不能为空!",8,2);
        return;
    }
    $.post("<?php echo WEB_PATH . "/" . ROUTE_M; ?>/setting/mobile", {"ceshi_haoma":ceshi_haoma,"ceshi_con":ceshi_con,"ceshi_submit":true}, function( data ) {
        data = jQuery.parseJSON(data);  
        if ( data[0] == -1 ) {
            window.parent.message( data[1], 8, 3 );
        } else {
            window.parent.message( data[1], 1, 2 );
        }
    });
});

</script>
</body>
</html> 