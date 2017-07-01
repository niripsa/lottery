<?php 
 defined('G_IN_SYSTEM') or exit('No permission resources.');
 return array (
  'config' => 
  array (
    'suffix' => '.html',
    'stag' => '{wc:',
    'etag' => '}',
  ),
  'skin' => 
  array (
    'pc' => 'newtemplates',
    //'pc' => 'mobile',
    'mobile' => 'mobile',
  ),
  'templates' => 
  array (
    'default' => 
    array (
      'type' => 'pc',
      'photo' => '',
      'name' => 'pc默认版本',
      'dir' => 'default',
      'html' => 'html',
      'author' => '韬龙1',
      'version' => '> v4',
      'desc' => 'a模板',
      'kid' => 125,
      'status' => 0,
    ),
    'mobile' => 
    array (
      'type' => 'mobile',
      'photo' => '',
      'name' => '手机默认模板',
      'dir' => 'mobile',
      'html' => 'html',
      'author' => '韬龙',
      'version' => '> v4',
      'desc' => 'a模板',
      'kid' => 123,
      'status' => 1,
    ),
    'newtemplates' => 
    array (
      'name' => 'xx默认模板',
      'photo' => '',
      'type' => 'pc',
      'dir' => 'newtemplates',
      'html' => 'html',
      'author' => '韬龙',
      'dosubmit' => ' 提交 ',
      'version' => '> v4',
      'desc' => 'a模板',
      'kid' => 124,
      'status' => '1',
    ),
    'orange' => 
    array (
      'name' => 'xx默认模板',
      'photo' => '',
      'type' => 'pc',
      'dir' => 'orange',
      'html' => 'html',
      'author' => '韬龙',
      'dosubmit' => ' 提交 ',
      'version' => '> v4',
      'desc' => 'a模板',
      'kid' => 124,
      'status' => '1',
    ),
    'diynewtemplates' => 
    array (
      'name' => 'pc模板',
      'type' => 'pc',
      'dir' => 'diynewtemplates',
      'html' => 'html',
      'author' => '韬龙',
      'colorlist' => '#DB3752,#F3D9DC,#FFD1D8,#F3D9DC,#CA1B38',
      'colorname' => 'colornew',
    ),
    'diymobile' => 
    array (
      'name' => 'mobile模板',
      'type' => 'mobile',
      'dir' => 'diymobile',
      'html' => 'html',
      'author' => '韬龙',
      'colorlist' => '#FF6600,#FFB320,#FDA700,#FF4400,#FF4400',
      'colorname' => 'colornew',
    ),
    'diyorange' => 
    array (
      'name' => 'pc模板',
      'type' => 'pc',
      'dir' => 'diyorange',
      'html' => 'html',
      'author' => '韬龙',
      'colorlist' => '#FF6600,#FFAC4A,#FFAC4A,#FF4400,#FF4400',
      'colorname' => 'colornew',
    ),
  ),
);
 ?>