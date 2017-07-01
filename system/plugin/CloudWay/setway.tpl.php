<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<html>
    <head>
        <title><?php echo $ments;?></title>
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
        <style>
            body{ padding: 50px;}
            p{ margin-bottom: 20px;}
        </style>
    </head>
    <body>
        <?php foreach($loconfig['lotterylist'] as $key=>$v){
    ?>      
        <fieldset class="wid500">
            <legend><?php echo $v['name']?>配置</legend>
                                
            <p>                     
                <span class="tab-left">入口文件夹:</span><?php echo $v['dir']?>
            </p>
            <p>                     
                <span class="tab-left">入口函数文件:</span><?php echo $v['apiclass']?>
            </p>
            <p>                     
                <span class="tab-left">入口函数:</span><?php echo $v['apifun']?>
            </p>            
            <p>                     
                <span class="tab-left">功能说明:</span><?php echo $v['comment']?>
            </p>                     
            <p>                     
                <span class="tab-left">是否启用:</span>
            <?php 
                if($v['state']=='1'){echo "<font color=\"#0c0\">已启用</font>";}
                else{
            ?>
            <a style="color:#F60" href="<?php echo WEB_PATH; ?>/plugin-CloudWay-openway-<?php echo $v['type']; ?>">
            <?php echo "未启用"; ?>
            </a>        
            <?php } ?></th>                    
            </p>                        
        </fieldset>             
        <div class="bk30"></div>
         <?php   }?>                        
        
    </body>
</html>