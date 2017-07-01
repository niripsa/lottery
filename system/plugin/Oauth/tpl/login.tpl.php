<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<!doctype html>
<html lang="zh-CN"><head>
<title><?php echo _cfg("web_name"); ?></title>
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
<style>
    img .img-code{
        cursor: pointer;
    }
    a{color:#f60}
    
    
    
    .nav-pills>li>a{
        background: #e1e1e1;
    }
    .nav-pills>li.active>a, .nav-pills>li.active>a:hover, .nav-pills>li.active>a:focus {
        color: #fff;
        background-color: #f60;
    }
    
    .btn-success {
          color: #fff;
          background-color: #f60;
          border-color: #f70;
    }
    
    .btn-success:hover,.btn-success:focus {
          color: #fff;
          background-color: #f70;
          border-color: #f60;
    }
    

    body{
        background:#f7f7f7; padding:0; margin:0;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        display: block;
    }
    .window{
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    
    .width{ width:600px;}
        
    .border{
        border:1px solid #d2d2d2;
        border-radius: 6px;
        box-shadow: 0px 0px 5px #c6c6c6;
    }
    
    .box{ background:#fff; padding:20px 10px;}
    
    .footer{ font-size:12px; color:#666; line-height:30px; padding-left:20px; }
    .form-box{ width:350px; margin:auto; padding-bottom:30px;}
    
    
    @media only screen and (max-width:600px ) and (min-width:240px) {
        .width{ width:98%;min-width: 240px;}
        .form-box{ width:98%;min-width: 240px;}
        .nav-pills>li{ float: left; width:49%}
        .footer{display: none;}
        .button_band{ width: 100%;}
    }
    
    #message{ font-size:12px; color:#f00;}
    #button_band{ float: right;}
</style>
</head>


<body>

<div style="height:3px; width:100%; background:#f60"></div>
<div class="window width">
    <div class="header">
        <BR>
    </div>
    
    <div class="box border">
        <div style=" padding:10px;border-bottom: 1px dotted #d8d8d8;">
            欢迎登陆 <a href="<?php echo G_WEB_PATH; ?>"><?php echo _cfg("web_name_two"); ?></a> 请绑定你在<?php echo _cfg("web_name_two"); ?>的账户. 
        </div>    
        <br>        
        <div class="form-box">
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-justified" role="tablist" id="myTab">
              <li role="presentation" class="active"><a href="#login" role="tab" data-toggle="tab">绑定已有账户</a></li>
              <li role="presentation"><a href="#register" role="tab" data-toggle="tab"> 注册新账户 </a></li>
            </ul>       
            <br>        
             <div class="input-group">
                         <span class="input-group-addon">邮箱或手机</span>
                         <input id="user" type="text" class="form-control" placeholder="请输入您在<?php echo _cfg("web_name_two"); ?>的账户">
             </div>
             <br>
            <div class="input-group col-xs-12">
              <span class="input-group-addon">密　　　码</span><input id="pass" type="password" class="form-control" placeholder="请输入您的密码">               
            </div>
            <br>
            <!--
            <div class="input-group col-xs-12" style="display:none;">
                         <span class="input-group-addon">验　证　码</span>
                         <input id="code" type="text"  class="form-control" placeholder="验证码">  <img class="img-code" src="<?php echo G_WEB_PATH; ?>/?plugin=1&api=Captcha">   
            </div>              
            -->             
            <button type="button" id="button_band" class="btn btn-success">登录并绑定</button>  
            <span id="message" style="display:inline-block;"></span>
            
        </div>
          
    </div>
    
    <div class="footer">
        提示：为保障帐号安全，
        请认准本页URL地址必须以 <?php echo G_WEB_PATH; ?> 开头  
    </div>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script type="text/javascript">

    function aaa(){
        var tempwindow=window.open('_blank');
            tempwindow.location='http://www.baidu.com';             
    }
    
    $("img.img-code").click(function(){
        var src = $(this).attr("src");      
        $(this).attr("src",src+new Date().getTime());
    });
    
    
    var band_type = "login"
    $('#myTab a').click(function (e) {
      e.preventDefault();
      if($(this).attr("href") == "#login"){
        band_type = "login" 
        $("button").text("登录并绑定");
      }else{
        band_type = "register"    
        $("button").text("注册并绑定");
      }
    });

    $("#button_band").click(function(){
        var button = $(this);
        var text = button.text();
        button.text("绑定中...");
        
        var pdata = {}
            pdata.user = user.value;
            pdata.pass = pass.value;
            pdata.type = band_type;
        //  pdata.code = code.value;        
        
        $.post("<?php echo $bandurl; ?>",pdata,function(data){
            
            if(data.code){
                alert("code")               
            }   
            if(data.status=='1'){
                button.text("绑定成功");
                $("#message").text("跳转中...");
                
                if(window.opener){
                    //我是父窗口新建的      
                    window.opener.location.href = '<?php echo G_WEB_PATH; ?>';
                    window.close();
                }else{                  
                    window.location.href = '<?php echo G_WEB_PATH; ?>';
                }
    
                
                
            }else{
                $("#message").text(data.msg);
                button.text(text);
                $("img.img-code").click();
            }
        },'json');
    });
    
</script>



</body></html>