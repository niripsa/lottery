<?php defined('G_IN_SYSTEM')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>To UnionPay Page</title>
    <meta content="app-id=518966501" name="apple-itunes-app">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <link href="<?php echo G_TEMPLATES_CSS; ?>/mobile/comm.css?v=130715" rel="stylesheet" type="text/css" />
    <link href="<?php echo G_TEMPLATES_CSS; ?>/mobile/member.css?v=130726" rel="stylesheet" type="text/css" />
    <style>
        .g-pay-auto{ padding:30px; text-align:center}
        .g-pay-auto a{ padding:5px; font-size:16px;}
        #spanTime{ color:#f00; display:inline-block;font-size:18px; font-weight:bold}
        #divInfo{ font-size:18px; display:block; color:#666; text-align:center; line-height:50px;}
        #error{ line-height:20px; padding-top:30px;}
        #pay_ok{ color:#0c0; font-size:18px; line-height:40px; cursor:pointer; background:#f8f8f8; border:1px solid #eee;border-radius:5px; width:180px; margin:auto;}
    </style>
    <script type="text/javascript">
        function goUnionPay() {
            var times;
            var ifrSrc = "uppay://uppayservice/?style=token&paydata=<?php echo $base64_url; ?>";
            if (!ifrSrc) {
                return;
            }           
            var ifr = document.createElement('iframe');     
            ifr.src = ifrSrc;
            ifr.style.display = 'none';
            document.body.appendChild(ifr);
            setTimeout(function() {
                document.body.removeChild(ifr);
                document.getElementById('divInfo').innerHTML = '<div class="line" id="error"><li>抱歉,跳转支付失败,失败原因</li><li>1.您没有安装银联客户端</li> <li>2.您的浏览器不支持银联支付(建议用手机自带网页浏览器！)</li> <li>3.安装成功后刷新本页即可进入支付页面</li><li id="pay_ok" onclick="pay_ok()">选其它支付点这里!</li></div>';
                clearInterval(times);
            }, 5000);
            
            var x = 4;
                times = setInterval(function(){
                document.getElementById('spanTime').innerHTML = x;
                x--;
                if(x<=0)
                {
                    clearInterval(times);
                    return;
                }
            },1000);
        }
        function pay_ok(){  
            location.href = "<?php echo $ReturnUrl; ?>";
        }
    </script>
</head>
<body onLoad="goUnionPay()">
    <header class="g-header">
        <div class="head-l">
            <a href="javascript:;" onClick="history.go(-1)" class="z-HReturn"><s></s><b>返回</b></a>
        </div>
        <h2>结算支付</h2>
        <div class="head-r">
            
        </div>
    </header>
    <div class="line5"><span class="linead">推荐</span><span class="black">
用【手机自带网页浏览器】打开银联支付！
</span></div>   
    <div class="line" id="divInfo">如果<div id="spanTime">5</div>秒没有跳转,请手动下载客户端!</div>    
    <div class="anniu">
        <span class="linead">安卓</span> <a href="http://mpay.unionpay.com/getclient?platform=android&type=securepayplugin" class="ziti"><em>下载银联安卓客户端</em></a>
        </div><br><div class="anniu"><span class="linead">苹果</span>
         <a href="http://mpay.unionpay.com/getclient?platform=ios&type=securepayplugin" class="ziti"><em>下载银联苹果客户端</em></a>
    </div>
<div class="line5"><span class="linead">帮助</span><span class="black">
使用银联支付需要先安装上方的银联客户端，如果银联客户端已安装成功，但您的UC或QQ等浏览器还是无法跳转到银联支付页面或让您重新安装，说明您当前使用的浏览器不支持银联支付，请选择使用【手机自带网页浏览器】打开本站进行支付，安卓或苹果手机的自带网页浏览器一般都能完美支持银联支付。
</span></div>   
     <?php include templates ("mobile/index","footer"); ?>
</body>
</html>