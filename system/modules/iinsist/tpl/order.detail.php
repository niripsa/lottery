<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style type="text/css">
tr{height:40px;line-height:40px}
.dingdan_content{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}
.dingdan_content li{ float:left;width:310px;}
.dingdan_content_user{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}
.dingdan_content_user li{ line-height:25px;}

.api_b{width:80px; display:inline-block;font-weight:normal}
.yun_ma{ word-break:break-all; width:200px; background:#fff; overflow:auto; height:100px; border:5px solid #09F; padding:5px;}
</style>
</head>
<body>
<div class="header-title lr10">
    <b>订单详情</b>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
    <div class="dingdan_content">
        <h3 style="clear:both;display:block; line-height:30px;"> <?php echo $goods["g_title"]; ?> </h3>
        <li><b class="api_b">剩余次数：</b> <?php echo $cloud_goods["shenyurenshu"]; ?> 人次    </li>
        <li><b class="api_b">总需次数：</b><?php echo $cloud_goods["zongrenshu"]; ?>人次     </li>
        <li><b class="api_b">商品期数：</b>第 <?php echo $cloud_goods["qishu"]; ?> 期 </li>
        <li><b class="api_b">商品价格：</b><?php echo sprintf("%.2f", $cloud_goods["zongrenshu"] * $cloud_goods["price"]); ?></li>
        <li><b class="api_b"><font color="#ff0000">中奖人</font></b><?php echo get_user_name($cloud_goods["q_uid"]); ?></li>
        <li><b class="api_b"><font color="#ff0000">中奖夺宝码</font></b><?php echo $cloud_goods["q_user_code"]; ?></li>
        <div class="bk10"></div>
        <li><b class="api_b">购买次数：</b><?php echo $record["onum"]; ?>人次</li>
        <li class="yun_ma"><b class="api_b">获得夺宝码：</b><br/>         
            <?php echo str_ireplace(",", "&nbsp;&nbsp;&nbsp;&nbsp;", $record["ogocode"]); ?>
        </li>   
        </li>
    </div>
    <div class="bk10"></div>
    <div class="dingdan_content_user">
        <li><b class="api_b">购买人ID：</b> <?php echo $user["uid"]; ?></li>
        <li><b class="api_b">购买人昵称：</b> <?php echo $user["username"]; ?></li>
        <li><b class="api_b">购买人邮箱：</b> <?php echo $user["email"]; ?></li>        
        <li><b class="api_b">购买人手机：</b> <?php echo $user["mobile"]; ?></li>                 
        <li><b class="api_b">购买时间：</b> <?php echo date("Y-m-d H:i:s", $record["otime"]); ?></li>
        <li><b class="api_b">收货信息：</b>
        <div class="ml20">
            <?php
                if ( is_array( $user_add ) && (0 < count( $user_add ) ) )
                {
                    foreach ( $user_add as $row )
                    {
                        echo $row["sheng"] . " - " . $row["shi"] . " - " . $row["xian"] . " - " . $row["jiedao"];
                        echo "&nbsp;&nbsp;&nbsp;邮编:" . $row["youbian"];
                        echo "&nbsp;&nbsp;&nbsp;收货人:" . $row["shouhuoren"];
                        echo "&nbsp;&nbsp;&nbsp;手机:" . $row["mobile"];
                        echo "<br>";
                    }
                }
                else
                {
                    echo "该用户未填写收货信息,请自行联系买家！";
                }
            ?>
        </div>
        </li>
    </div>          
    <div class="bk10"></div>
    
    <?php if ( 0 < $record["ofstatus"] ) { ?>
    <div class="dingdan_content_user">
        <form action="" method="post">
        <input type="hidden" name="oid" value="<?php echo $record["oid"]; ?>" />
        <li><b class="api_b">当前状态:</b> <font color="#0c0"> <?php echo $record["status_txt"]; ?> </font></li>
        <li><b class="api_b">订单状态:</b>
        <select name="ofstatus">
            <option value="1" <?php echo $record["ofstatus"] == 1 ? 'selected' : ''; ?> >未发货</option>
            <option value="2" <?php echo $record["ofstatus"] == 2 ? 'selected' : ''; ?> >已发货</option>
            <option value="3" <?php echo $record["ofstatus"] == 3 ? 'selected' : ''; ?> >已完成</option>
            <option value="-1" <?php echo $record["ofstatus"] == -1 ? 'selected' : ''; ?> >已作废</option>
        </select>
        </li>
        <li><b class="api_b">物流公司:</b>
        <select name="eid">
        <?php if (is_array($ems) && (0 < count($ems))) { ?>
            <?php foreach ($ems as $key => $row): ?>
                <option value="<?php echo $row["eid"]; ?>" <?php echo $row["eid"] == $ship["eid"] ? 'selected' : ''; ?> ><?php echo $row["ename"]; ?></option>
            <?php endforeach ?>
        <?php } ?>
        </select>
        </li>
        <li><b class="api_b">快递单号:</b>
        <input type="text" name="ecode" value="<?php echo $ship["ecode"]; ?>" class="input-text wid150" /> 填写物流公司快递单号
        </li>
        <li><b class="api_b">快递运费:</b><input type="text" name="emoney" value="<?php echo $ship["emoney"]; ?>" class="input-text wid150"> 元 </li>
        <li><input type="submit" class="button" value="  更新  " name="submit" /></li>        
        </form>
    </div>
    <?php } ?>
    </div><!--table-list end-->
</body>
</html>