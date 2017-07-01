<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<?php
$qqband="<p style='color:red;'>已经绑定QQ</p>";
$weiboband="<p style='color:red;'>已经绑定新浪微博</p>";
$weixinband="<p style='color:red;'>已经绑定微信</p>";
$qqshow="<a class='qq'  href='".G_WEB_PATH."/?plugin=1&api=Oauth&action=login&data=qq'></a>";
$weiboshow="<a class='weibo'  href='".G_WEB_PATH."/?plugin=1&api=Oauth&action=login&data=weibo'></a>";
$weixinshow="<a class='weixin'  href='".G_WEB_PATH."/?plugin=1&api=Oauth&action=login&data=weixin'></a>";
    echo "document.write(\"";
    if($userband){
        $qq=$qqshow;
        $weibo=$weiboshow;
        $weixin=$weixinshow;
        foreach($userband as $band){
            switch($band['b_type']){
                case "qq":$qq=$qqband;break;
                case "weibo":$weibo=$weiboband;break;
                case "weixin":$weixin=$weixinband;break;    
            }       
        }
        echo $qq.$weibo.$weixin;
    }else{  
        echo $qqshow;
        echo $weiboshow;
        echo $weixinshow;
    }
    echo "\")"; 