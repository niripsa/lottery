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
        <?php foreach($paylist as $key=>$v){
    ?>      
        <fieldset class="wid500">
            <legend><?php echo $v['pay_name'];?></legend>   
            
            <form action="<?php echo G_WEB_PATH;?>/?plugin=1&api=Pay&action=submit&data=<?php echo $v['pay_id'];?>" method="post">
            <?php $v['pay_key']=@unserialize($v['pay_key'])?>
            <?php foreach($v['pay_key'] as $key1=>$h){
             ?>                             
            <p>                     
                <span class="tab-left"><?php echo $h['name']?></span><input type="text" name="pay_key[<?php echo $key1; ?>]" value="<?php echo $h['val']?>" class="input-text wid400"/>
            </p>
            <?php }
             ?>                     
            <input type="hidden" name="type" value="weibo" />                           
            <span class="tab-left"></span><input type="submit" name="submit" value="submit"  class="button wid280"/>
            </form>         
        </fieldset>
                    
        <div class="bk30"></div>
         <?php   }?>                        
        
    </body>
</html>