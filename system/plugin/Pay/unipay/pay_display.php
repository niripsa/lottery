<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $pay_title;?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo G_TEMPLATES_STYLE?>/css/Comm_weixin.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo G_TEMPLATES_STYLE?>/css/WeixinPay.css" />
    <script language="javascript" type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/weixin/JQuery132.js"></script>
</head>
<body>
    <input name="hidShopID" type="hidden" id="hidShopID" value="141027161101330617" />
    <input name="hidIsBuyPay" type="hidden" id="hidIsBuyPay" value="1" />
    <div class="wx_header">
        <div class="wx_logo"><img title="夺宝<?php echo $pay_title;?>" alt="<?php echo $pay_title;?>标志" src="<?php echo G_TEMPLATES_STYLE?>/images/<?php echo $pay_pic;?>" /></div>
    </div>
    <div class="weixin">
        <div class="weixin2">
            <b class="wx_box_corner left pngFix"></b><b class="wx_box_corner right pngFix"></b>
            <div class="wx_box pngFix">
                <div class="wx_box_area">
                    <div class="pay_box qr_default">
                        <div class="area_bd"><span class="wx_img_wrapper"  id="qr_box">
                           <div align="center" id="qrcode" class="ewm_wrapper"></div>
                            <img style="left: 50%; opacity: 0; display: none; margin-left: -101px;" class="guide pngFix" src="<?php echo G_TEMPLATES_STYLE?>/images/wxwebpay_guide.png" alt="" id="guide" />
                        </span>
                            <div class="msg_default_box" id="resmsgdiv" style="margin-top: 20px;"><i  class="icon_wxa"></i>
                                <p id="return_msg">
                                    请使用<?php echo $pay_name;?>扫描<br/>
                                    二维码以完成支付
                                </p>
                            </div>
                            <div class="msg_box"><i class="icon_wx pngFix"></i>
                                <p><strong>扫描成功</strong>请在手机确认支付</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wx_hd">
                    <div class="wx_hd_img icon_wx"></div>
                </div>
                <div class="wx_money"><span>￥</span><?php echo $money;?></div>
                <!--支付订单号-->
                <div class="wx_pay">
                    <p><span  class="wx_left">支付订单号</span>
                    <span id="dingdan"  ddcode="<?php echo $config['pay_code'];?>"  class="wx_right" ><?php echo $config['pay_code'];?></span></p>
                     <p><span  class="wx_left">订单时间</span>
                    <span  class="wx_right" ><?php echo date("Y-m-d H:i:s",time());?></span></p>
                    <p><span class="wx_left">商品名称</span><span class="wx_right"><?php echo $config['pay_title'];?></span></p>
                </div>
                <div class="wx_kf">
                    <div class="wx_kf_img icon_wx"></div>
                    <div class="wx_kf_wz">
                        <p><?php echo _cfg('web_name');?></p>
                        <p><?php echo _cfg('cell');?></p>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<?php 
    $code_url = $aPayInfo['code_url'];
?>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/weixin/qrcode.js"></script>
    <script>
        if(<?php echo $code_url != NULL; ?>)
        {
            var url = "<?php echo $code_url;?>";
            //参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
            var qr = qrcode(10, 'M');
            qr.addData(url);
            qr.make();
            var wording=document.createElement('p');
            wording.innerHTML = "支付完成前，请勿关闭此页面！";
            var code=document.createElement('DIV');
            code.innerHTML = qr.createImgTag();
            var element=document.getElementById("qrcode");
            element.appendChild(wording);
            element.appendChild(code);
        }
    </script>    
    <script>
        var d=$("#dingdan").attr("ddcode");
            setInterval(function(){
              $.ajax({
                url:'/?/member/account/ajax_check_ispay',
                type: "post",      
                cache:false,  
                dataType: "json",  
                data: {ocode:d},                 
                async : true,
                success: function(res){
                    console.log(res);
                    if(res.errno == 0){
                       $(".icon_wxa").css("background-position","0 0px");
                       $("#return_msg").text(res.errmsg);
                       
                       window.location.href="<?php echo WEB_PATH;?>/member/account/userbalance";                          
                    }
                }             
              });           
            }, 2000);
        </script>   

</body>
</html>
