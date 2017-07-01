<?php 
 return array (
  'lotteryway' => 
  array (
    'opennow' => 'default',
  ),
  'lotterylist' => 
  array (
    'default' => 
    array (
      'type' => 'default',
      'name' => '默认开奖',
      'dir' => 'Cloudnormal',//开奖方式文件夹
      'apiclass' => 'Pay_shopinsert',//开奖入口文件类   
      'apifun' => 'pay_insert_shop',//开奖入口函数  
      'autoclass' => 'AutoLottery', //限时揭晓类        
      'state' => '1',
      'html' => 'html',
      'comment' => '原始开奖方式',
      'author' => '韬龙',
      'range' => '> v4',
    ),
    'cqssc' => 
    array (
      'type' => 'cqssc',
      'name' => '时时彩',
      'dir' => 'Cloudcqssc',//开奖方式文件夹
      'apiclass' => 'Pay_shopinsert', //开奖入口文件类   
      'apifun' => 'pay_insert_shop',//开奖入口函数
      'autoclass' => 'AutoLottery',//限时揭晓类       
      'state' => '0',
      'html' => 'html',
      'comment' => '添加外部时时彩开奖数据',
      'author' => '韬龙',
      'range' => '> v4',
    ),
  ),
); 
