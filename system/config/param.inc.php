<?php 
/*
    default  默认访问路由
    routes   自定义路由
*/
return array (
    'page_p'             => "p",     //分页,分割符
    'page_q'             => "page",  //query 请求分页
    'plugin_begin_route' => "plugin",
    'default'            => array( 'm' => 'index', 'c' => 'index', 'a' => 'init' ),
    'routes' => array(
        // 登录注册
        'login/(:any)'    => "member/user/login/$1",
        'register/(:any)' => 'member/user/register/$1',
        'distributor/(:any)' => 'member/receive/receive_invitation/$1',
        
        'uname/(:any)'    => 'member/us/uname/$1',
        
        'club'            => "index/club/init",

        // 商品
        'cgoods/(:any)'         => 'index/cloud_goods/cgitem/$1',
        'cgoods_list/(:any)'    => 'index/cloud_goods/cglist/$1',
        'recomgoods_list/(:any)'    => 'index/cloud_goods/recomglist/$1',
        'cgdataserver/(:any)'   => 'index/cloud_goods/cgdataserver/$1',
        'cgoods_lottery/(:any)' => 'index/cloud_goods/cglottery/$1',
        'autolottery/(:any)'    => 'index/cloud_goods/cloud_autolottery/$1',
        'cgoodsdesc/(:any)'     => 'index/cloud_goods/cgoodsdesc/$1',
        'cgoodsresult/(:any)'   => 'index/cloud_goods/CalResult/$1',
        'goods/(:any)'          => 'index/goods/gitem/$1',
        'goods_list/(:any)'     => 'index/goods/glist/$1',

        // 搜索
        'soso=(:any)' => 'index/index/search/$1',
        
        // 夺宝记录
        'buyrecord'    => 'index/index/cloud_gorecord',
        'buyrecordbai' => 'index/index/buyrecordbai',
        
        // 文章
        'article'             => 'index/article/lists',
        'article-(:any).html' => 'index/article/show/$1',
        
        // 单页面
        'single/(:any)' => 'index/article/single/$1',

        // 模板预览
        'skin=(:any)' => 'index/index/skinchange/$1',

    )
);