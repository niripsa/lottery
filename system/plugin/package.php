<?php 

    /** 
     *  插件配置管理器
     *  插件状态分为: 
     *  
     *  0.  未安装
     *  1.  使用中
     *  2.  已停止
     *  3.  已卸载
     *  
     */

return array (
  'Manager' => 
  array (
    'Name' => '插件管理器',
    'Status' => 1,
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'manager.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Manager.png',
    'Desc' => '框架插件系统管理器！ 不能删除。',
  ),
  'Fund' => 
  array (
    'Name' => '夺宝基金插件',
    'Status' => 1,
    'Action' => '/?plugin=true&api=Fund&action=config',
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Fund.png',
    'Desc' => '框架插件系统管理器！ 不能删除。',
  ),
  'Oauth' => 
  array (
    'Name' => '多账户登录插件',
    'Status' => 1,
    'Action' => '/?plugin=true&api=Oauth&action=config',
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Oauth.png',
    'Desc' => '框架多账户登录绑定插件。',
  ),
  'Pay' => 
  array (
    'Name' => '第三方支付',
    'Status' => 1,
    'Action' => '',
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Pay.png',
    'Desc' => '框架第三方支付插件。',
  ),
  'Captcha' => 
  array (
    'Name' => '验证码',
    'Status' => 1,
    'Action' => '',
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Captcha.png',
    'Desc' => '框架验证码插件。',
  ),
  'Upload' => 
  array (
    'Name' => '上传插件',
    'Status' => 1,
    'Action' => '',
    'Author' => '战线',
    'Email' => 'booobusy@gmail.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Upload.png',
    'Desc' => '框架上传插件。',
  ),
  'CloudWay' => 
  array (
    'Name' => '夺宝计算插件',
    'Status' => 1,
    'Action' => '/?plugin=true&api=CloudWay&action=setway',
    'Author' => '绿灯',
    'Email' => 'gao1631351268671@163.com',
    'Version' => '1.0',
    'Index' => 'setway.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/CloudWay.png',
    'Desc' => '夺宝计算算法设置。',
  ),
  'Template' => 
  array (
    'Name' => '模板插件',
    'Status' => 1,
    'Author' => 'yll',
    'Email' => 'gao1631351268671@163.com',
    'Version' => '1.0',
    'Index' => 'api.php',
    'Install' => '',
    'Uninstall' => '',
    'Photo' => 'http://www.duobao.com/statics/uploads/banner/Template.png',
    'Desc' => '模板插件。',
  ),
);