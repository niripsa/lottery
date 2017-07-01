<?php


return array(

    "config" =>array(
        "suffix"=>".html",
        "stag" =>"{wc:",
        "etag"   =>"}",
    ),

    "skin" => array(
        "pc" => "newtemplates",
        "mobile" => "mobile"
    ),

    "templates" => array(
        'mobile' => array (
             'type' => "mobile",
             'name' => '手机默认模板',
             'dir' => 'mobile',
             'html' => 'html',
             'author' => '韬龙',
             'range' => '> v4',
             'kid'=>'123',
            'desc'=>'手机默认模板',
            'email'=>'111@QQ.COM',
             'price'=>'0.01'
        ),
        'newtemplates' => array (
             'type' => "pc",
             'name' => '手机默认模板',
             'dir' => 'newtemplates',
             'html' => 'html',
             'author' => '韬龙',
             'range' => '> v4',
             'kid'=>'124',
            'desc'=>'手机默认模板',
            'email'=>'111@QQ.COM',
            'price'=>'0.01'
        ),

    )
);


