<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<html>
    <head>
        <title>oauth config</title>
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
        <style>
            body{ padding: 50px;}
            p{ margin-bottom: 20px;}
            p.p-text{ border:1px solid #eee;background:#fff; padding:10px 5px;}
        </style>
    </head>
    <body>

        <fieldset class="wid500">
            <legend>qq-config</legend>
            
            <form action="<?php echo G_WEB_PATH;?>/?plugin=1&api=Oauth&action=postconfig&data=qq" method="post">                
            <p>                     
                <span class="tab-left">APPID</span><input type="text" name="id" value="<?php echo $config['qq']['id']; ?>" class="input-text wid400"/>
            </p>        
            <p>                     
                <span class="tab-left">APPKEY</span><input type="text" name="key"  value="<?php echo $config['qq']['key']; ?>" class="input-text wid400" />
            </p>
            <p>                     
                <span class="tab-left">callback</span><input type="text" name="callback" value="<?php echo $config['qq']['callback']; ?>"  class="input-text wid400"/>      
            </p>
            <p class="p-text">
                回调地址(callback)  <font color=red>"26%"</font> 表示 <font color=red>"&"</font>,请不要改变<br/>
                <font color=#0c0><?php echo G_WEB_PATH."/?plugin=1%26api=Oauth%26action=callback%26data=qq"; ?></font>
            </p>
            <input type="hidden" name="type" value="qq" />                  
            <span class="tab-left"></span><input type="submit" name="submit" value="submit" class="button wid280"/>
            </form>                 
        </fieldset>             
        <div class="bk30"></div>
        
        <fieldset class="wid500">
            <legend>weibo-config</legend>   
            
            <form action="<?php echo G_WEB_PATH;?>/?plugin=1&api=Oauth&action=postconfig&data=weibo" method="post">             
            <p>                     
                <span class="tab-left">ID</span><input type="text" name="id" value="<?php echo $config['weibo']['id']; ?>" class="input-text wid400"/>
            </p>        
            <p>                     
                <span class="tab-left">KEY</span><input type="text" name="key" value="<?php echo $config['weibo']['key']; ?>" class="input-text wid400"/>
            </p>
            <p>                     
                <span class="tab-left">callback</span><input type="text" name="callback" value="<?php echo $config['weibo']['callback']; ?>"  class="input-text wid400"/>
            </p>
            
            <p class="p-text">
                回调地址(callback)  <br/>
                <font color=#0c0><?php echo G_WEB_PATH."/?plugin=1&api=Oauth&action=callback&data=weibo"; ?></font>
            </p>
            
            
            <input type="hidden" name="type" value="weibo" />                           
            <span class="tab-left"></span><input type="submit" name="submit" value="submit"  class="button wid280"/>
            </form>         
        </fieldset>
        <div class="bk30"></div>
        <fieldset class="wid500">
            <legend>weixin-config</legend>
            
            <form action="<?php echo G_WEB_PATH;?>/?plugin=1&api=Oauth&action=postconfig&data=weixin" method="post">                
            <p>                     
                <span class="tab-left">APPID</span><input type="text" name="id" value="<?php echo $config['weixin']['id']; ?>" class="input-text wid400"/>
            </p>        
            <p>                     
                <span class="tab-left">APPKEY</span><input type="text" name="key"  value="<?php echo $config['weixin']['key']; ?>" class="input-text wid400" />
            </p>
            <p>                     
                <span class="tab-left">callback</span><input type="text" name="callback" value="<?php echo $config['weixin']['callback']; ?>"  class="input-text wid400"/>      
            </p>
            <p class="p-text">
                回调地址(callback)  <font color=red>"26%"</font> 表示 <font color=red>"&"</font>,请不要改变<br/>
                <font color=#0c0><?php echo G_WEB_PATH."/?plugin=1%26api=Oauth%26action=callback%26data=weixin"; ?></font>
            </p>
            <input type="hidden" name="type" value="qq" />                  
            <span class="tab-left"></span><input type="submit" name="submit" value="submit" class="button wid280"/>
            </form>                 
        </fieldset>             
        <div class="bk30"></div>        
        
        
        
        <script src="<?php echo G_WEB_PATH; ?>/?plugin=1&api=Oauth&action=config&data=qq"></script>
        <script src="<?php echo G_WEB_PATH; ?>/?plugin=1&api=Oauth&action=config&data=qq"></script>
        
        
        
    </body>
</html>