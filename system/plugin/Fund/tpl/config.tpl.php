<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>基金配置</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/from.js"></script>
<style>
body{ padding: 50px;}
p{ margin-bottom: 20px; display: block;}
b{color:#0c0; font-size: 150%;}
.div-p{margin-bottom: 20px; display: block;}
.tab-left{ width:80px;}
</style>
</head>
<body>
    

        <fieldset class="wid500">
            <legend>基金配置</legend>
            
            <form action="<?php echo G_WEB_PATH;?>/?plugin=1&api=Fund&action=postconfig" method="post">         
            <p>             
                <span class="tab-left">当前金额</span><b>3434.00</b>
            </p>
            <div class="div-p">                     
                <span class="tab-left" style="float: left;">开启</span>
                <input type="hidden" name="fund_off" value="<?php echo $F['fund_off'];?>" id="web_off">
                <script language="javascript">yg_close("2,1|关闭,开启","txt","web_off",'<?php echo $F['fund_off'];?>')</script>
                <div class="cl"></div>
                
            </div>      
            <p>                     
                <span class="tab-left">单次金额</span>
                <input type="text" name="fund_money" placeholder="每参与1人次夺宝,将出资多少为基金筹款" value="<?php echo $F['fund_money'];?>" class="input-text wid100" />
            </p>
            <p>                     
                <span class="tab-left">预出资金额</span>
                <input type="text" name="fund_ymoney" placeholder="网站提前注入的资金,如:100万" value="<?php echo $F['fund_ymoney'];?>" class="input-text wid100" />    
            </p>
            <p>     
                <span class="tab-left">总金额</span>
                <input type="text" name="fund_cmoney" id="count-money" onkeypress="return myNumberic(event)" disabled="true" value="<?php echo $F['fund_cmoney'];?>" class="input-text"/>
                <a href="javascript:;" onClick="input_set_disabled('count-money',this);" style="color:#0c0">　　修改</a>
            </p>                        
            <span class="tab-left"></span><input type="submit" value="submit" class="button wid280"/>
            </form>
                    
        </fieldset>

<script>    
function myNumberic(e,len) {
    var obj=e.srcElement || e.target;
    var dot=obj.value.indexOf(".");//alert(e.which);
    len =(typeof(len)=="undefined")?2:len;
    var  key=e.keyCode|| e.which;
    if(key==8 || key==9 || key==46 || (key>=37  && key<=40))//这里为了兼容Firefox的backspace,tab,del,方向键
        return true;
    if (key<=57 && key>=48) { //数字
        if(dot==-1)//没有小数点
            return true;
        else if(obj.value.length<=dot+len)//小数位数
            return true;
        } else if((key==46) && dot==-1){//小数点
            return true;
    }        
    return false;
}


function input_set_disabled(iid,AT){
    var input  = document.getElementById(iid);
    if(this.input_disabled == 'off'){   
        this.input_disabled = 'on';     
        input.disabled = true;      
        AT.innerHTML = "　　修改"
    }else{
        this.input_disabled = 'off';
        input.disabled = false;
        AT.innerHTML = "　　关闭"
    }
}
</script>
</body>
</html> 