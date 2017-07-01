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
        
            var x = 4;
            setInterval(function(){
                document.getElementById('spanTime').innerHTML = x;
                x--;
                if(x<=0)
                {
                    window.top.location.reload();
                    clearInterval();
                    return;
                }
            },1000);
        }

    </script>
</head>
<body onLoad="goUnionPay()">
    <header class="g-header">
        <div class="head-l">
            <a href="javascript:;" onClick="history.go(-1)" class="z-HReturn"><s></s><b>返回</b></a>
        </div>
        <h2>正在连接银联…</h2>
        <div class="head-r">
            
        </div>
    </header>
    
    <div id="divInfo"><div id="spanTime">5</div>秒后.会自动刷新！</div>         
     <?php include templates ("mobile/index","footer"); ?>
</body>
</html>