<?php 

/**
 *  验证码插件
 *  <战线> booobusy@gmail.com
 *  version 1.0

    支持unicode的话从0x3041到 0x30fe 为日文字
    0x4e00到
    ox9fa5为中文(含繁体)
 **/

//http://www.demo.com/?/plugin=true&api=Captcha
//http://www.demo.com/plugin-Captcha
//http://www.demo.com/?/plugin=true&api=Captcha&action=check



function Plugin_Captcha_image()
{
    ob_clean(); 
    $width   = isset($_GET['w']) ? abs(intval($_GET['w'])) : 80;
    $height  = isset($_GET['h']) ? abs(intval($_GET['h'])) : 30;
    $color   = 'ff6600'; 
    $bgcolor = 'ffffff';
     
    $checkcode = System::load_plugin_class( 'Captcha', 'Captcha' );
    $checkcode->config( $width, $height, $color, $bgcolor, 4, 1 );
    $checkcode->Img_To_Dian( 100, false ); 
    $checkcode->Img_To_Xian( 5 );
    
    // 发送  
    _setcookie( 'Captcha', _encookiecode( strtolower( $checkcode->code ) ) );
    $checkcode->ImgCreate();    
}

function Plugin_Captcha_check()
{
    $code = $_POST['param'];
    if ( _ifcookiecode( $code, 'Captcha' ) )
    {
        _SendMsgJson( 'info', '验证码正确' );
        _SendMsgJson( 'status', 'y', 1 );
    }
    else
    {
        _setcookie( 'Captcha', '' );
        _SendMsgJson( 'info', '验证码错误' );
        _SendMsgJson( 'status', 'x', 1 );
    }
}

$action = isset( $_GET['action'] ) ? basename( $_GET['action'] ) : 'image';
$action = 'Plugin_Captcha_' . $action;
if ( function_exists( $action ) )
{
    $action();
}
else
{
    _SendStatus( 404 );
}